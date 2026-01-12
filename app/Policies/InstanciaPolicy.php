<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Instancia;
use App\Models\User;

/**
 * Policy para autorização de ações em instâncias.
 */
class InstanciaPolicy
{
    /**
     * Determina se o usuário pode visualizar qualquer instância.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina se o usuário pode visualizar a instância.
     */
    public function view(User $user, Instancia $instancia): bool
    {
        return $user->id === $instancia->user_id;
    }

    /**
     * Determina se o usuário pode criar instâncias.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina se o usuário pode atualizar a instância.
     */
    public function update(User $user, Instancia $instancia): bool
    {
        return $user->id === $instancia->user_id;
    }

    /**
     * Determina se o usuário pode deletar a instância.
     */
    public function delete(User $user, Instancia $instancia): bool
    {
        return $user->id === $instancia->user_id;
    }

    /**
     * Determina se o usuário pode restaurar a instância.
     */
    public function restore(User $user, Instancia $instancia): bool
    {
        return $user->id === $instancia->user_id;
    }

    /**
     * Determina se o usuário pode deletar permanentemente a instância.
     */
    public function forceDelete(User $user, Instancia $instancia): bool
    {
        return $user->id === $instancia->user_id;
    }
}
