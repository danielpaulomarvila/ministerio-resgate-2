<?php

namespace App\Services\Secretaria;

use App\Models\Person;

/**
 * Service para verificar elegibilidade de acesso ao sistema
 * 
 * Valida se uma pessoa pode ter usuário baseado em:
 * - Idade (menores de 11 anos não podem)
 * - Data de nascimento (obrigatória)
 * - Responsável ativo autorizado (para Júniores)
 */
class UserAccessEligibilityService
{
    /**
     * Verifica se uma pessoa é elegível para ter acesso ao sistema
     *
     * @param Person $person A pessoa a ser verificada
     * @return array Array com informações sobre elegibilidade
     */
    public function check(Person $person): array
    {
        $age = $person->age();
        $ageGroup = $person->ageGroup();

        // Verificar se já possui usuário (ativo ou suspenso)
        if ($person->user !== null) {
            $isActive = $person->user->isActive();
            $reason = $isActive
                ? 'Esta pessoa já possui usuário ativo.'
                : 'Esta pessoa já possui usuário suspenso. Reative o acesso existente em vez de criar outro.';

            return [
                'allowed' => false,
                'reason' => $reason,
                'age' => $age,
                'age_group' => $ageGroup,
                'requires_guardian' => false,
                'has_authorized_guardian' => false,
            ];
        }

        // Verificar se tem data de nascimento
        if (!$person->birth_date) {
            return [
                'allowed' => false,
                'reason' => 'Informe a data de nascimento antes de criar acesso, para validar as regras de idade.',
                'age' => null,
                'age_group' => null,
                'requires_guardian' => false,
                'has_authorized_guardian' => false,
            ];
        }

        // Verificar se é menor de 11 anos
        if ($person->isUnder11YearsOld()) {
            return [
                'allowed' => false,
                'reason' => 'Crianças menores de 11 anos não podem ter usuário próprio. O acesso deve ser feito pelos responsáveis.',
                'age' => $age,
                'age_group' => $ageGroup,
                'requires_guardian' => false,
                'has_authorized_guardian' => false,
            ];
        }

        // Verificar se é Júnior (11-13 anos)
        if ($person->isJunior()) {
            $hasAuthorizedGuardian = $person->hasActiveGuardianAuthorizedForLogin();

            if (!$hasAuthorizedGuardian) {
                return [
                    'allowed' => false,
                    'reason' => 'Júniores podem ter usuário supervisionado, mas precisam de responsável ativo autorizado.',
                    'age' => $age,
                    'age_group' => $ageGroup,
                    'requires_guardian' => true,
                    'has_authorized_guardian' => false,
                ];
            }

            return [
                'allowed' => true,
                'reason' => 'Permitido com supervisão: usuário será vinculado a responsável.',
                'age' => $age,
                'age_group' => $ageGroup,
                'requires_guardian' => true,
                'has_authorized_guardian' => true,
            ];
        }

        // Verificar se é Jovem (14-17 anos)
        if ($person->isYoung()) {
            return [
                'allowed' => true,
                'reason' => 'Permitido: jovem pode ter usuário. Membro somente se batizado.',
                'age' => $age,
                'age_group' => $ageGroup,
                'requires_guardian' => false,
                'has_authorized_guardian' => true,
            ];
        }

        // Adulto (18 anos ou mais)
        return [
            'allowed' => true,
            'reason' => 'Permitido: adulto pode ter usuário. Membro somente se batizado.',
            'age' => $age,
            'age_group' => $ageGroup,
            'requires_guardian' => false,
            'has_authorized_guardian' => true,
        ];
    }

    /**
     * Verifica se uma pessoa já possui usuário ativo
     * 
     * @param Person $person A pessoa a ser verificada
     * @return bool True se já possui usuário ativo
     */
    public function hasActiveUser(Person $person): bool
    {
        return $person->user !== null && $person->user->isActive();
    }
}
