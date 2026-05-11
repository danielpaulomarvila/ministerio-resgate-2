<?php

namespace App\Policies;

use App\Models\AccessProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a perfis de acesso
 * 
 * Regras de autorização:
 * - Perfis de acesso definem permissões
 * - Apenas Super-admin pode gerir perfis de acesso
 * - Alterações em perfis afetam todo o sistema
 */
class AccessProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de perfis de acesso
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um perfil de acesso específico
     */
    public function view(User $user, AccessProfile $profile): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode criar perfis de acesso
     * Apenas Super-admin pode criar perfis
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determina se o usuário pode atualizar um perfil de acesso
     * Apenas Super-admin pode editar perfis
     */
    public function update(User $user, AccessProfile $profile): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determina se o usuário pode deletar um perfil de acesso
     * Apenas Super-admin pode deletar perfis
     */
    public function delete(User $user, AccessProfile $profile): bool
    {
        // Não pode deletar perfis críticos
        if (in_array($profile->slug, ['super-admin', 'secretaria', 'membro'])) {
            return false;
        }

        return $user->isSuperAdmin();
    }
}
