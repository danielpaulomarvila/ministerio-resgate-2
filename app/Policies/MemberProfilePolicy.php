<?php

namespace App\Policies;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a perfis de membro
 * 
 * Regras de autorização:
 * - Secretaria pode gerir perfis de membro
 * - Perfis de membro são para pessoas batizadas
 * - Dados de membresia são sensíveis
 */
class MemberProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de perfis de membro
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um perfil de membro específico
     */
    public function view(User $user, MemberProfile $profile): bool
    {
        // Super-admin e Secretaria podem ver qualquer perfil
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Usuário pode ver seu próprio perfil de membro
        if ($user->person_id === $profile->person_id) {
            return true;
        }

        return false;
    }

    /**
     * Determina se o usuário pode criar perfis de membro
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar um perfil de membro
     */
    public function update(User $user, MemberProfile $profile): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar um perfil de membro
     */
    public function delete(User $user, MemberProfile $profile): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }
}
