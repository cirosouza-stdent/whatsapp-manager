<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para logs de mensagens do WhatsApp.
 *
 * @property int $id
 * @property int $instancia_id
 * @property string $tipo
 * @property string $telefone_destino
 * @property string $mensagem
 * @property array|null $payload
 * @property string $status
 * @property string|null $message_id
 * @property string|null $erro_detalhes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class LogDeMensagem extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao model.
     */
    protected $table = 'logs_de_mensagens';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'instancia_id',
        'tipo',
        'telefone_destino',
        'mensagem',
        'payload',
        'status',
        'message_id',
        'erro_detalhes',
    ];

    /**
     * Casts dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Tipos de mensagem.
     */
    public const TIPO_ENVIADA = 'enviada';
    public const TIPO_RECEBIDA = 'recebida';
    public const TIPO_WEBHOOK = 'webhook';
    public const TIPO_ERRO = 'erro';

    /**
     * Status de mensagem.
     */
    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_ENVIADA = 'enviada';
    public const STATUS_ENTREGUE = 'entregue';
    public const STATUS_LIDA = 'lida';
    public const STATUS_ERRO = 'erro';

    /**
     * Relacionamento com instância.
     */
    public function instancia(): BelongsTo
    {
        return $this->belongsTo(Instancia::class);
    }

    /**
     * Scope para filtrar por tipo.
     */
    public function scopeByTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para mensagens enviadas.
     */
    public function scopeEnviadas($query)
    {
        return $query->where('tipo', self::TIPO_ENVIADA);
    }

    /**
     * Scope para mensagens recebidas.
     */
    public function scopeRecebidas($query)
    {
        return $query->where('tipo', self::TIPO_RECEBIDA);
    }

    /**
     * Scope para erros.
     */
    public function scopeErros($query)
    {
        return $query->where('tipo', self::TIPO_ERRO);
    }

    /**
     * Retorna a cor do badge baseado no status.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_LIDA => 'blue',
            self::STATUS_ENTREGUE => 'green',
            self::STATUS_ENVIADA => 'indigo',
            self::STATUS_PENDENTE => 'yellow',
            self::STATUS_ERRO => 'red',
            default => 'gray',
        };
    }
}
