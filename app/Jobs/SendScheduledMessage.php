<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\MensagemAgendada;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para envio de mensagens agendadas.
 */
class SendScheduledMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Número de tentativas.
     */
    public int $tries = 3;

    /**
     * Timeout do job em segundos.
     */
    public int $timeout = 60;

    /**
     * Construtor do job.
     */
    public function __construct(
        public MensagemAgendada $mensagem
    ) {
    }

    /**
     * Executa o job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // Recarrega a mensagem do banco
        $this->mensagem->refresh();

        // Verifica se ainda está agendada (pode ter sido cancelada)
        if ($this->mensagem->status !== MensagemAgendada::STATUS_AGENDADA) {
            Log::info('Mensagem não está mais agendada', [
                'mensagem_id' => $this->mensagem->id,
                'status' => $this->mensagem->status,
            ]);
            return;
        }

        // Marca como processando
        $this->mensagem->markAsProcessing();

        // Carrega a instância
        $instancia = $this->mensagem->instancia;

        // Verifica se a instância está online
        if (!$instancia->isOnline()) {
            $this->mensagem->markAsError('Instância não está online');
            Log::warning('Tentativa de envio com instância offline', [
                'mensagem_id' => $this->mensagem->id,
                'instancia_id' => $instancia->id,
            ]);
            return;
        }

        try {
            // Verifica se tem mídia
            if (!empty($this->mensagem->midia)) {
                $result = $whatsAppService->sendMediaMessage(
                    $instancia,
                    $this->mensagem->telefone_destino,
                    $this->mensagem->midia['url'],
                    $this->mensagem->midia['type'] ?? 'image',
                    $this->mensagem->mensagem
                );
            } else {
                $result = $whatsAppService->sendTextMessage(
                    $instancia,
                    $this->mensagem->telefone_destino,
                    $this->mensagem->mensagem
                );
            }

            if ($result['success']) {
                $this->mensagem->markAsSent();
                Log::info('Mensagem agendada enviada com sucesso', [
                    'mensagem_id' => $this->mensagem->id,
                    'message_id' => $result['message_id'] ?? null,
                ]);
            } else {
                $this->mensagem->markAsError($result['error'] ?? 'Erro desconhecido');
                Log::error('Falha ao enviar mensagem agendada', [
                    'mensagem_id' => $this->mensagem->id,
                    'error' => $result['error'] ?? 'Erro desconhecido',
                ]);
            }
        } catch (\Exception $e) {
            $this->mensagem->markAsError($e->getMessage());
            Log::error('Exceção ao enviar mensagem agendada', [
                'mensagem_id' => $this->mensagem->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Tratamento de falha do job.
     */
    public function failed(\Throwable $exception): void
    {
        $this->mensagem->markAsError('Job falhou: ' . $exception->getMessage());

        Log::error('Job de mensagem agendada falhou definitivamente', [
            'mensagem_id' => $this->mensagem->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
