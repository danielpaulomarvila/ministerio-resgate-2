<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a usuários do sistema
 * 
 * Regras de autorização:
 * - Acesso ao sistema é sensível e requer controle rigoroso
 * - Apenas Secretaria pode gerenciar usuários
 * - Super-admin tem acesso total
 * - Usuários podem gerenciar seu próprio perfil básico
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de usuários
     * Apenas Secretaria pode ver todos os usuários
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um usuário específico
     */
    public function view(User $user, User $model): bool
    {
        // Super-admin e Secretaria podem ver qualquer usuário
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Usuário pode ver seu próprio perfil
        return $user->id === $model->id;
    }

    /**
     * Determina se o usuário pode criar novos usuários
     * Apenas Secretaria pode criar usuários
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar um usuário
     */
    public function update(User $user, User $model): bool
    {
        // Super-admin pode editar qualquer usuário
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Secretaria pode editar usuários (exceto super-admin)
        if ($user->hasAccessProfile('secretaria')) {
            // Não pode editar outro super-admin
            if ($model->isSuperAdmin()) {
                return false;
            }
            return true;
        }

        // Usuário pode editar seu próprio perfil básico
        if ($user->id === $model->id) {
            return $user->hasPermission('users.update_own');
        }

        return false;
    }

    /**
     * Determina se o usuário pode deletar um usuário
     * Soft delete, extremamente restrito
     */
    public function delete(User $user, User $model): bool
    {
        // Não pode deletar a si mesmo
        if ($user->id === $model->id) {
            return false;
        }

        // Super-admin pode deletar qualquer usuário (exceto a si mesmo)
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Secretaria pode deletar usuários comuns
        if ($user->hasAccessProfile('secretaria')) {
            // Não pode deletar outro super-admin
            if ($model->isSuperAdmin()) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Determina se o usuário pode restaurar um usuário deletado
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar permanentemente um usuário
     * Force delete é extremamente restrito
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Apenas Super-admin pode force delete
        if (!$user->isSuperAdmin()) {
            return false;
        }

        // Não pode force delete a si mesmo
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }
}
