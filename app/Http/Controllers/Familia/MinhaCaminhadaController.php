<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Services\MinhaCaminhada\WalkingAchievementReadService;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;
use App\Services\MinhaCaminhada\WalkingDashboardReadService;
use App\Services\MinhaCaminhada\WalkingLevelService;
use App\Services\MinhaCaminhada\WalkingProgressService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MinhaCaminhadaController extends Controller
{
    public function __construct(
        private readonly WalkingDashboardReadService $dashboardReadService,
        private readonly WalkingAuthorizationService $authorizationService,
    ) {
    }

    public function index(Request $request): Response
    {
        $user = $request->user();
        $generalDashboard = $this->dashboardReadService->getOwnDashboard($user);
        $canSeeYouthJourney = $this->authorizationService->userCanViewOwnYouthJourney($user);
        $youthDashboard = $canSeeYouthJourney
            ? $this->dashboardReadService->getOwnDashboard($user, 'youth')
            : null;

        return Inertia::render('FamiliaResgate/MinhaCaminhada', [
            'walkingDashboard' => [
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'viewerContext' => [
                    'profileType' => 'member',
                    'canSeeGeneralJourney' => $this->dashboardHasAuthorizedProgress($generalDashboard),
                    'canSeeYouthJourney' => $canSeeYouthJourney && $this->dashboardHasAuthorizedProgress($youthDashboard ?? []),
                    'canSeeYouthTeams' => false,
                    'youthMember' => $canSeeYouthJourney,
                    'youthLeader' => false,
                    'isGuardian' => false,
                    'isAdmin' => $user?->isSuperAdmin() ?? false,
                    'isPastoralLeadership' => $this->authorizationService->userCanViewPastoralWalkingData($user),
                ],
                'general' => $this->formatJourneyDashboard($generalDashboard, 'general'),
                'youth' => $youthDashboard ? $this->formatJourneyDashboard($youthDashboard, 'youth') : null,
            ],
        ]);
    }

    public function achievements(Request $request, WalkingAchievementReadService $achievementReadService): Response
    {
        $user = $request->user();
        $person = $user?->person;

        if (!$person) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaConquistas', [
                'walkingAchievements' => $this->emptyAchievementsPayload(
                    'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                    false
                ),
            ]);
        }

        $canSeeYouthJourney = $this->authorizationService->userCanViewOwnYouthJourney($user);

        return Inertia::render('FamiliaResgate/MinhaCaminhadaConquistas', [
            'walkingAchievements' => [
                'authorized' => true,
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'person' => [
                    'id' => $person->id,
                    'name' => $person->preferred_name ?: $person->full_name,
                ],
                'canSeeYouthJourney' => $canSeeYouthJourney,
                'general' => $this->formatAchievementsJourney(
                    $achievementReadService->getVisibleCatalog($user),
                    $achievementReadService->getOwnAchievements($user)
                ),
                'youth' => $canSeeYouthJourney
                    ? $this->formatAchievementsJourney(
                        $achievementReadService->getVisibleCatalog($user, 'youth'),
                        $achievementReadService->getOwnAchievements($user, 'youth')
                    )
                    : $this->emptyAchievementsJourney(false),
                'emptyStates' => $this->achievementsEmptyStates(),
            ],
        ]);
    }

    public function level(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService
    ): Response {
        $user = $request->user();
        $person = $user?->person;
        $defaultJourneyType = $levelService->getJourneyByType('general')?->type ?? 'general';

        if (!$person) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
                'area' => 'nivel',
                'journey' => 'auto',
                'walkingLevel' => $this->emptyLevelPayload(
                    'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                    false,
                    $defaultJourneyType
                ),
            ]);
        }

        $canSeeYouthJourney = $this->authorizationService->userCanViewOwnYouthJourney($user);

        return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
            'area' => 'nivel',
            'journey' => 'auto',
            'walkingLevel' => [
                'authorized' => true,
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'message' => null,
                'person' => [
                    'id' => $person->id,
                    'name' => $person->preferred_name ?: $person->full_name,
                ],
                'canSeeYouthJourney' => $canSeeYouthJourney,
                'defaultJourneyType' => $defaultJourneyType,
                'general' => $this->formatLevelJourney(
                    $progressService->getOwnProgress($user),
                    $progressService->getRecentApprovedLogs($user, $person)
                ),
                'youth' => $canSeeYouthJourney
                    ? $this->formatLevelJourney(
                        $progressService->getOwnProgress($user, 'youth'),
                        $progressService->getRecentApprovedLogs($user, $person, 'youth')
                    )
                    : $this->emptyLevelJourney('youth', false),
                'emptyStates' => $this->levelEmptyStates(),
            ],
        ]);
    }

    private function formatJourneyDashboard(array $dashboard, string $journeyType): array
    {
        if (!($dashboard['authorized'] ?? false)) {
            return [
                'authorized' => false,
                'type' => $journeyType,
                'message' => $dashboard['message'] ?? 'Caminhada indisponível.',
                'journey' => null,
                'progress' => null,
                'recentLogs' => [],
                'achievements' => [],
                'mentor' => null,
                'highlights' => [],
            ];
        }

        return [
            'authorized' => true,
            'type' => $journeyType,
            'person' => $dashboard['person'] ?? null,
            'journey' => $dashboard['progress']['journey'] ?? null,
            'progress' => $dashboard['progress'] ?? null,
            'recentLogs' => $dashboard['recent_logs'] ?? [],
            'achievements' => $dashboard['achievements'] ?? [],
            'mentor' => $dashboard['mentor'] ?? null,
            'highlights' => $dashboard['highlights'] ?? [],
        ];
    }

    private function emptyLevelPayload(string $message, bool $authorized, string $defaultJourneyType = 'general'): array
    {
        return [
            'authorized' => $authorized,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'message' => $message,
            'person' => null,
            'canSeeYouthJourney' => false,
            'defaultJourneyType' => $defaultJourneyType,
            'general' => $this->emptyLevelJourney('general', false),
            'youth' => $this->emptyLevelJourney('youth', false),
            'emptyStates' => $this->levelEmptyStates(),
        ];
    }

    private function emptyLevelJourney(string $journeyType, bool $authorized = true): array
    {
        return [
            'authorized' => $authorized,
            'journeyType' => $journeyType,
            'journey' => null,
            'points' => 0,
            'approvedLogsCount' => 0,
            'currentLevel' => null,
            'nextLevel' => null,
            'pointsToNextLevel' => 0,
            'progressPercent' => 0,
            'recentLogs' => [],
            'summary' => [
                'has_points' => false,
                'has_current_level' => false,
                'has_next_level' => false,
                'is_final_level' => false,
            ],
        ];
    }

    private function formatLevelJourney(array $progress, array $recentLogs): array
    {
        if (!($progress['authorized'] ?? false)) {
            return array_merge(
                $this->emptyLevelJourney($progress['journey_type'] ?? 'general', false),
                ['message' => $progress['message'] ?? 'Caminhada indisponível.']
            );
        }

        $levelProgress = $progress['level_progress'] ?? [];
        $points = (int) ($progress['total_points'] ?? 0);
        $currentLevel = $this->formatLevelData($levelProgress['current_level'] ?? null);
        $nextLevel = $this->formatLevelData($levelProgress['next_level'] ?? null);

        return [
            'authorized' => true,
            'journeyType' => $progress['journey']['type'] ?? $progress['journey_type'] ?? 'general',
            'journey' => $progress['journey'] ?? null,
            'points' => $points,
            'approvedLogsCount' => (int) ($progress['approved_logs_count'] ?? 0),
            'currentLevel' => $currentLevel,
            'nextLevel' => $nextLevel,
            'pointsToNextLevel' => (int) ($levelProgress['points_to_next_level'] ?? 0),
            'progressPercent' => (int) ($levelProgress['progress_percentage'] ?? 0),
            'recentLogs' => $recentLogs,
            'summary' => [
                'has_points' => $points > 0,
                'has_current_level' => $currentLevel !== null,
                'has_next_level' => $nextLevel !== null,
                'is_final_level' => $nextLevel === null && $currentLevel !== null,
            ],
        ];
    }

    private function formatLevelData(?array $level): ?array
    {
        if (!$level) {
            return null;
        }

        return [
            'id' => $level['id'] ?? null,
            'number' => (int) ($level['number'] ?? 0),
            'name' => $level['name'] ?? 'Nível',
            'description' => $level['description'] ?? null,
            'requiredPoints' => (int) ($level['required_points'] ?? 0),
        ];
    }

    private function levelEmptyStates(): array
    {
        return [
            'withoutPersonTitle' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
            'withoutPersonText' => 'Assim que o cadastro for vinculado, seu nível real aparecerá aqui.',
            'withoutPointsTitle' => 'Ainda não há pontos aprovados nesta caminhada.',
            'withoutPointsText' => 'Quando houver registros aprovados, seu nível começará a avançar.',
            'withoutJourneyTitle' => 'Caminhada indisponível no momento.',
            'withoutJourneyText' => 'Assim que a jornada estiver disponível, o nível real aparecerá aqui.',
        ];
    }

    private function emptyAchievementsPayload(string $message, bool $authorized): array
    {
        return [
            'authorized' => $authorized,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'message' => $message,
            'person' => null,
            'canSeeYouthJourney' => false,
            'general' => $this->emptyAchievementsJourney(false),
            'youth' => $this->emptyAchievementsJourney(false),
            'emptyStates' => $this->achievementsEmptyStates(),
        ];
    }

    private function emptyAchievementsJourney(bool $authorized = true): array
    {
        return [
            'authorized' => $authorized,
            'catalog' => [],
            'received' => [],
            'inProgress' => [],
            'locked' => [],
            'categories' => [],
            'summary' => [
                'total_catalog' => 0,
                'received_count' => 0,
                'in_progress_count' => 0,
                'locked_count' => 0,
            ],
        ];
    }

    private function formatAchievementsJourney(array $catalog, array $personAchievements): array
    {
        $received = $this->formatPersonAchievementsByStatus($personAchievements, ['received']);
        $inProgress = $this->formatPersonAchievementsByStatus($personAchievements, ['in_progress', 'pending_validation']);
        $activeAchievementIds = collect($received)
            ->merge($inProgress)
            ->pluck('achievement_id')
            ->filter()
            ->unique()
            ->values();
        $locked = collect($catalog)
            ->reject(fn (array $achievement) => $activeAchievementIds->contains($achievement['id'] ?? null))
            ->map(fn (array $achievement) => $this->formatCatalogAchievementForPage($achievement))
            ->values()
            ->all();

        return [
            'authorized' => true,
            'catalog' => array_values(array_map(fn (array $achievement) => $this->formatCatalogAchievementForPage($achievement), $catalog)),
            'received' => $received,
            'inProgress' => $inProgress,
            'locked' => $locked,
            'categories' => $this->formatAchievementCategories($catalog),
            'summary' => [
                'total_catalog' => count($catalog),
                'received_count' => count($received),
                'in_progress_count' => count($inProgress),
                'locked_count' => count($locked),
            ],
        ];
    }

    private function formatPersonAchievementsByStatus(array $personAchievements, array $statuses): array
    {
        return collect($personAchievements)
            ->filter(fn (array $personAchievement) => in_array($personAchievement['status'] ?? null, $statuses, true))
            ->map(function (array $personAchievement) {
                $achievement = $this->formatCatalogAchievementForPage($personAchievement['achievement'] ?? []);
                $progressCurrent = (int) ($personAchievement['progress_current'] ?? 0);
                $progressTarget = (int) ($personAchievement['progress_target'] ?? 0);

                return array_merge($achievement, [
                    'person_achievement_id' => $personAchievement['id'] ?? null,
                    'achievement_id' => $achievement['id'] ?? null,
                    'status' => $personAchievement['status'] ?? 'in_progress',
                    'progress_current' => $progressCurrent,
                    'progress_target' => $progressTarget,
                    'progress_percent' => $progressTarget > 0
                        ? min(100, (int) round(($progressCurrent / $progressTarget) * 100))
                        : (($personAchievement['status'] ?? null) === 'received' ? 100 : 0),
                    'awarded_at' => $personAchievement['awarded_at'] ?? null,
                ]);
            })
            ->values()
            ->all();
    }

    private function formatCatalogAchievementForPage(array $achievement): array
    {
        return [
            'id' => $achievement['id'] ?? null,
            'key' => $achievement['key'] ?? null,
            'name' => $achievement['name'] ?? 'Conquista',
            'description' => $achievement['description'] ?? 'Conquista disponível na sua caminhada.',
            'type' => $achievement['type'] ?? 'general',
            'category' => $achievement['category'] ?? 'general',
            'visibility' => $achievement['visibility'] ?? null,
            'icon' => $achievement['icon'] ?: '✦',
            'color' => $achievement['color'] ?? null,
            'requires_validation' => (bool) ($achievement['requires_validation'] ?? false),
        ];
    }

    private function formatAchievementCategories(array $catalog): array
    {
        return collect($catalog)
            ->groupBy(fn (array $achievement) => $achievement['category'] ?? 'general')
            ->map(fn ($items, string $category) => [
                'key' => $category,
                'title' => $this->formatAchievementCategoryTitle($category),
                'count' => $items->count(),
            ])
            ->values()
            ->all();
    }

    private function formatAchievementCategoryTitle(string $category): string
    {
        return str($category)
            ->replace(['_', '-'], ' ')
            ->title()
            ->toString();
    }

    private function achievementsEmptyStates(): array
    {
        return [
            'withoutPersonTitle' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
            'withoutPersonText' => 'Assim que o cadastro for vinculado, suas conquistas reais aparecerão aqui.',
            'withoutReceivedTitle' => 'Nenhuma conquista recebida ainda.',
            'withoutReceivedText' => 'Quando uma conquista real for concedida, ela aparecerá aqui.',
            'withoutProgressTitle' => 'Nenhuma conquista em progresso no momento.',
            'withoutProgressText' => 'Conquistas em andamento aparecerão quando houver registros suficientes.',
            'withoutCatalogTitle' => 'Nenhuma conquista disponível para esta jornada.',
        ];
    }

    private function dashboardHasAuthorizedProgress(array $dashboard): bool
    {
        return (bool) ($dashboard['authorized'] ?? false)
            && (bool) ($dashboard['progress']['authorized'] ?? false);
    }
}
