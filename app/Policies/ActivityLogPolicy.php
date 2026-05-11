<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a logs de atividade
 * 
 * Regras de autorização:
 * - Logs de atividade são para auditoria
 * - Apenas Secretaria/Super-admin pode ver logs
 * - Logs não devem ser editados ou deletados
 */
class ActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de logs
     * Apenas Secretaria pode ver logs
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um log específico
     */
    public function view(User $user, ActivityLog $log): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Logs não devem ser criados manualmente
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Logs não devem ser editados
     */
    public function update(User $user, ActivityLog $log): bool
    {
        return false;
    }

    /**
     * Logs não devem ser deletados (exceto por Super-admin em casos extremos)
     */
    public function delete(User $user, ActivityLog $log): bool
    {
        return $user->isSuperAdmin();
    }
}
