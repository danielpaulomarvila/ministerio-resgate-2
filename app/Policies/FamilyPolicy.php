<?php

namespace App\Policies;

use App\Models\Family;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a famílias
 * 
 * Regras de autorização:
 * - Secretaria pode gerir todas as famílias
 * - Dados familiares são sensíveis
 * - Membros da família podem visualizar dados da própria família (regra futura)
 */
class FamilyPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de famílias
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver uma família específica
     */
    public function view(User $user, Family $family): bool
    {
        // Super-admin e Secretaria podem ver qualquer família
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Verificar se o usuário é membro da família (regra futura)
        // Por enquanto, exige permissão explícita
        return $user->hasPermission('families.view');
    }

    /**
     * Determina se o usuário pode criar novas famílias
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar uma família
     */
    public function update(User $user, Family $family): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar uma família
     */
    public function delete(User $user, Family $family): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode restaurar uma família deletada
     */
    public function restore(User $user, Family $family): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar permanentemente uma família
     */
    public function forceDelete(User $user, Family $family): bool
    {
        return $user->isSuperAdmin();
    }
}
