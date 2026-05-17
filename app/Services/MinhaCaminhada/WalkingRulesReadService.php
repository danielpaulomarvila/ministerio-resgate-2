<?php

namespace App\Services\MinhaCaminhada;

use App\Models\WalkingJourney;
use App\Models\WalkingPointRule;
use Illuminate\Support\Collection;

class WalkingRulesReadService
{
    public function getActiveRulesForJourney(string $journeyType = 'general'): array
    {
        $journey = WalkingJourney::query()
            ->where('type', $journeyType)
            ->where('is_active', true)
            ->first();

        if (!$journey) {
            return [
                'authorized' => true,
                'journeyType' => $journeyType,
                'rules' => [],
                'groups' => [],
                'summary' => $this->summary(collect(), $journeyType),
            ];
        }

        $rules = WalkingPointRule::query()
            ->where('walking_journey_id', $journey->id)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(fn (WalkingPointRule $rule) => $this->formatRule($rule, $journeyType));

        return [
            'authorized' => true,
            'journeyType' => $journeyType,
            'rules' => $rules->values()->all(),
            'groups' => $this->groupRules($rules),
            'summary' => $this->summary($rules, $journeyType),
        ];
    }

    private function formatRule(WalkingPointRule $rule, string $journeyType): array
    {
        return [
            'id' => $rule->id,
            'key' => $rule->key,
            'name' => $rule->name,
            'description' => $rule->description,
            'category' => $rule->category,
            'categoryLabel' => $this->categoryLabel($rule->category),
            'points' => $rule->points,
            'validationType' => $rule->validation_type,
            'validationLabel' => $this->validationLabel($rule->validation_type),
            'validationHint' => $this->validationHint($rule->validation_type),
            'sourceOrigin' => $rule->source_origin,
            'sourceLabel' => $this->sourceLabel($rule->source_origin),
            'countsForLevel' => $rule->counts_for_level,
            'countsForHighlight' => $rule->counts_for_highlight,
            'maxPerDay' => $rule->daily_limit,
            'maxPerWeek' => $rule->weekly_limit,
            'maxPerMonth' => $rule->monthly_limit,
            'isActive' => $rule->is_active,
            'journeyType' => $journeyType,
        ];
    }

    private function groupRules(Collection $rules): array
    {
        return $rules
            ->groupBy('category')
            ->map(fn (Collection $items, string $category) => [
                'category' => $category,
                'categoryLabel' => $this->categoryLabel($category),
                'rulesCount' => $items->count(),
                'totalPossiblePoints' => $items->sum('points'),
                'rules' => $items->values()->all(),
            ])
            ->values()
            ->all();
    }

    private function summary(Collection $rules, string $journeyType): array
    {
        return [
            'journeyType' => $journeyType,
            'activeRulesCount' => $rules->count(),
            'categoriesCount' => $rules->pluck('category')->unique()->count(),
            'levelRulesCount' => $rules->where('countsForLevel', true)->count(),
            'highlightRulesCount' => $rules->where('countsForHighlight', true)->count(),
        ];
    }

    private function categoryLabel(?string $category): string
    {
        return match ($category) {
            'presence' => 'Presença',
            'bible_study' => 'Estudo bíblico',
            'bible' => 'Bíblia',
            'bible_challenge' => 'Desafio bíblico',
            'service' => 'Serviço',
            'evangelism' => 'Evangelismo',
            'devotional' => 'Devocional',
            'communion' => 'Comunhão',
            'intercession' => 'Intercessão',
            default => $category ? str($category)->replace('_', ' ')->title()->toString() : 'Sem categoria',
        };
    }

    private function validationLabel(?string $validationType): string
    {
        return match ($validationType) {
            'automatic' => 'Automática',
            'manual' => 'Manual',
            'leader_validation' => 'Validação da liderança',
            'secretary_validation' => 'Validação da secretaria',
            'pastoral_validation' => 'Validação pastoral',
            default => $validationType ? str($validationType)->replace('_', ' ')->title()->toString() : 'Não informado',
        };
    }

    private function validationHint(?string $validationType): string
    {
        return $validationType === 'automatic'
            ? 'Pode ser contabilizada automaticamente quando houver integração.'
            : 'Pode precisar de validação/aprovação.';
    }

    private function sourceLabel(?string $sourceOrigin): string
    {
        return match ($sourceOrigin) {
            'future_attendance' => 'Integração futura de presenças',
            'future_department_service' => 'Integração futura de ministérios/departamentos',
            'future_visitor_followup' => 'Integração futura de visitantes',
            'future_devotional_entry' => 'Integração futura de devocionais',
            'future_community_action' => 'Integração futura de comunhão/serviço',
            'future_intercession_safe_flow' => 'Fluxo futuro seguro de intercessão',
            'future_youth_attendance' => 'Integração futura de presença jovem',
            'future_youth_checkin' => 'Integração futura de check-in jovem',
            'future_youth_challenge' => 'Integração futura de desafio jovem',
            'future_youth_service' => 'Integração futura de serviço jovem',
            'future_youth_visitor_followup' => 'Integração futura de visitante jovem',
            default => $sourceOrigin ? str($sourceOrigin)->replace('_', ' ')->title()->toString() : 'Origem não informada',
        };
    }
}
