<?php

namespace App\Policies;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a membros de família (tabela pivot)
 * 
 * Regras de autorização:
 * - Secretaria pode gerir vínculos familiares
 * - Vínculos familiares são sensíveis
 * - Alterações requerem cuidado
 */
class FamilyMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de membros de família
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um vínculo de membro específico
     */
    public function view(User $user, FamilyMember $familyMember): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode criar vínculos de família
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar um vínculo de família
     */
    public function update(User $user, FamilyMember $familyMember): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar um vínculo de família
     */
    public function delete(User $user, FamilyMember $familyMember): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }
}
