<?php

namespace App\Http\Controllers;

use App\Models\SystemAlert;
use App\Services\Secretaria\SecretaryAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller para gerenciar alertas internos da Secretaria
 *
 * Responsável por:
 * - Listar alertas
 * - Mostrar detalhes de um alerta
 * - Resolver alertas
 * - Ignorar alertas
 * - Regenerar alertas com base nas regras atuais
 */
class SecretaryAlertController extends Controller
{
    /**
     * Service para geração de alertas
     */
    protected SecretaryAlertService $alertService;

    /**
     * Injeta o service de alertas
     */
    public function __construct(SecretaryAlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    /**
     * Lista todos os alertas da Secretaria
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', SystemAlert::class);

        // Buscar alertas com filtros opcionais
        $query = SystemAlert::with(['relatedPerson', 'relatedFamily', 'resolvedBy']);

        // Filtro por status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtro por tipo
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filtro por severidade
        if ($request->has('severity') && $request->severity !== 'all') {
            $query->where('severity', $request->severity);
        }

        // Ordenar por data de criação (mais recentes primeiro)
        $alerts = $query->latest()->get();

        // Calcular contagens por status
        $openCount = SystemAlert::where('status', 'pending')->count();
        $resolvedCount = SystemAlert::where('status', 'resolved')->count();
        $ignoredCount = SystemAlert::where('status', 'dismissed')->count();

        // Calcular contagens por severidade
        $infoCount = SystemAlert::where('severity', 'low')->count();
        $warningCount = SystemAlert::where('severity', 'medium')->count();
        $dangerCount = SystemAlert::whereIn('severity', ['high', 'critical'])->count();

        return Inertia::render('Secretaria/Alerts/Index', [
            'alerts' => $alerts->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'title' => $alert->title,
                    'message' => $alert->message,
                    'severity' => $alert->severity,
                    'status' => $alert->status,
                    'detected_at' => $alert->created_at->format('d/m/Y H:i'),
                    'resolved_at' => $alert->resolved_at ? $alert->resolved_at->format('d/m/Y H:i') : null,
                    'related_person' => $alert->relatedPerson ? [
                        'id' => $alert->relatedPerson->id,
                        'full_name' => $alert->relatedPerson->full_name,
                    ] : null,
                    'related_family' => $alert->relatedFamily ? [
                        'id' => $alert->relatedFamily->id,
                        'name' => $alert->relatedFamily->name,
                    ] : null,
                ];
            }),
            'counts' => [
                'open' => $openCount,
                'resolved' => $resolvedCount,
                'ignored' => $ignoredCount,
                'info' => $infoCount,
                'warning' => $warningCount,
                'danger' => $dangerCount,
            ],
            'filters' => [
                'status' => $request->get('status', 'all'),
                'type' => $request->get('type', 'all'),
                'severity' => $request->get('severity', 'all'),
            ],
        ]);
    }

    /**
     * Mostra detalhes de um alerta específico
     *
     * @return Response
     */
    public function show(SystemAlert $systemAlert)
    {
        $this->authorize('view', $systemAlert);

        $systemAlert->load(['relatedPerson', 'relatedFamily', 'resolvedBy']);

        return Inertia::render('Secretaria/Alerts/Show', [
            'alert' => [
                'id' => $systemAlert->id,
                'type' => $systemAlert->type,
                'title' => $systemAlert->title,
                'message' => $systemAlert->message,
                'severity' => $systemAlert->severity,
                'status' => $systemAlert->status,
                'detected_at' => $systemAlert->created_at->format('d/m/Y H:i'),
                'resolved_at' => $systemAlert->resolved_at ? $systemAlert->resolved_at->format('d/m/Y H:i') : null,
                'resolution_notes' => $systemAlert->resolution_notes,
                'related_person' => $systemAlert->relatedPerson ? [
                    'id' => $systemAlert->relatedPerson->id,
                    'full_name' => $systemAlert->relatedPerson->full_name,
                    'birth_date' => $systemAlert->relatedPerson->birth_date ? $systemAlert->relatedPerson->birth_date->format('d/m/Y') : null,
                ] : null,
                'related_family' => $systemAlert->relatedFamily ? [
                    'id' => $systemAlert->relatedFamily->id,
                    'name' => $systemAlert->relatedFamily->name,
                ] : null,
                'resolved_by' => $systemAlert->resolvedBy ? [
                    'id' => $systemAlert->resolvedBy->id,
                    'name' => $systemAlert->resolvedBy->name,
                ] : null,
            ],
        ]);
    }

    /**
     * Mostra a tela de resolução/tratamento do alerta
     *
     * @return Response
     */
    public function resolveShow(SystemAlert $systemAlert)
    {
        $this->authorize('resolve', $systemAlert);

        $systemAlert->load(['relatedPerson', 'relatedFamily', 'resolvedBy']);

        return Inertia::render('Secretaria/Alerts/Resolve', [
            'alert' => [
                'id' => $systemAlert->id,
                'type' => $systemAlert->type,
                'title' => $systemAlert->title,
                'message' => $systemAlert->message,
                'severity' => $systemAlert->severity,
                'status' => $systemAlert->status,
                'detected_at' => $systemAlert->created_at->format('d/m/Y H:i'),
                'resolved_at' => $systemAlert->resolved_at ? $systemAlert->resolved_at->format('d/m/Y H:i') : null,
                'resolution_notes' => $systemAlert->resolution_notes,
                'related_person' => $systemAlert->relatedPerson ? [
                    'id' => $systemAlert->relatedPerson->id,
                    'full_name' => $systemAlert->relatedPerson->full_name,
                    'birth_date' => $systemAlert->relatedPerson->birth_date ? $systemAlert->relatedPerson->birth_date->format('d/m/Y') : null,
                    'primary_phone' => $systemAlert->relatedPerson->primary_phone,
                    'email' => $systemAlert->relatedPerson->email,
                ] : null,
                'related_family' => $systemAlert->relatedFamily ? [
                    'id' => $systemAlert->relatedFamily->id,
                    'name' => $systemAlert->relatedFamily->name,
                ] : null,
                'resolved_by' => $systemAlert->resolvedBy ? [
                    'id' => $systemAlert->resolvedBy->id,
                    'name' => $systemAlert->resolvedBy->name,
                ] : null,
            ],
        ]);
    }

    /**
     * Marca um alerta como em andamento
     *
     * @return RedirectResponse
     */
    public function markInProgress(Request $request, SystemAlert $systemAlert)
    {
        $this->authorize('resolve', $systemAlert);

        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();
        $notes = $request->get('resolution_notes');

        $systemAlert->update([
            'status' => 'in_progress',
            'resolution_notes' => $notes,
        ]);

        return redirect()->route('secretaria.alerts.resolve.show', $systemAlert->id)
            ->with('success', 'Alerta marcado como em andamento.');
    }

    /**
     * Verifica se o alerta foi realmente resolvido
     *
     * @return RedirectResponse
     */
    public function verifyResolution(Request $request, SystemAlert $systemAlert)
    {
        $this->authorize('resolve', $systemAlert);

        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();
        $notes = $request->get('resolution_notes');

        // Verificar se o problema foi realmente resolvido
        $verification = $this->alertService->isAlertActuallyResolved($systemAlert);

        if ($verification['resolved']) {
            // Se foi resolvido, marcar como resolved
            $systemAlert->markAsResolved($userId, $notes);

            return redirect()->route('secretaria.alerts.index')
                ->with('success', 'Alerta marcado como resolvido. '.$verification['message']);
        } else {
            // Se não foi resolvido, manter em andamento ou pending
            $newStatus = $systemAlert->status === 'pending' ? 'pending' : 'in_progress';

            $systemAlert->update([
                'status' => $newStatus,
                'resolution_notes' => $notes,
            ]);

            return redirect()->route('secretaria.alerts.resolve.show', $systemAlert->id)
                ->with('error', 'O problema ainda não foi corrigido. '.$verification['message']);
        }
    }

    /**
     * Marca um alerta como ignorado
     *
     * @return RedirectResponse
     */
    public function ignore(Request $request, SystemAlert $systemAlert)
    {
        $this->authorize('update', $systemAlert);

        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();
        $notes = $request->get('resolution_notes');

        $systemAlert->markAsIgnored($userId, $notes);

        return redirect()->route('secretaria.alerts.index')
            ->with('success', 'Alerta marcado como ignorado com sucesso.');
    }

    /**
     * Regenera todos os alertas com base nas regras atuais
     * Não duplica alertas abertos iguais
     *
     * @return RedirectResponse
     */
    public function regenerate()
    {
        $this->authorize('create', SystemAlert::class);

        $this->alertService->regenerateAlerts();

        return redirect()->route('secretaria.alerts.index')
            ->with('success', 'Alertas regenerados com sucesso.');
    }
}
