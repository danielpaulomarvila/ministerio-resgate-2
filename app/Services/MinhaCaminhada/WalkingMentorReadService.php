<?php

namespace App\Services\MinhaCaminhada;

use App\Models\Person;
use App\Models\User;
use App\Models\WalkingMentorResponseTemplate;

class WalkingMentorReadService
{
    public function __construct(
        private readonly WalkingAuthorizationService $authorizationService,
        private readonly WalkingProgressService $progressService,
    ) {
    }

    /**
     * Escolhe uma mensagem pré-aprovada do Mentor sem criar logs ou chamar IA externa.
     */
    public function getSuggestedMessage(User $viewer, Person $person, string $journeyType = 'general'): array
    {
        if (!$this->authorizationService->userCanViewPersonJourney($viewer, $person, $journeyType)) {
            return [
                'authorized' => false,
                'journey_type' => $journeyType,
                'message' => 'Você não tem permissão para visualizar esta caminhada.',
            ];
        }

        $progress = $this->progressService->getPersonProgress($viewer, $person, $journeyType);

        if (!($progress['authorized'] ?? false)) {
            return [
                'authorized' => false,
                'journey_type' => $journeyType,
                'message' => $progress['message'] ?? 'Você não tem permissão para visualizar esta caminhada.',
            ];
        }

        $analysisType = $this->chooseAnalysisType(
            (int) ($progress['total_points'] ?? 0),
            (int) ($progress['approved_logs_count'] ?? 0)
        );

        $template = WalkingMentorResponseTemplate::query()
            ->where('journey_type', $journeyType)
            ->where('analysis_type', $analysisType)
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (!$template) {
            return $this->fallbackMessage($journeyType, $analysisType);
        }

        return [
            'authorized' => true,
            'journey_type' => $journeyType,
            'analysis_type' => $analysisType,
            'title' => $template->title,
            'body' => $template->body,
            'tone' => $template->tone,
            'source' => 'pre_approved_template',
        ];
    }

    /**
     * Lê uma sugestão para a própria pessoa vinculada ao usuário.
     */
    public function getOwnSuggestedMessage(User $viewer, string $journeyType = 'general'): array
    {
        if (!$viewer->person) {
            return [
                'authorized' => false,
                'journey_type' => $journeyType,
                'message' => 'Usuário sem pessoa vinculada.',
            ];
        }

        return $this->getSuggestedMessage($viewer, $viewer->person, $journeyType);
    }

    private function chooseAnalysisType(int $totalPoints, int $approvedLogsCount): string
    {
        if ($totalPoints === 0) {
            return 'rhythm_drop';
        }

        if ($approvedLogsCount >= 5) {
            return 'balanced_growth';
        }

        if ($totalPoints >= 100) {
            return 'presence_strong';
        }

        return 'balanced_growth';
    }

    private function fallbackMessage(string $journeyType, string $analysisType): array
    {
        return [
            'authorized' => true,
            'journey_type' => $journeyType,
            'analysis_type' => $analysisType,
            'title' => 'Continue caminhando com constância',
            'body' => 'Valorize os pequenos passos da caminhada. Crescimento saudável pode começar por uma atitude simples e fiel nesta semana.',
            'tone' => 'pastoral',
            'source' => 'safe_fallback',
        ];
    }
}
