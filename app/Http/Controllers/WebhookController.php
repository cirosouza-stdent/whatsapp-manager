<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Instancia;
use App\Models\LogDeMensagem;
use App\Models\Webhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller para processar webhooks do WhatsApp.
 */
class WebhookController extends Controller
{
    /**
     * Processa webhook recebido da API WhatsApp.
     */
    public function handle(Request $request, string $token): JsonResponse
    {
        // Encontra a instância pelo token
        $instancia = Instancia::where('token', $token)->first();

        if (!$instancia) {
            Log::warning('Webhook recebido para token inválido', [
                'token' => $token,
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Instância não encontrada'], 404);
        }

        $payload = $request->all();
        $evento = $this->detectEvent($payload);

        // Registra o webhook
        $webhook = Webhook::create([
            'instancia_id' => $instancia->id,
            'evento' => $evento,
            'payload' => $payload,
            'status' => Webhook::STATUS_RECEBIDO,
        ]);

        try {
            // Processa o webhook baseado no tipo de evento
            $resposta = match ($evento) {
                Webhook::EVENTO_MESSAGE => $this->handleMessage($instancia, $payload),
                Webhook::EVENTO_STATUS => $this->handleStatus($instancia, $payload),
                Webhook::EVENTO_CONNECTION => $this->handleConnection($instancia, $payload),
                Webhook::EVENTO_QR_CODE => $this->handleQrCode($instancia, $payload),
                default => $this->handleUnknown($instancia, $payload),
            };

            $webhook->markAsProcessed($resposta);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'response' => $resposta,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook', [
                'instancia_id' => $instancia->id,
                'evento' => $evento,
                'error' => $e->getMessage(),
            ]);

            $webhook->markAsError($e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detecta o tipo de evento do webhook.
     */
    protected function detectEvent(array $payload): string
    {
        if (isset($payload['event'])) {
            return match ($payload['event']) {
                'messages.upsert', 'message' => Webhook::EVENTO_MESSAGE,
                'messages.update', 'status' => Webhook::EVENTO_STATUS,
                'connection.update', 'connection' => Webhook::EVENTO_CONNECTION,
                'qrcode.updated', 'qrcode' => Webhook::EVENTO_QR_CODE,
                default => $payload['event'],
            };
        }

        // Detecta por estrutura do payload
        if (isset($payload['data']['message'])) {
            return Webhook::EVENTO_MESSAGE;
        }

        if (isset($payload['data']['qrcode'])) {
            return Webhook::EVENTO_QR_CODE;
        }

        if (isset($payload['data']['state'])) {
            return Webhook::EVENTO_CONNECTION;
        }

        return 'unknown';
    }

    /**
     * Processa evento de mensagem recebida.
     */
    protected function handleMessage(Instancia $instancia, array $payload): string
    {
        $messageData = $payload['data']['message'] ?? $payload['data'] ?? [];

        // Extrai informações da mensagem
        $remoteJid = $messageData['key']['remoteJid'] ?? '';
        $telefone = preg_replace('/@.*$/', '', $remoteJid);
        $fromMe = $messageData['key']['fromMe'] ?? false;
        $messageId = $messageData['key']['id'] ?? null;

        // Extrai o texto da mensagem
        $mensagem = $messageData['message']['conversation']
            ?? $messageData['message']['extendedTextMessage']['text']
            ?? $messageData['message']['imageMessage']['caption']
            ?? '[Mídia recebida]';

        // Registra o log da mensagem
        LogDeMensagem::create([
            'instancia_id' => $instancia->id,
            'tipo' => $fromMe ? LogDeMensagem::TIPO_ENVIADA : LogDeMensagem::TIPO_RECEBIDA,
            'telefone_destino' => $telefone,
            'mensagem' => $mensagem,
            'payload' => $messageData,
            'status' => LogDeMensagem::STATUS_ENTREGUE,
            'message_id' => $messageId,
        ]);

        // Aqui você pode adicionar lógica de resposta automática
        $this->processAutoResponse($instancia, $telefone, $mensagem);

        return "Mensagem registrada: $messageId";
    }

    /**
     * Processa evento de status de mensagem.
     */
    protected function handleStatus(Instancia $instancia, array $payload): string
    {
        $messageId = $payload['data']['key']['id'] ?? null;
        $status = $payload['data']['status'] ?? null;

        if ($messageId && $status) {
            // Mapeia o status
            $statusMapped = match ($status) {
                'PENDING' => LogDeMensagem::STATUS_PENDENTE,
                'SENT', 'SERVER_ACK' => LogDeMensagem::STATUS_ENVIADA,
                'DELIVERY_ACK', 'DELIVERED' => LogDeMensagem::STATUS_ENTREGUE,
                'READ', 'PLAYED' => LogDeMensagem::STATUS_LIDA,
                'ERROR' => LogDeMensagem::STATUS_ERRO,
                default => null,
            };

            if ($statusMapped) {
                LogDeMensagem::where('message_id', $messageId)
                    ->where('instancia_id', $instancia->id)
                    ->update(['status' => $statusMapped]);
            }

            return "Status atualizado: $messageId -> $status";
        }

        return 'Status processado';
    }

    /**
     * Processa evento de conexão.
     */
    protected function handleConnection(Instancia $instancia, array $payload): string
    {
        $state = $payload['data']['state'] ?? $payload['state'] ?? null;

        if ($state) {
            $status = match ($state) {
                'open', 'connected' => Instancia::STATUS_ONLINE,
                'connecting' => Instancia::STATUS_CONNECTING,
                'close', 'disconnected' => Instancia::STATUS_OFFLINE,
                default => Instancia::STATUS_OFFLINE,
            };

            $instancia->updateStatus($status);

            return "Conexão atualizada: $state";
        }

        return 'Conexão processada';
    }

    /**
     * Processa evento de QR Code.
     */
    protected function handleQrCode(Instancia $instancia, array $payload): string
    {
        $qrCode = $payload['data']['qrcode'] ?? $payload['qrcode'] ?? null;

        if ($qrCode) {
            $instancia->update([
                'qr_code' => $qrCode,
                'status' => Instancia::STATUS_QR_PENDING,
            ]);

            return 'QR Code atualizado';
        }

        return 'QR Code processado';
    }

    /**
     * Processa evento desconhecido.
     */
    protected function handleUnknown(Instancia $instancia, array $payload): string
    {
        Log::info('Webhook desconhecido recebido', [
            'instancia_id' => $instancia->id,
            'payload' => $payload,
        ]);

        return 'Evento desconhecido registrado';
    }

    /**
     * Processa resposta automática.
     */
    protected function processAutoResponse(Instancia $instancia, string $telefone, string $mensagem): void
    {
        // Dispara job para processar resposta automática
        // Isso pode ser expandido para integrar com chatbots, IA, etc.
        \App\Jobs\ProcessAutoResponse::dispatch($instancia, $telefone, $mensagem);
    }
}
