<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Instancia;
use App\Models\LogDeMensagem;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Serviço para integração com API WhatsApp (Baileys/Evolution).
 */
class WhatsAppService
{
    /**
     * URL base da API.
     */
    protected string $baseUrl;

    /**
     * Chave de autenticação da API.
     */
    protected string $apiKey;

    /**
     * Timeout padrão para requisições.
     */
    protected int $timeout = 30;

    /**
     * Construtor do serviço.
     */
    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.whatsapp.url', 'http://localhost:8080'), '/');
        $this->apiKey = config('services.whatsapp.key', '');
    }

    /**
     * Cria uma nova instância na API.
     */
    public function createInstance(Instancia $instancia): array
    {
        try {
            $response = $this->request('POST', '/instance/create', [
                'instanceName' => $instancia->token,
                'token' => $instancia->token,
                'qrcode' => true,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $instancia->updateStatus(Instancia::STATUS_QR_PENDING);

                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Erro ao criar instância'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Create Instance Error', [
                'instancia_id' => $instancia->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtém o QR Code para conexão.
     */
    public function getQrCode(Instancia $instancia): array
    {
        try {
            $response = $this->request('GET', "/instance/qrcode/{$instancia->token}");

            if ($response->successful()) {
                $data = $response->json();

                // Salva o QR Code na instância
                if (isset($data['qrcode'])) {
                    $instancia->update([
                        'qr_code' => $data['qrcode'],
                        'status' => Instancia::STATUS_QR_PENDING,
                    ]);
                }

                return [
                    'success' => true,
                    'qr_code' => $data['qrcode'] ?? null,
                    'base64' => $data['base64'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Erro ao obter QR Code'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - QR Code Error', [
                'instancia_id' => $instancia->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verifica o status da conexão.
     */
    public function getConnectionStatus(Instancia $instancia): array
    {
        try {
            $response = $this->request('GET', "/instance/connectionState/{$instancia->token}");

            if ($response->successful()) {
                $data = $response->json();
                $state = $data['instance']['state'] ?? 'close';

                // Mapeia o estado da API para o status interno
                $status = match ($state) {
                    'open' => Instancia::STATUS_ONLINE,
                    'connecting' => Instancia::STATUS_CONNECTING,
                    'qrcode' => Instancia::STATUS_QR_PENDING,
                    default => Instancia::STATUS_OFFLINE,
                };

                $instancia->updateStatus($status);

                return [
                    'success' => true,
                    'state' => $state,
                    'status' => $status,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Erro ao verificar status'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Connection Status Error', [
                'instancia_id' => $instancia->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Envia uma mensagem de texto.
     */
    public function sendTextMessage(
        Instancia $instancia,
        string $telefone,
        string $mensagem
    ): array {
        try {
            $response = $this->request('POST', "/message/sendText/{$instancia->token}", [
                'number' => $this->formatPhoneNumber($telefone),
                'text' => $mensagem,
            ]);

            $log = LogDeMensagem::create([
                'instancia_id' => $instancia->id,
                'tipo' => LogDeMensagem::TIPO_ENVIADA,
                'telefone_destino' => $telefone,
                'mensagem' => $mensagem,
                'payload' => $response->json(),
                'status' => $response->successful()
                    ? LogDeMensagem::STATUS_ENVIADA
                    : LogDeMensagem::STATUS_ERRO,
                'message_id' => $response->json('key.id'),
                'erro_detalhes' => $response->successful()
                    ? null
                    : $response->json('message', 'Erro desconhecido'),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('key.id'),
                    'log_id' => $log->id,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Erro ao enviar mensagem'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Send Message Error', [
                'instancia_id' => $instancia->id,
                'telefone' => $telefone,
                'error' => $e->getMessage(),
            ]);

            LogDeMensagem::create([
                'instancia_id' => $instancia->id,
                'tipo' => LogDeMensagem::TIPO_ERRO,
                'telefone_destino' => $telefone,
                'mensagem' => $mensagem,
                'status' => LogDeMensagem::STATUS_ERRO,
                'erro_detalhes' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Envia uma mensagem com mídia.
     */
    public function sendMediaMessage(
        Instancia $instancia,
        string $telefone,
        string $mediaUrl,
        string $mediaType = 'image',
        ?string $caption = null
    ): array {
        try {
            $endpoint = match ($mediaType) {
                'image' => "/message/sendMedia/{$instancia->token}",
                'video' => "/message/sendMedia/{$instancia->token}",
                'audio' => "/message/sendWhatsAppAudio/{$instancia->token}",
                'document' => "/message/sendMedia/{$instancia->token}",
                default => "/message/sendMedia/{$instancia->token}",
            };

            $response = $this->request('POST', $endpoint, [
                'number' => $this->formatPhoneNumber($telefone),
                'mediatype' => $mediaType,
                'media' => $mediaUrl,
                'caption' => $caption,
            ]);

            $log = LogDeMensagem::create([
                'instancia_id' => $instancia->id,
                'tipo' => LogDeMensagem::TIPO_ENVIADA,
                'telefone_destino' => $telefone,
                'mensagem' => $caption ?? "[$mediaType] $mediaUrl",
                'payload' => [
                    'media_url' => $mediaUrl,
                    'media_type' => $mediaType,
                    'response' => $response->json(),
                ],
                'status' => $response->successful()
                    ? LogDeMensagem::STATUS_ENVIADA
                    : LogDeMensagem::STATUS_ERRO,
                'message_id' => $response->json('key.id'),
                'erro_detalhes' => $response->successful()
                    ? null
                    : $response->json('message', 'Erro desconhecido'),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('key.id'),
                    'log_id' => $log->id,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Erro ao enviar mídia'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Send Media Error', [
                'instancia_id' => $instancia->id,
                'telefone' => $telefone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Desconecta a instância.
     */
    public function disconnect(Instancia $instancia): array
    {
        try {
            $response = $this->request('DELETE', "/instance/logout/{$instancia->token}");

            $instancia->updateStatus(Instancia::STATUS_OFFLINE);
            $instancia->update(['qr_code' => null]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful()
                    ? 'Instância desconectada com sucesso'
                    : $response->json('message', 'Erro ao desconectar'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Disconnect Error', [
                'instancia_id' => $instancia->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Reinicia a instância.
     */
    public function restart(Instancia $instancia): array
    {
        try {
            $response = $this->request('PUT', "/instance/restart/{$instancia->token}");

            if ($response->successful()) {
                $instancia->updateStatus(Instancia::STATUS_CONNECTING);
            }

            return [
                'success' => $response->successful(),
                'message' => $response->successful()
                    ? 'Instância reiniciada com sucesso'
                    : $response->json('message', 'Erro ao reiniciar'),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service - Restart Error', [
                'instancia_id' => $instancia->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Faz uma requisição para a API.
     */
    protected function request(string $method, string $endpoint, array $data = []): Response
    {
        $url = $this->baseUrl . $endpoint;

        return Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout($this->timeout)
        ->$method($url, $data);
    }

    /**
     * Formata o número de telefone para o padrão da API.
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove caracteres não numéricos
        $phone = preg_replace('/\D/', '', $phone);

        // Se não começar com código do país, adiciona 55 (Brasil)
        if (strlen($phone) === 11 || strlen($phone) === 10) {
            $phone = '55' . $phone;
        }

        return $phone;
    }
}
