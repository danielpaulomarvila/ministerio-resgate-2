<?php

namespace App\Services\MinhaCaminhada;

use App\Models\Person;
use App\Models\User;
use App\Models\WalkingHighlight;
use App\Models\WalkingJourney;
use App\Models\WalkingPointLog;
use Illuminate\Database\Eloquent\Builder;

class WalkingRecognitionReadService
{
    private const MAX_ITEMS = 12;
    private const MAX_MONTHLY_ITEMS = 6;

    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
        private readonly WalkingLevelService $levelService,
    ) {
    }

    public function getRecognition(User $viewer, string $variant = 'ranking'): array
    {
        $person = $viewer->person;
        $canSeeYouthJourney = $this->authorizationService->userCanViewOwnYouthJourney($viewer);

        return [
            'authorized' => $person !== null,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'variant' => $variant,
            'person' => $person ? [
                'id' => $person->id,
                'name' => $person->preferred_name ?: $person->full_name,
            ] : null,
            'canSeeYouthJourney' => $canSeeYouthJourney,
            'general' => $this->formatJourneyRecognition($viewer, $person, 'general', true),
            'youth' => $canSeeYouthJourney
                ? $this->formatJourneyRecognition($viewer, $person, 'youth', true)
                : $this->emptyJourneyRecognition('youth', false, 'Reconhecimentos jovens disponíveis somente para jovens/resgatados autorizados.'),
            'pastoralNotice' => [
                'title' => 'Reconhecimento saudável',
                'text' => 'Este espaço reconhece constância, serviço e participação de forma saudável. Ele não mede espiritualidade, valor pessoal ou intimidade com Deus.',
                'approvalText' => 'Os reconhecimentos dependem de critérios, validação e aprovação da igreja.',
            ],
            'emptyStates' => $this->emptyStates(),
        ];
    }

    private function formatJourneyRecognition(User $viewer, ?Person $viewerPerson, string $journeyType, bool $authorized): array
    {
        if (!$authorized) {
            return $this->emptyJourneyRecognition($journeyType, false);
        }

        $journey = $this->levelService->getJourneyByType($journeyType);

        if (!$journey) {
            return $this->emptyJourneyRecognition($journeyType, true, 'Jornada indisponível no momento.');
        }

        $items = $this->approvedHighlightsQuery($journey, $journeyType)
            ->limit(self::MAX_ITEMS)
            ->get()
            ->map(fn (WalkingHighlight $highlight) => $this->formatHighlight($highlight, $viewerPerson, $journey, $journeyType))
            ->values()
            ->all();

        $monthlyHighlights = $this->approvedHighlightsQuery($journey, $journeyType)
            ->where('period_type', 'monthly')
            ->limit(self::MAX_MONTHLY_ITEMS)
            ->get()
            ->map(fn (WalkingHighlight $highlight) => $this->formatHighlight($highlight, $viewerPerson, $journey, $journeyType))
            ->values()
            ->all();

        return [
            'authorized' => true,
            'journeyType' => $journeyType,
            'title' => $journeyType === 'youth' ? 'Reconhecimentos dos Resgatados' : 'Reconhecimentos da caminhada geral',
            'items' => $items,
            'monthlyHighlights' => $monthlyHighlights,
            'summary' => [
                'approvedHighlightsCount' => count($items),
                'monthlyHighlightsCount' => count($monthlyHighlights),
                'hasHighlights' => count($items) > 0,
                'hasMonthlyHighlights' => count($monthlyHighlights) > 0,
                'journeyLabel' => $journeyType === 'youth' ? 'Jornada jovem' : 'Jornada geral',
            ],
            'emptyState' => count($items) > 0 ? null : $this->emptyStates()['withoutHighlights'],
        ];
    }

    private function approvedHighlightsQuery(WalkingJourney $journey, string $journeyType): Builder
    {
        return WalkingHighlight::query()
            ->with('person')
            ->where('is_visible', true)
            ->whereNotNull('approved_at')
            ->where('walking_journey_id', $journey->id)
            ->where('type', $journeyType)
            ->whereNotIn('type', ['intercession'])
            ->whereNotIn('category', ['intercession', 'pastoral', 'financeiro'])
            ->where('visibility', 'public_to_user')
            ->orderByDesc('period_end')
            ->orderByDesc('approved_at')
            ->orderByDesc('id');
    }

    private function formatHighlight(WalkingHighlight $highlight, ?Person $viewerPerson, WalkingJourney $journey, string $journeyType): array
    {
        $person = $highlight->person;
        $isCurrentUser = $viewerPerson && $person && $viewerPerson->id === $person->id;
        $points = $person ? $this->approvedPointsForPerson($person, $journey) : 0;
        $currentLevel = $this->levelService->getCurrentLevel($journey, $points);

        return [
            'id' => $highlight->id,
            'displayName' => $this->safeDisplayName($person, $isCurrentUser),
            'initials' => $this->initials($person?->preferred_name ?: $person?->full_name),
            'position' => null,
            'points' => $points,
            'levelName' => $currentLevel?->name,
            'highlightType' => $highlight->type,
            'highlightLabel' => $highlight->title,
            'description' => $highlight->description,
            'category' => $this->formatCategory($highlight->category),
            'periodLabel' => $this->periodLabel($highlight),
            'journeyType' => $journeyType,
            'isCurrentUser' => $isCurrentUser,
            'approvedAt' => $highlight->approved_at?->toISOString(),
        ];
    }

    private function approvedPointsForPerson(Person $person, WalkingJourney $journey): int
    {
        return (int) WalkingPointLog::query()
            ->where('person_id', $person->id)
            ->where('walking_journey_id', $journey->id)
            ->where('status', 'approved')
            ->sum('points');
    }

    private function safeDisplayName(?Person $person, bool $isCurrentUser): string
    {
        if (!$person) {
            return 'Membro da Família Resgate';
        }

        $name = $person->preferred_name ?: $person->full_name;

        if ($isCurrentUser) {
            return $name ?: 'Você';
        }

        $parts = collect(explode(' ', trim((string) $name)))->filter()->values();
        $firstName = $parts->first();

        if (!$firstName) {
            return 'Membro da Família Resgate';
        }

        $lastInitial = $parts->count() > 1 ? mb_substr($parts->last(), 0, 1).'.' : '';

        return trim($firstName.' '.$lastInitial);
    }

    private function initials(?string $name): string
    {
        $letters = collect(explode(' ', trim((string) $name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        return $letters !== '' ? $letters : 'FR';
    }

    private function formatCategory(string $category): string
    {
        return str($category)
            ->replace(['_', '-'], ' ')
            ->title()
            ->toString();
    }

    private function periodLabel(WalkingHighlight $highlight): string
    {
        $start = $highlight->period_start?->format('d/m/Y');
        $end = $highlight->period_end?->format('d/m/Y');

        if (!$start || !$end) {
            return 'Período aprovado';
        }

        return $start === $end ? $start : $start.' a '.$end;
    }

    private function emptyJourneyRecognition(string $journeyType, bool $authorized = true, ?string $message = null): array
    {
        return [
            'authorized' => $authorized,
            'journeyType' => $journeyType,
            'title' => $journeyType === 'youth' ? 'Reconhecimentos dos Resgatados' : 'Reconhecimentos da caminhada geral',
            'items' => [],
            'monthlyHighlights' => [],
            'summary' => [
                'approvedHighlightsCount' => 0,
                'monthlyHighlightsCount' => 0,
                'hasHighlights' => false,
                'hasMonthlyHighlights' => false,
                'journeyLabel' => $journeyType === 'youth' ? 'Jornada jovem' : 'Jornada geral',
            ],
            'emptyState' => [
                'title' => $authorized ? 'Nenhum destaque aprovado ainda.' : 'Reconhecimentos indisponíveis para este perfil.',
                'text' => $message ?: ($authorized
                    ? 'Quando houver reconhecimentos validados pela liderança, eles aparecerão aqui.'
                    : 'Este trilho depende de autorização e cuidado pastoral.'),
            ],
        ];
    }

    private function emptyStates(): array
    {
        return [
            'withoutPerson' => [
                'title' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                'text' => 'Você pode consultar orientações gerais. Reconhecimentos pessoais aparecerão após o vínculo cadastral.',
            ],
            'withoutHighlights' => [
                'title' => 'Nenhum destaque aprovado ainda.',
                'text' => 'Quando houver reconhecimentos validados pela liderança, eles aparecerão aqui.',
            ],
            'unauthorizedYouth' => [
                'title' => 'Reconhecimentos jovens indisponíveis para este perfil.',
                'text' => 'Destaques dos Resgatados aparecem somente para jovens/resgatados autorizados.',
            ],
        ];
    }
}
