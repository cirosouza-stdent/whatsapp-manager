<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Instancia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para processar respostas automáticas.
 */
class ProcessAutoResponse implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Número de tentativas.
     */
    public int $tries = 1;

    /**
     * Timeout do job em segundos.
     */
    public int $timeout = 30;

    /**
     * Construtor do job.
     */
    public function __construct(
        public Instancia $instancia,
        public string $telefone,
        public string $mensagem
    ) {
    }

    /**
     * Executa o job.
     */
    public function handle(): void
    {
        // Verifica se a instância está online
        if (!$this->instancia->isOnline()) {
            Log::info('Auto-resposta ignorada: instância offline', [
                'instancia_id' => $this->instancia->id,
            ]);
            return;
        }

        // Carrega as configurações de auto-resposta da instância
        $config = $this->instancia->configuracoes ?? [];
        $autoResponses = $config['auto_responses'] ?? [];

        if (empty($autoResponses)) {
            Log::debug('Nenhuma auto-resposta configurada', [
                'instancia_id' => $this->instancia->id,
            ]);
            return;
        }

        // Procura uma resposta que combine com a mensagem
        $resposta = $this->findMatchingResponse($autoResponses);

        if ($resposta) {
            $this->sendAutoResponse($resposta);
        }
    }

    /**
     * Encontra uma resposta automática que combine com a mensagem.
     */
    protected function findMatchingResponse(array $autoResponses): ?string
    {
        $mensagemLower = mb_strtolower($this->mensagem);

        foreach ($autoResponses as $trigger => $response) {
            // Suporte a expressões regulares
            if (str_starts_with($trigger, '/') && str_ends_with($trigger, '/')) {
                if (preg_match($trigger . 'i', $this->mensagem)) {
                    return $response;
                }
            }

            // Correspondência parcial (contém)
            if (str_contains($mensagemLower, mb_strtolower($trigger))) {
                return $response;
            }
        }

        return null;
    }

    /**
     * Envia a resposta automática.
     */
    protected function sendAutoResponse(string $resposta): void
    {
        try {
            $whatsAppService = app(\App\Services\WhatsAppService::class);

            $result = $whatsAppService->sendTextMessage(
                $this->instancia,
                $this->telefone,
                $resposta
            );

            if ($result['success']) {
                Log::info('Auto-resposta enviada', [
                    'instancia_id' => $this->instancia->id,
                    'telefone' => $this->telefone,
                    'message_id' => $result['message_id'] ?? null,
                ]);
            } else {
                Log::warning('Falha ao enviar auto-resposta', [
                    'instancia_id' => $this->instancia->id,
                    'telefone' => $this->telefone,
                    'error' => $result['error'] ?? 'Erro desconhecido',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar auto-resposta', [
                'instancia_id' => $this->instancia->id,
                'telefone' => $this->telefone,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Tratamento de falha do job.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de auto-resposta falhou', [
            'instancia_id' => $this->instancia->id,
            'telefone' => $this->telefone,
            'error' => $exception->getMessage(),
        ]);
    }
}
