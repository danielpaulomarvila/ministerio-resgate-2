<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para responsáveis por menores de idade
 * Usado para crianças e Júniores (11-13 anos)
 * Define responsável legal, financeiro e quem aprova alterações
 * Essencial para cantina e financeiro futuros
 */
class GuardianShip extends Model
{
    // Nome da tabela no banco de dados
    // Laravel padrão seria guardian_ships, mas a tabela real é guardianships
    protected $table = 'guardianships';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'minor_person_id',
        'guardian_person_id',
        'relationship_type',
        'is_legal_guardian',
        'is_financial_responsible',
        'can_approve_changes',
        'can_view_financial',
        'can_authorize_login',
        'can_receive_canteen_debts',
        'starts_at',
        'ends_at',
        'status',
        'notes',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'is_legal_guardian' => 'boolean',
        'is_financial_responsible' => 'boolean',
        'can_approve_changes' => 'boolean',
        'can_view_financial' => 'boolean',
        'can_authorize_login' => 'boolean',
        'can_receive_canteen_debts' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    /**
     * Relacionamento: Uma responsabilidade pertence a um menor (pessoa)
     */
    public function minor(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'minor_person_id');
    }

    /**
     * Relacionamento: Uma responsabilidade pertence a um responsável (pessoa)
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'guardian_person_id');
    }

    /**
     * Verifica se a responsabilidade está ativa
     * Responsabilidade ativa não tem data de fim
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at === null;
    }

    /**
     * Verifica se o responsável é responsável legal
     */
    public function isLegalGuardian(): bool
    {
        return $this->is_legal_guardian;
    }

    /**
     * Verifica se o responsável é financeiramente responsável
     * Importante para cantina e financeiro
     */
    public function isFinancialResponsible(): bool
    {
        return $this->is_financial_responsible;
    }

    /**
     * Verifica se o responsável pode autorizar login do menor
     * Importante para Júniores (11-13 anos)
     */
    public function canAuthorizeLogin(): bool
    {
        return $this->can_authorize_login;
    }

    /**
     * Verifica se o responsável pode aprovar alterações no cadastro
     */
    public function canApproveChanges(): bool
    {
        return $this->can_approve_changes;
    }

    /**
     * Verifica se o responsável pode ver dados financeiros do menor
     */
    public function canViewFinancial(): bool
    {
        return $this->can_view_financial;
    }

    /**
     * Verifica se o responsável recebe dívidas futuras da cantina
     * Importante para menores de 11 anos
     */
    public function canReceiveCanteenDebts(): bool
    {
        return $this->can_receive_canteen_debts;
    }
}
