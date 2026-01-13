<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome',
        'username',
        'token',
        'bot_id',
        'descricao',
        'foto_url',
        'ativo',
        'webhook_ativo',
        'webhook_url',
        'ultimo_uso',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'webhook_ativo' => 'boolean',
        'ultimo_uso' => 'datetime',
    ];

    /**
     * Relacionamento com usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se o bot está ativo.
     */
    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    /**
     * Retorna o token mascarado para exibição.
     */
    public function getTokenMascaradoAttribute(): string
    {
        if (strlen($this->token) < 20) {
            return str_repeat('*', strlen($this->token));
        }
        
        return substr($this->token, 0, 10) . '...' . substr($this->token, -5);
    }
}
