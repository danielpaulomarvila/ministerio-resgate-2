<?php

namespace App\Services\MinhaCaminhada;

use App\Models\WalkingJourney;
use App\Models\WalkingLevel;

class WalkingLevelService
{
    /**
     * Busca uma jornada ativa pelo tipo oficial usado pelo módulo.
     */
    public function getJourneyByType(string $journeyType): ?WalkingJourney
    {
        return WalkingJourney::query()
            ->where('type', $journeyType)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Retorna o maior nível ativo já alcançado pelos pontos informados.
     */
    public function getCurrentLevel(WalkingJourney $journey, int $points): ?WalkingLevel
    {
        return WalkingLevel::query()
            ->where('walking_journey_id', $journey->id)
            ->where('is_active', true)
            ->where('required_points', '<=', max(0, $points))
            ->orderByDesc('required_points')
            ->orderByDesc('level_number')
            ->first();
    }

    /**
     * Retorna o próximo nível ativo ainda não alcançado.
     */
    public function getNextLevel(WalkingJourney $journey, int $points): ?WalkingLevel
    {
        return WalkingLevel::query()
            ->where('walking_journey_id', $journey->id)
            ->where('is_active', true)
            ->where('required_points', '>', max(0, $points))
            ->orderBy('required_points')
            ->orderBy('level_number')
            ->first();
    }

    /**
     * Calcula o progresso entre o nível atual e o próximo nível sem alterar dados.
     */
    public function getLevelProgress(WalkingJourney $journey, int $points): array
    {
        $safePoints = max(0, $points);
        $currentLevel = $this->getCurrentLevel($journey, $safePoints);
        $nextLevel = $this->getNextLevel($journey, $safePoints);

        if (!$nextLevel) {
            return [
                'current_level' => $this->formatLevel($currentLevel),
                'next_level' => null,
                'points' => $safePoints,
                'points_to_next_level' => 0,
                'progress_percentage' => 100,
            ];
        }

        $currentRequiredPoints = $currentLevel?->required_points ?? 0;
        $distanceToNextLevel = max(1, $nextLevel->required_points - $currentRequiredPoints);
        $pointsInsideCurrentRange = max(0, $safePoints - $currentRequiredPoints);
        $percentage = (int) round(($pointsInsideCurrentRange / $distanceToNextLevel) * 100);

        return [
            'current_level' => $this->formatLevel($currentLevel),
            'next_level' => $this->formatLevel($nextLevel),
            'points' => $safePoints,
            'points_to_next_level' => max(0, $nextLevel->required_points - $safePoints),
            'progress_percentage' => min(100, max(0, $percentage)),
        ];
    }

    private function formatLevel(?WalkingLevel $level): ?array
    {
        if (!$level) {
            return null;
        }

        return [
            'id' => $level->id,
            'number' => $level->level_number,
            'name' => $level->name,
            'description' => $level->description,
            'required_points' => $level->required_points,
        ];
    }
}
