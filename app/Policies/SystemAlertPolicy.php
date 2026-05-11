<?php

namespace App\Policies;

use App\Models\SystemAlert;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a alertas do sistema
 * 
 * Regras de autorização:
 * - Secretaria pode ver e gerir alertas
 * - Alertas são internos para a Secretaria
 * - Outros usuários não devem ver alertas do sistema
 */
class SystemAlertPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de alertas
     * Apenas Secretaria pode ver alertas
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um alerta específico
     */
    public function view(User $user, SystemAlert $alert): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode criar alertas
     * Geralmente criados automaticamente pelo sistema
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar um alerta
     */
    public function update(User $user, SystemAlert $alert): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode resolver um alerta
     * Secretaria pode resolver alertas
     */
    public function resolve(User $user, SystemAlert $alert): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar um alerta
     */
    public function delete(User $user, SystemAlert $alert): bool
    {
        return $user->isSuperAdmin();
    }
}
