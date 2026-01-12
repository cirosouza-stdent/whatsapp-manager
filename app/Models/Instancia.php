<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Model para gerenciamento de instâncias de WhatsApp.
 *
 * @property int $id
 * @property int $user_id
 * @property string $nome
 * @property string|null $telefone
 * @property string $token
 * @property string $status
 * @property string|null $qr_code
 * @property \Carbon\Carbon|null $last_connected_at
 * @property array|null $configuracoes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Instancia extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nome da tabela associada ao model.
     */
    protected $table = 'instancias';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nome',
        'telefone',
        'token',
        'status',
        'qr_code',
        'last_connected_at',
        'configuracoes',
    ];

    /**
     * Atributos que devem ser ocultados na serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
    ];

    /**
     * Casts dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_connected_at' => 'datetime',
            'configuracoes' => 'array',
        ];
    }

    /**
     * Status disponíveis para instância.
     */
    public const STATUS_ONLINE = 'online';
    public const STATUS_OFFLINE = 'offline';
    public const STATUS_CONNECTING = 'connecting';
    public const STATUS_QR_PENDING = 'qr_pending';

    /**
     * Boot do model para gerar token automático.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Instancia $instancia) {
            if (empty($instancia->token)) {
                $instancia->token = self::generateUniqueToken();
            }
        });
    }

    /**
     * Gera um token único para a instância.
     */
    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::uuid()->toString();
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Relacionamento com usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com logs de mensagens.
     */
    public function logsDeMensagens(): HasMany
    {
        return $this->hasMany(LogDeMensagem::class, 'instancia_id');
    }

    /**
     * Relacionamento com mensagens agendadas.
     */
    public function mensagensAgendadas(): HasMany
    {
        return $this->hasMany(MensagemAgendada::class, 'instancia_id');
    }

    /**
     * Relacionamento com webhooks.
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class, 'instancia_id');
    }

    /**
     * Verifica se a instância está online.
     */
    public function isOnline(): bool
    {
        return $this->status === self::STATUS_ONLINE;
    }

    /**
     * Verifica se a instância está aguardando QR Code.
     */
    public function isAwaitingQrCode(): bool
    {
        return $this->status === self::STATUS_QR_PENDING;
    }

    /**
     * Atualiza o status da instância.
     */
    public function updateStatus(string $status): bool
    {
        $this->status = $status;

        if ($status === self::STATUS_ONLINE) {
            $this->last_connected_at = now();
        }

        return $this->save();
    }

    /**
     * Scope para filtrar por status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar instâncias online.
     */
    public function scopeOnline($query)
    {
        return $query->where('status', self::STATUS_ONLINE);
    }

    /**
     * Retorna a cor do badge baseado no status.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ONLINE => 'green',
            self::STATUS_CONNECTING => 'yellow',
            self::STATUS_QR_PENDING => 'blue',
            default => 'red',
        };
    }

    /**
     * Retorna o texto do status formatado.
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ONLINE => 'Online',
            self::STATUS_OFFLINE => 'Offline',
            self::STATUS_CONNECTING => 'Conectando...',
            self::STATUS_QR_PENDING => 'Aguardando QR Code',
            default => 'Desconhecido',
        };
    }
}
