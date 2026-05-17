<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Models\WalkingJourney;
use App\Models\WalkingLevel;
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

    public function history(
        Request $request,
        WalkingProgressService $progressService,
        WalkingAuthorizationService $authorizationService
    ): Response {
        $user = $request->user();
        $person = $user?->person;

        if (!$person) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
                'area' => 'historico',
                'journey' => 'all',
                'walkingHistory' => $this->emptyHistoryPayload(
                    'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                    false
                ),
            ]);
        }

        $canSeeYouthJourney = $authorizationService->userCanViewOwnYouthJourney($user);

        return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
            'area' => 'historico',
            'journey' => 'all',
            'walkingHistory' => [
                'authorized' => true,
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'message' => null,
                'person' => [
                    'id' => $person->id,
                    'name' => $person->preferred_name ?: $person->full_name,
                ],
                'canSeeYouthJourney' => $canSeeYouthJourney,
                'general' => $this->formatHistoryJourney(
                    $progressService->getOwnProgress($user),
                    $progressService->getRecentApprovedLogs($user, $person, 'general', 20),
                    'general'
                ),
                'youth' => $canSeeYouthJourney
                    ? $this->formatHistoryJourney(
                        $progressService->getOwnProgress($user, 'youth'),
                        $progressService->getRecentApprovedLogs($user, $person, 'youth', 20),
                        'youth'
                    )
                    : $this->emptyHistoryJourney('youth', false, 'Você não tem permissão para visualizar o histórico jovem.'),
                'emptyStates' => $this->historyEmptyStates(),
            ],
        ]);
    }

    public function map(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService
    ): Response {
        return $this->renderMap($request, $progressService, $levelService, $authorizationService, 'general', 'geral');
    }

    public function generalMap(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService
    ): Response {
        return $this->renderMap($request, $progressService, $levelService, $authorizationService, 'general', 'geral');
    }

    public function youthMap(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService
    ): Response {
        return $this->renderMap($request, $progressService, $levelService, $authorizationService, 'youth', 'jovem');
    }

    public function generalLevel(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService,
        string $level
    ): Response {
        return $this->renderLevelDetail($request, $progressService, $levelService, $authorizationService, $level, 'general', 'geral');
    }

    public function youthLevel(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService,
        string $level
    ): Response {
        return $this->renderLevelDetail($request, $progressService, $levelService, $authorizationService, $level, 'youth', 'jovem');
    }

    public function legacyGeneralLevel(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService,
        string $level
    ): Response {
        return $this->renderLevelDetail($request, $progressService, $levelService, $authorizationService, $level, 'general', 'geral', true);
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

    private function emptyHistoryPayload(string $message, bool $authorized): array
    {
        return [
            'authorized' => $authorized,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'message' => $message,
            'person' => null,
            'canSeeYouthJourney' => false,
            'general' => $this->emptyHistoryJourney('general', false),
            'youth' => $this->emptyHistoryJourney('youth', false),
            'emptyStates' => $this->historyEmptyStates(),
        ];
    }

    private function emptyHistoryJourney(string $journeyType, bool $authorized = true, ?string $message = null): array
    {
        return [
            'authorized' => $authorized,
            'journeyType' => $journeyType,
            'message' => $message,
            'events' => [],
            'summary' => [
                'totalEvents' => 0,
                'totalPoints' => 0,
                'approvedLogsCount' => 0,
                'latestEventAt' => null,
                'categoriesCount' => 0,
            ],
        ];
    }

    private function formatHistoryJourney(array $progress, array $recentLogs, string $journeyType): array
    {
        if (!($progress['authorized'] ?? false)) {
            return array_merge(
                $this->emptyHistoryJourney($progress['journey_type'] ?? $journeyType, false),
                ['message' => $progress['message'] ?? 'Histórico indisponível.']
            );
        }

        $events = collect($recentLogs)
            ->map(fn (array $log) => $this->formatHistoryEvent($log, $journeyType))
            ->values()
            ->all();

        return [
            'authorized' => true,
            'journeyType' => $progress['journey']['type'] ?? $journeyType,
            'message' => null,
            'events' => $events,
            'summary' => [
                'totalEvents' => count($events),
                'totalPoints' => (int) ($progress['total_points'] ?? 0),
                'approvedLogsCount' => (int) ($progress['approved_logs_count'] ?? 0),
                'latestEventAt' => $events[0]['occurredAt'] ?? null,
                'categoriesCount' => collect($events)->pluck('category')->filter()->unique()->count(),
            ],
        ];
    }

    private function formatHistoryEvent(array $log, string $journeyType): array
    {
        $category = $log['category'] ?? 'registro';
        $createdAt = $log['created_at'] ?? null;

        return [
            'id' => $log['id'] ?? null,
            'type' => 'point_log',
            'title' => $this->historyEventTitle($category),
            'description' => $log['notes'] ?: 'Registro aprovado sem observação pública.',
            'points' => (int) ($log['points'] ?? 0),
            'status' => 'approved',
            'statusLabel' => 'Aprovado',
            'category' => $category,
            'categoryLabel' => $this->historyCategoryLabel($category),
            'journeyType' => $journeyType,
            'journeyLabel' => $journeyType === 'youth' ? 'Caminhada Jovem' : 'Caminhada Geral',
            'occurredAt' => $createdAt,
            'createdAt' => $createdAt,
            'source' => $log['source_type'] ?? null,
        ];
    }

    private function historyEventTitle(?string $category): string
    {
        return match ($category) {
            'presence' => 'Registro aprovado de presença',
            'word', 'bible', 'reading' => 'Registro aprovado de Palavra',
            'devotional' => 'Registro aprovado de devocional',
            'service' => 'Registro aprovado de serviço',
            'communion' => 'Registro aprovado de comunhão',
            'evangelism' => 'Registro aprovado de evangelismo',
            default => 'Registro aprovado na caminhada',
        };
    }

    private function historyCategoryLabel(?string $category): string
    {
        return match ($category) {
            'presence' => 'Presença',
            'word', 'bible', 'reading' => 'Palavra',
            'devotional' => 'Devocional',
            'service' => 'Serviço',
            'communion' => 'Comunhão',
            'evangelism' => 'Evangelismo',
            default => 'Registro',
        };
    }

    private function historyEmptyStates(): array
    {
        return [
            'withoutPersonTitle' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
            'withoutPersonText' => 'Assim que o cadastro for vinculado, seu histórico real aparecerá aqui.',
            'withoutEventsTitle' => 'Ainda não há registros aprovados no histórico.',
            'withoutEventsText' => 'Quando houver registros aprovados, eles aparecerão aqui com segurança.',
            'unauthorizedYouthTitle' => 'Histórico jovem indisponível para este perfil.',
            'unauthorizedYouthText' => 'A caminhada jovem aparece somente para jovens/resgatados autorizados.',
            'withoutJourneyTitle' => 'Histórico indisponível no momento.',
            'withoutJourneyText' => 'Assim que a jornada estiver disponível, seu histórico real aparecerá aqui.',
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

    private function renderLevelDetail(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService,
        string $level,
        string $requestedJourneyType,
        string $journeySlug,
        bool $legacyRoute = false
    ): Response {
        $user = $request->user();
        $person = $user?->person;
        $levelNumber = max(1, (int) $level);
        $canSeeYouthJourney = $person ? $authorizationService->userCanViewOwnYouthJourney($user) : false;

        if (!$person) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaNivel', [
                'journey' => $journeySlug,
                'level' => $levelNumber,
                'walkingLevelDetail' => $this->emptyLevelDetailPayload(
                    'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                    $requestedJourneyType,
                    $legacyRoute,
                    false,
                    $canSeeYouthJourney,
                    'without_person'
                ),
            ]);
        }

        if ($requestedJourneyType === 'youth' && !$canSeeYouthJourney) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaNivel', [
                'journey' => $journeySlug,
                'level' => $levelNumber,
                'walkingLevelDetail' => $this->emptyLevelDetailPayload(
                    'Você não tem permissão para visualizar este nível jovem.',
                    'youth',
                    $legacyRoute,
                    false,
                    false,
                    'unauthorized_youth',
                    [
                        'id' => $person->id,
                        'name' => $person->preferred_name ?: $person->full_name,
                    ]
                ),
            ]);
        }

        $progress = $progressService->getOwnProgress($user, $requestedJourneyType);
        $recentLogs = $progressService->getRecentApprovedLogs($user, $person, $requestedJourneyType);
        $journey = $levelService->getJourneyByType($requestedJourneyType);

        if (!$journey || !($progress['authorized'] ?? false)) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaNivel', [
                'journey' => $journeySlug,
                'level' => $levelNumber,
                'walkingLevelDetail' => $this->emptyLevelDetailPayload(
                    $progress['message'] ?? 'Jornada não encontrada ou inativa.',
                    $requestedJourneyType,
                    $legacyRoute,
                    false,
                    $canSeeYouthJourney,
                    'without_journey',
                    [
                        'id' => $person->id,
                        'name' => $person->preferred_name ?: $person->full_name,
                    ]
                ),
            ]);
        }

        $requestedLevel = $journey->levels()
            ->where('is_active', true)
            ->where('level_number', $levelNumber)
            ->first();

        if (!$requestedLevel) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaNivel', [
                'journey' => $journeySlug,
                'level' => $levelNumber,
                'walkingLevelDetail' => array_merge(
                    $this->emptyLevelDetailPayload(
                        'Nível não encontrado.',
                        $requestedJourneyType,
                        $legacyRoute,
                        true,
                        $canSeeYouthJourney,
                        'missing_level',
                        [
                            'id' => $person->id,
                            'name' => $person->preferred_name ?: $person->full_name,
                        ]
                    ),
                    [
                        'journey' => $this->formatMapJourneyData($journey),
                        'levelFound' => false,
                    ]
                ),
            ]);
        }

        $levelProgress = $progress['level_progress'] ?? [];
        $points = (int) ($progress['total_points'] ?? 0);
        $currentLevel = $this->formatLevelData($levelProgress['current_level'] ?? null);
        $nextLevel = $this->formatLevelData($levelProgress['next_level'] ?? null);

        return Inertia::render('FamiliaResgate/MinhaCaminhadaNivel', [
            'journey' => $journeySlug,
            'level' => $levelNumber,
            'walkingLevelDetail' => [
                'authorized' => true,
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'requestedJourneyType' => $requestedJourneyType,
                'legacyRoute' => $legacyRoute,
                'levelFound' => true,
                'person' => [
                    'id' => $person->id,
                    'name' => $person->preferred_name ?: $person->full_name,
                ],
                'canSeeYouthJourney' => $canSeeYouthJourney,
                'message' => null,
                'journey' => $this->formatMapJourneyData($journey),
                'progress' => [
                    'points' => $points,
                    'approvedLogsCount' => (int) ($progress['approved_logs_count'] ?? 0),
                    'currentLevel' => $currentLevel,
                    'nextLevel' => $nextLevel,
                    'pointsToNextLevel' => (int) ($levelProgress['points_to_next_level'] ?? 0),
                    'progressPercent' => (int) ($levelProgress['progress_percentage'] ?? 0),
                    'recentLogs' => $recentLogs,
                ],
                'level' => $this->formatMapLevel($requestedLevel, $points, $currentLevel, $nextLevel),
                'neighborLevels' => $this->levelDetailNeighbors($journey, $requestedLevel, $journeySlug),
                'emptyStates' => $this->levelDetailEmptyStates(),
            ],
        ]);
    }

    private function emptyLevelDetailPayload(
        string $message,
        string $requestedJourneyType,
        bool $legacyRoute,
        bool $authorized,
        bool $canSeeYouthJourney,
        string $reason,
        ?array $person = null
    ): array {
        return [
            'authorized' => $authorized,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'requestedJourneyType' => $requestedJourneyType,
            'legacyRoute' => $legacyRoute,
            'levelFound' => false,
            'person' => $person,
            'canSeeYouthJourney' => $canSeeYouthJourney,
            'message' => $message,
            'reason' => $reason,
            'journey' => null,
            'progress' => [
                'points' => 0,
                'approvedLogsCount' => 0,
                'currentLevel' => null,
                'nextLevel' => null,
                'pointsToNextLevel' => 0,
                'progressPercent' => 0,
                'recentLogs' => [],
            ],
            'level' => null,
            'neighborLevels' => [
                'previous' => null,
                'next' => null,
            ],
            'emptyStates' => $this->levelDetailEmptyStates(),
        ];
    }

    private function levelDetailNeighbors(WalkingJourney $journey, WalkingLevel $level, string $journeySlug): array
    {
        $previous = $journey->levels()
            ->where('is_active', true)
            ->where('level_number', '<', $level->level_number)
            ->orderByDesc('level_number')
            ->first();
        $next = $journey->levels()
            ->where('is_active', true)
            ->where('level_number', '>', $level->level_number)
            ->orderBy('level_number')
            ->first();

        return [
            'previous' => $this->formatLevelNeighbor($previous, $journeySlug),
            'next' => $this->formatLevelNeighbor($next, $journeySlug),
        ];
    }

    private function formatLevelNeighbor(?WalkingLevel $level, string $journeySlug): ?array
    {
        if (!$level) {
            return null;
        }

        return [
            'id' => $level->id,
            'number' => $level->level_number,
            'name' => $level->name,
            'requiredPoints' => $level->required_points,
            'route' => "/familia-resgate/minha-caminhada/{$journeySlug}/niveis/{$level->level_number}",
        ];
    }

    private function levelDetailEmptyStates(): array
    {
        return [
            'withoutPersonTitle' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
            'withoutPersonText' => 'Assim que o cadastro for vinculado, os detalhes reais deste nível aparecerão aqui.',
            'missingLevelTitle' => 'Nível não encontrado.',
            'missingLevelText' => 'Verifique o endereço acessado ou volte ao mapa da caminhada.',
            'unauthorizedYouthTitle' => 'Nível jovem indisponível para este perfil.',
            'unauthorizedYouthText' => 'A caminhada jovem aparece somente para jovens/resgatados autorizados.',
            'withoutJourneyTitle' => 'Jornada indisponível no momento.',
            'withoutJourneyText' => 'Assim que a jornada estiver disponível, os detalhes reais deste nível aparecerão aqui.',
        ];
    }

    private function renderMap(
        Request $request,
        WalkingProgressService $progressService,
        WalkingLevelService $levelService,
        WalkingAuthorizationService $authorizationService,
        string $requestedJourneyType,
        string $journeySlug
    ): Response {
        $user = $request->user();
        $person = $user?->person;
        $defaultJourneyType = $levelService->getJourneyByType('general')?->type ?? 'general';

        if (!$person) {
            return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
                'area' => 'mapa',
                'journey' => $journeySlug,
                'walkingMap' => $this->emptyMapPayload(
                    'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
                    false,
                    $requestedJourneyType,
                    $defaultJourneyType
                ),
            ]);
        }

        $canSeeYouthJourney = $authorizationService->userCanViewOwnYouthJourney($user);

        return Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
            'area' => 'mapa',
            'journey' => $journeySlug,
            'walkingMap' => [
                'authorized' => true,
                'usesRealData' => true,
                'generatedAt' => now()->toISOString(),
                'message' => null,
                'requestedJourneyType' => $requestedJourneyType,
                'defaultJourneyType' => $defaultJourneyType,
                'person' => [
                    'id' => $person->id,
                    'name' => $person->preferred_name ?: $person->full_name,
                ],
                'canSeeYouthJourney' => $canSeeYouthJourney,
                'general' => $this->formatMapJourney(
                    $progressService->getOwnProgress($user),
                    $progressService->getRecentApprovedLogs($user, $person),
                    $levelService
                ),
                'youth' => $canSeeYouthJourney
                    ? $this->formatMapJourney(
                        $progressService->getOwnProgress($user, 'youth'),
                        $progressService->getRecentApprovedLogs($user, $person, 'youth'),
                        $levelService
                    )
                    : $this->emptyMapJourney('youth', false, 'Você não tem permissão para visualizar o mapa jovem.'),
                'emptyStates' => $this->mapEmptyStates(),
            ],
        ]);
    }

    private function emptyMapPayload(string $message, bool $authorized, string $requestedJourneyType, string $defaultJourneyType = 'general'): array
    {
        return [
            'authorized' => $authorized,
            'usesRealData' => true,
            'generatedAt' => now()->toISOString(),
            'message' => $message,
            'requestedJourneyType' => $requestedJourneyType,
            'defaultJourneyType' => $defaultJourneyType,
            'person' => null,
            'canSeeYouthJourney' => false,
            'general' => $this->emptyMapJourney('general', false),
            'youth' => $this->emptyMapJourney('youth', false),
            'emptyStates' => $this->mapEmptyStates(),
        ];
    }

    private function emptyMapJourney(string $journeyType, bool $authorized = true, ?string $message = null): array
    {
        return [
            'authorized' => $authorized,
            'journeyType' => $journeyType,
            'journey' => null,
            'message' => $message,
            'points' => 0,
            'approvedLogsCount' => 0,
            'currentLevel' => null,
            'nextLevel' => null,
            'pointsToNextLevel' => 0,
            'progressPercent' => 0,
            'levels' => [],
            'recentLogs' => [],
            'summary' => [
                'has_points' => false,
                'has_current_level' => false,
                'has_next_level' => false,
                'has_levels' => false,
                'is_final_level' => false,
                'completed_count' => 0,
                'next_count' => 0,
                'locked_count' => 0,
                'levels_count' => 0,
            ],
        ];
    }

    private function formatMapJourney(array $progress, array $recentLogs, WalkingLevelService $levelService): array
    {
        if (!($progress['authorized'] ?? false)) {
            return array_merge(
                $this->emptyMapJourney($progress['journey_type'] ?? 'general', false),
                ['message' => $progress['message'] ?? 'Mapa indisponível.']
            );
        }

        $journeyType = $progress['journey']['type'] ?? $progress['journey_type'] ?? 'general';
        $journey = $levelService->getJourneyByType($journeyType);

        if (!$journey) {
            return $this->emptyMapJourney($journeyType, false, 'Jornada não encontrada ou inativa.');
        }

        $levelProgress = $progress['level_progress'] ?? [];
        $points = (int) ($progress['total_points'] ?? 0);
        $currentLevel = $this->formatLevelData($levelProgress['current_level'] ?? null);
        $nextLevel = $this->formatLevelData($levelProgress['next_level'] ?? null);
        $levels = $journey->levels()
            ->where('is_active', true)
            ->orderBy('level_number')
            ->get()
            ->map(fn (WalkingLevel $level) => $this->formatMapLevel($level, $points, $currentLevel, $nextLevel))
            ->values()
            ->all();

        return [
            'authorized' => true,
            'journeyType' => $journeyType,
            'journey' => $this->formatMapJourneyData($journey),
            'message' => null,
            'points' => $points,
            'approvedLogsCount' => (int) ($progress['approved_logs_count'] ?? 0),
            'currentLevel' => $currentLevel,
            'nextLevel' => $nextLevel,
            'pointsToNextLevel' => (int) ($levelProgress['points_to_next_level'] ?? 0),
            'progressPercent' => (int) ($levelProgress['progress_percentage'] ?? 0),
            'levels' => $levels,
            'recentLogs' => $recentLogs,
            'summary' => [
                'has_points' => $points > 0,
                'has_current_level' => $currentLevel !== null,
                'has_next_level' => $nextLevel !== null,
                'has_levels' => count($levels) > 0,
                'is_final_level' => $nextLevel === null && $currentLevel !== null,
                'completed_count' => collect($levels)->where('status', 'completed')->count(),
                'next_count' => collect($levels)->where('status', 'next')->count(),
                'locked_count' => collect($levels)->where('status', 'locked')->count(),
                'levels_count' => count($levels),
            ],
        ];
    }

    private function formatMapJourneyData(WalkingJourney $journey): array
    {
        return [
            'id' => $journey->id,
            'key' => $journey->key,
            'name' => $journey->name,
            'type' => $journey->type,
            'description' => $journey->description,
        ];
    }

    private function formatMapLevel(WalkingLevel $level, int $points, ?array $currentLevel, ?array $nextLevel): array
    {
        $status = $this->mapLevelStatus($level, $points, $currentLevel, $nextLevel);

        return [
            'id' => $level->id,
            'name' => $level->name,
            'slug' => str($level->name ?: 'nivel-'.$level->level_number)->slug()->toString(),
            'number' => $level->level_number,
            'position' => $level->level_number,
            'requiredPoints' => $level->required_points,
            'description' => $level->description,
            'icon' => $level->icon ?: '✦',
            'color' => $level->color,
            'status' => $status,
            'isCurrent' => $status === 'current',
            'isCompleted' => $status === 'completed',
            'isNext' => $status === 'next',
            'isLocked' => $status === 'locked',
            'pointsToReach' => max(0, $level->required_points - $points),
        ];
    }

    private function mapLevelStatus(WalkingLevel $level, int $points, ?array $currentLevel, ?array $nextLevel): string
    {
        if (($currentLevel['id'] ?? null) === $level->id) {
            return 'current';
        }

        if (($nextLevel['id'] ?? null) === $level->id) {
            return 'next';
        }

        if ($points > 0 && $level->required_points < $points) {
            return 'completed';
        }

        return 'locked';
    }

    private function mapEmptyStates(): array
    {
        return [
            'withoutPersonTitle' => 'Seu usuário ainda não está vinculado a uma pessoa cadastrada.',
            'withoutPersonText' => 'Assim que o cadastro for vinculado, o mapa da sua caminhada real aparecerá aqui.',
            'withoutPointsTitle' => 'Ainda não há pontos aprovados nesta caminhada.',
            'withoutPointsText' => 'O mapa mostrará seu avanço conforme registros reais forem aprovados.',
            'withoutJourneyTitle' => 'Mapa indisponível no momento.',
            'withoutJourneyText' => 'Assim que a jornada estiver disponível, o mapa real aparecerá aqui.',
            'unauthorizedYouthTitle' => 'Mapa jovem indisponível para este perfil.',
            'unauthorizedYouthText' => 'A caminhada jovem aparece somente para jovens/resgatados autorizados.',
            'withoutLevelsTitle' => 'Nenhum nível cadastrado para esta jornada.',
            'withoutLevelsText' => 'Assim que os níveis oficiais estiverem ativos, o mapa será exibido aqui.',
        ];
    }
}
