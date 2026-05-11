<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para controle de acesso a cadastro de pessoas
 * 
 * Regras de autorização:
 * - Secretaria/admin pode gerir cadastros principais
 * - Usuário comum não pode editar dados permanentes diretamente
 * - Membro/congregado só pode visualizar o que for permitido
 * - Dados familiares e de menores exigem cuidado especial
 * - Crianças menores de 11 anos não têm usuário próprio
 * - De 11 a antes de 14 são Júniores/Resgatados com supervisão
 * - De 14 a antes de 18 são Jovens/Resgatados
 * - Membro é somente quem é batizado
 */
class PersonPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode ver a lista de pessoas
     * Secretaria pode ver todos, outros podem ter acesso limitado
     */
    public function viewAny(User $user): bool
    {
        // Super-admin e Secretaria podem ver todos
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Outros perfis podem precisar de permissão específica
        return $user->hasPermission('people.view');
    }

    /**
     * Determina se o usuário pode ver uma pessoa específica
     */
    public function view(User $user, Person $person): bool
    {
        // Super-admin e Secretaria podem ver qualquer pessoa
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Usuário pode ver seus próprios dados
        if ($user->person_id === $person->id) {
            return true;
        }

        // Verificar se está na mesma família (regra futura)
        // Por enquanto, exige permissão explícita
        return $user->hasPermission('people.view');
    }

    /**
     * Determina se o usuário pode criar novas pessoas
     * Geralmente restrito a Secretaria
     */
    public function create(User $user): bool
    {
        // Apenas Secretaria pode criar cadastros
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode atualizar uma pessoa
     * Dados permanentes exigem cuidado
     */
    public function update(User $user, Person $person): bool
    {
        // Super-admin e Secretaria podem editar qualquer pessoa
        if ($user->isSuperAdmin() || $user->hasAccessProfile('secretaria')) {
            return true;
        }

        // Usuário pode editar seus próprios dados básicos
        // (mas não dados sensíveis como status, batismo, etc.)
        if ($user->person_id === $person->id) {
            return $user->hasPermission('people.update_own');
        }

        return false;
    }

    /**
     * Determina se o usuário pode deletar uma pessoa
     * Soft delete, mas ainda assim restrito
     */
    public function delete(User $user, Person $person): bool
    {
        // Apenas Secretaria pode deletar
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode restaurar uma pessoa deletada
     */
    public function restore(User $user, Person $person): bool
    {
        // Apenas Secretaria pode restaurar
        return $user->isSuperAdmin() || $user->hasAccessProfile('secretaria');
    }

    /**
     * Determina se o usuário pode deletar permanentemente uma pessoa
     * Force delete é extremamente restrito
     */
    public function forceDelete(User $user, Person $person): bool
    {
        // Apenas Super-admin pode force delete
        return $user->isSuperAdmin();
    }
}
