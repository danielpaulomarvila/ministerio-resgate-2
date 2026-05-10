<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SecretaryRequest extends Model
{
    /**
     * Campos que podem ser atribuídos em massa
     */
    protected $fillable = [
        'uuid',
        'type',
        'status',
        'priority',
        'title',
        'description',
        'requester_user_id',
        'requester_person_id',
        'target_type',
        'target_id',
        'related_alert_id',
        'assigned_to_user_id',
        'current_snapshot',
        'requested_changes',
        'internal_notes',
        'decision_notes',
        'submitted_at',
        'reviewed_at',
        'reviewed_by_user_id',
        'approved_at',
        'approved_by_user_id',
        'rejected_at',
        'rejected_by_user_id',
        'completed_at',
        'completed_by_user_id',
        'cancelled_at',
        'cancelled_by_user_id',
        'due_at',
        'metadata',
    ];

    /**
     * Casts para tipos específicos
     */
    protected $casts = [
        'current_snapshot' => 'array',
        'requested_changes' => 'array',
        'metadata' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    /**
     * Relacionamento: Usuário solicitante
     */
    public function requesterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    /**
     * Relacionamento: Pessoa solicitante
     */
    public function requesterPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'requester_person_id');
    }

    /**
     * Relacionamento: Usuário responsável pela análise
     */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Relacionamento: Usuário que revisou
     */
    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    /**
     * Relacionamento: Usuário que aprovou
     */
    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Relacionamento: Usuário que rejeitou
     */
    public function rejectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by_user_id');
    }

    /**
     * Relacionamento: Usuário que concluiu
     */
    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    /**
     * Relacionamento: Usuário que cancelou
     */
    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by_user_id');
    }

    /**
     * Relacionamento: Alerta relacionado
     */
    public function relatedAlert(): BelongsTo
    {
        return $this->belongsTo(SystemAlert::class, 'related_alert_id');
    }

    /**
     * Verifica se a solicitação está pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica se a solicitação está em análise
     */
    public function isInReview(): bool
    {
        return $this->status === 'in_review';
    }

    /**
     * Verifica se a solicitação foi aprovada
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verifica se a solicitação foi rejeitada
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Verifica se a solicitação foi concluída
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica se a solicitação foi cancelada
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Marca solicitação como em análise
     */
    public function markInReview(int $userId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'in_review',
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $userId,
            'decision_notes' => $notes,
        ]);
    }

    /**
     * Aprova solicitação
     */
    public function approve(int $userId, string $notes): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by_user_id' => $userId,
            'decision_notes' => $notes,
        ]);
    }

    /**
     * Rejeita solicitação
     */
    public function reject(int $userId, string $notes): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by_user_id' => $userId,
            'decision_notes' => $notes,
        ]);
    }

    /**
     * Conclui solicitação
     */
    public function complete(int $userId, string $notes): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by_user_id' => $userId,
            'decision_notes' => $notes,
        ]);
    }

    /**
     * Cancela solicitação
     */
    public function cancel(int $userId, string $notes): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by_user_id' => $userId,
            'decision_notes' => $notes,
        ]);
    }

    /**
     * Verifica se a solicitação pode ser editada
     */
    public function canBeEdited(): bool
    {
        return !$this->isCompleted() && !$this->isRejected() && !$this->isCancelled();
    }

    /**
     * Verifica se a solicitação está atrasada
     */
    public function isOverdue(): bool
    {
        if (!$this->due_at) {
            return false;
        }

        return $this->due_at->isPast() && !$this->isCompleted();
    }
}
