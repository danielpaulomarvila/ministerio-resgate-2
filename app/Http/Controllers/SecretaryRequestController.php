<?php

namespace App\Http\Controllers;

use App\Models\SecretaryRequest;
use App\Models\SystemAlert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecretaryRequestController extends Controller
{
    /**
     * Lista todas as solicitações com filtros
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', SecretaryRequest::class);

        $query = SecretaryRequest::with([
            'requesterPerson',
            'relatedAlert',
            'assignedToUser',
        ]);

        // Filtros
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        // Contagens para cards
        $counts = [
            'pending' => SecretaryRequest::where('status', 'pending')->count(),
            'in_review' => SecretaryRequest::where('status', 'in_review')->count(),
            'approved' => SecretaryRequest::where('status', 'approved')->count(),
            'completed' => SecretaryRequest::where('status', 'completed')->count(),
            'urgent' => SecretaryRequest::where('priority', 'urgent')->count(),
        ];

        return Inertia::render('Secretaria/Requests/Index', [
            'requests' => $requests,
            'counts' => $counts,
            'filters' => [
                'status' => $request->status ?? 'all',
                'type' => $request->type ?? 'all',
                'priority' => $request->priority ?? 'all',
            ],
        ]);
    }

    /**
     * Mostra formulário de criação
     */
    public function create(Request $request)
    {
        $this->authorize('create', SecretaryRequest::class);

        // Se vier alert_id, busca o alerta para preencher dados
        $alert = null;
        if ($request->has('alert_id')) {
            $alert = SystemAlert::with('relatedPerson')->find($request->alert_id);
        }

        return Inertia::render('Secretaria/Requests/Create', [
            'alert' => $alert,
        ]);
    }

    /**
     * Salva nova solicitação
     */
    public function store(Request $request)
    {
        $this->authorize('create', SecretaryRequest::class);

        $validated = $request->validate([
            'type' => 'required|in:registration_review,personal_data_change,family_link_review,guardianship_review,child_transition_review,alert_resolution_review,manual_secretary_request',
            'priority' => 'required|in:low,normal,high,urgent',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requester_person_id' => 'nullable|exists:people,id',
            'related_alert_id' => 'nullable|exists:system_alerts,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date|after:now',
            'internal_notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['requester_user_id'] = auth()->id();

        SecretaryRequest::create($validated);

        return redirect()->route('secretaria.requests.index')
            ->with('success', 'Solicitação criada com sucesso.');
    }

    /**
     * Mostra detalhes de uma solicitação
     */
    public function show(SecretaryRequest $secretaryRequest)
    {
        $this->authorize('view', $secretaryRequest);

        $secretaryRequest->load([
            'requesterUser',
            'requesterPerson',
            'assignedToUser',
            'reviewedByUser',
            'approvedByUser',
            'rejectedByUser',
            'completedByUser',
            'relatedAlert',
        ]);

        return Inertia::render('Secretaria/Requests/Show', [
            'request' => $secretaryRequest,
        ]);
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->canBeEdited()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Esta solicitação não pode ser editada.');
        }

        $secretaryRequest->load(['requesterPerson', 'relatedAlert', 'assignedToUser']);

        return Inertia::render('Secretaria/Requests/Edit', [
            'request' => $secretaryRequest,
        ]);
    }

    /**
     * Atualiza solicitação
     */
    public function update(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->canBeEdited()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Esta solicitação não pode ser editada.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'requester_person_id' => 'nullable|exists:people,id',
            'related_alert_id' => 'nullable|exists:system_alerts,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date|after:now',
            'internal_notes' => 'nullable|string',
        ]);

        $secretaryRequest->update($validated);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação atualizada com sucesso.');
    }

    /**
     * Marca solicitação como em análise
     */
    public function markInReview(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->isPending()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Apenas solicitações pendentes podem ser marcadas como em análise.');
        }

        $validated = $request->validate([
            'decision_notes' => 'nullable|string',
        ]);

        $secretaryRequest->markInReview(auth()->id(), $validated['decision_notes'] ?? null);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação marcada como em análise.');
    }

    /**
     * Aprova solicitação
     */
    public function approve(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->isPending() && ! $secretaryRequest->isInReview()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Apenas solicitações pendentes ou em análise podem ser aprovadas.');
        }

        $validated = $request->validate([
            'decision_notes' => 'required|string',
        ]);

        $secretaryRequest->approve(auth()->id(), $validated['decision_notes']);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação aprovada com sucesso.');
    }

    /**
     * Rejeita solicitação
     */
    public function reject(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->isPending() && ! $secretaryRequest->isInReview()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Apenas solicitações pendentes ou em análise podem ser rejeitadas.');
        }

        $validated = $request->validate([
            'decision_notes' => 'required|string',
        ]);

        $secretaryRequest->reject(auth()->id(), $validated['decision_notes']);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação rejeitada com sucesso.');
    }

    /**
     * Conclui solicitação
     */
    public function complete(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->isApproved() && ! $secretaryRequest->isInReview()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Apenas solicitações aprovadas ou em análise podem ser concluídas.');
        }

        $validated = $request->validate([
            'decision_notes' => 'required|string',
        ]);

        $secretaryRequest->complete(auth()->id(), $validated['decision_notes']);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação concluída com sucesso.');
    }

    /**
     * Cancela solicitação
     */
    public function cancel(Request $request, SecretaryRequest $secretaryRequest)
    {
        $this->authorize('update', $secretaryRequest);

        if (! $secretaryRequest->isPending() && ! $secretaryRequest->isInReview()) {
            return redirect()->route('secretaria.requests.show', $secretaryRequest)
                ->with('error', 'Apenas solicitações pendentes ou em análise podem ser canceladas.');
        }

        $validated = $request->validate([
            'decision_notes' => 'required|string',
        ]);

        $secretaryRequest->cancel(auth()->id(), $validated['decision_notes']);

        return redirect()->route('secretaria.requests.show', $secretaryRequest)
            ->with('success', 'Solicitação cancelada com sucesso.');
    }
}
