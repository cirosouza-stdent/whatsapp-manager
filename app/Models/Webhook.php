<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para webhooks recebidos do WhatsApp.
 *
 * @property int $id
 * @property int $instancia_id
 * @property string $evento
 * @property array $payload
 * @property string $status
 * @property string|null $resposta
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Webhook extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao model.
     */
    protected $table = 'webhooks';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'instancia_id',
        'evento',
        'payload',
        'status',
        'resposta',
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
     * Eventos de webhook.
     */
    public const EVENTO_MESSAGE = 'message';
    public const EVENTO_STATUS = 'status';
    public const EVENTO_CONNECTION = 'connection';
    public const EVENTO_QR_CODE = 'qrcode';

    /**
     * Status de processamento.
     */
    public const STATUS_RECEBIDO = 'recebido';
    public const STATUS_PROCESSADO = 'processado';
    public const STATUS_ERRO = 'erro';

    /**
     * Relacionamento com instância.
     */
    public function instancia(): BelongsTo
    {
        return $this->belongsTo(Instancia::class);
    }

    /**
     * Scope para filtrar por evento.
     */
    public function scopeByEvento($query, string $evento)
    {
        return $query->where('evento', $evento);
    }

    /**
     * Scope para webhooks não processados.
     */
    public function scopeNaoProcessados($query)
    {
        return $query->where('status', self::STATUS_RECEBIDO);
    }

    /**
     * Marca como processado.
     */
    public function markAsProcessed(?string $resposta = null): bool
    {
        $this->status = self::STATUS_PROCESSADO;
        $this->resposta = $resposta;

        return $this->save();
    }

    /**
     * Marca como erro.
     */
    public function markAsError(string $erro): bool
    {
        $this->status = self::STATUS_ERRO;
        $this->resposta = $erro;

        return $this->save();
    }
}
