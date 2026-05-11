<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a departamentos
 * 
 * Regras de autorização:
 * - Secretaria pode gerir departamentos
 * - Líderes de departamento podem gerir pessoas de seu departamento (regra futura)
 * - Usa sistema de permissões da Etapa 8
 * 
 * IMPORTANTE:
 * - Liderar departamento não cria permissão automaticamente sem controle
 * - Participar de departamento não cria usuário automaticamente
 */
class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de departamentos
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.view');
    }

    /**
     * Determina se o usuário pode ver um departamento específico
     */
    public function view(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.view') || $user->hasPermission('departments.manage');
    }

    /**
     * Determina se o usuário pode criar departamentos
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.create');
    }

    /**
     * Determina se o usuário pode atualizar um departamento
     */
    public function update(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.update') || $user->hasPermission('departments.manage');
    }

    /**
     * Determina se o usuário pode deletar um departamento
     * Preferência: usar status inactive em vez de delete
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.manage');
    }

    /**
     * Determina se o usuário pode restaurar um departamento deletado
     */
    public function restore(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.manage');
    }

    /**
     * Determina se o usuário pode deletar permanentemente um departamento
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determina se o usuário pode gerir pessoas de um departamento
     * (adicionar, remover, alterar função)
     */
    public function managePeople(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.manage_people');
    }

    /**
     * Determina se o usuário pode atribuir líder a um departamento
     */
    public function assignLeader(User $user, Department $department): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('departments.assign_leader');
    }
}
