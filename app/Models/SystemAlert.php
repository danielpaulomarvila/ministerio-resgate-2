<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para alertas internos do sistema
 * Tipos futuros: child_turning_11, missing_guardian, pending_member_update, incomplete_registration, financial_warning
 * Alertas ajudam a Secretaria a agir proativamente
 */
class SystemAlert extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'type',
        'title',
        'message',
        'related_person_id',
        'related_family_id',
        'severity',
        'status',
        'due_date',
        'resolved_at',
        'resolved_by_user_id',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'due_date' => 'date',
        'resolved_at' => 'datetime',
    ];

    /**
     * Relacionamento: Um alerta pode estar relacionado a uma pessoa
     * Ex: alerta de criança completando 11 anos
     */
    public function relatedPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'related_person_id');
    }

    /**
     * Relacionamento: Um alerta pode estar relacionado a uma família
     * Ex: alerta sobre membros da família
     */
    public function relatedFamily(): BelongsTo
    {
        return $this->belongsTo(Family::class, 'related_family_id');
    }

    /**
     * Relacionamento: Um alerta é resolvido por um usuário
     * Geralmente alguém da Secretaria
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    /**
     * Verifica se o alerta está pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica se o alerta foi resolvido
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved' && $this->resolved_at !== null;
    }

    /**
     * Verifica se o alerta está em andamento
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Verifica se o alerta é crítico
     */
    public function isCritical(): bool
    {
        return $this->severity === 'critical';
    }

    /**
     * Marca o alerta como resolvido
     */
    public function markAsResolved(int $userId): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by_user_id' => $userId,
        ]);
    }
}
