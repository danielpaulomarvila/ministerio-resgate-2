<?php

namespace App\Policies;

use App\Models\GuardianShip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a responsáveis/tutoria
 * 
 * Regras de autorização:
 * - Secretaria pode gerir responsáveis
 * - Dados de tutoria são extremamente sensíveis (menores)
 * - Alterações requerem cuidado especial
 */
class GuardianshipPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de responsáveis
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode ver um vínculo de responsável específico
     */
    public function view(User $user, GuardianShip $guardianship): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode criar vínculos de responsável
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar um vínculo de responsável
     */
    public function update(User $user, GuardianShip $guardianship): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar um vínculo de responsável
     */
    public function delete(User $user, GuardianShip $guardianship): bool
    {
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }
}
