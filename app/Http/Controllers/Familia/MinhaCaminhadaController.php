<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Services\MinhaCaminhada\WalkingAuthorizationService;
use App\Services\MinhaCaminhada\WalkingDashboardReadService;
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

    private function dashboardHasAuthorizedProgress(array $dashboard): bool
    {
        return (bool) ($dashboard['authorized'] ?? false)
            && (bool) ($dashboard['progress']['authorized'] ?? false);
    }
}
