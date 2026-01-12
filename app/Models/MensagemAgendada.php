<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para mensagens agendadas do WhatsApp.
 *
 * @property int $id
 * @property int $instancia_id
 * @property string $telefone_destino
 * @property string $mensagem
 * @property array|null $midia
 * @property \Carbon\Carbon $agendado_para
 * @property string $status
 * @property string|null $erro_detalhes
 * @property int $tentativas
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MensagemAgendada extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao model.
     */
    protected $table = 'mensagens_agendadas';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'instancia_id',
        'telefone_destino',
        'mensagem',
        'midia',
        'agendado_para',
        'status',
        'erro_detalhes',
        'tentativas',
    ];

    /**
     * Casts dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'midia' => 'array',
            'agendado_para' => 'datetime',
        ];
    }

    /**
     * Status de agendamento.
     */
    public const STATUS_AGENDADA = 'agendada';
    public const STATUS_PROCESSANDO = 'processando';
    public const STATUS_ENVIADA = 'enviada';
    public const STATUS_ERRO = 'erro';
    public const STATUS_CANCELADA = 'cancelada';

    /**
     * Número máximo de tentativas.
     */
    public const MAX_TENTATIVAS = 3;

    /**
     * Relacionamento com instância.
     */
    public function instancia(): BelongsTo
    {
        return $this->belongsTo(Instancia::class);
    }

    /**
     * Scope para mensagens pendentes de envio.
     */
    public function scopePendentes($query)
    {
        return $query->where('status', self::STATUS_AGENDADA)
                     ->where('agendado_para', '<=', now());
    }

    /**
     * Scope para mensagens agendadas para o futuro.
     */
    public function scopeFuturas($query)
    {
        return $query->where('status', self::STATUS_AGENDADA)
                     ->where('agendado_para', '>', now());
    }

    /**
     * Verifica se pode tentar novamente.
     */
    public function canRetry(): bool
    {
        return $this->tentativas < self::MAX_TENTATIVAS
            && $this->status === self::STATUS_ERRO;
    }

    /**
     * Marca como processando.
     */
    public function markAsProcessing(): bool
    {
        $this->status = self::STATUS_PROCESSANDO;
        $this->tentativas++;

        return $this->save();
    }

    /**
     * Marca como enviada.
     */
    public function markAsSent(): bool
    {
        $this->status = self::STATUS_ENVIADA;

        return $this->save();
    }

    /**
     * Marca como erro.
     */
    public function markAsError(string $erro): bool
    {
        $this->status = self::STATUS_ERRO;
        $this->erro_detalhes = $erro;

        return $this->save();
    }

    /**
     * Cancela o agendamento.
     */
    public function cancel(): bool
    {
        $this->status = self::STATUS_CANCELADA;

        return $this->save();
    }
}
