<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model pivot para vínculo entre pessoas e famílias
 * Tabela intermediária que permite relacionamento many-to-many
 * com dados adicionais como tipo de relacionamento e período
 */
class FamilyMember extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'family_id',
        'person_id',
        'relationship_type',
        'is_main_responsible',
        'starts_at',
        'ends_at',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'is_main_responsible' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    /**
     * Relacionamento: Um vínculo pertence a uma família
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Relacionamento: Um vínculo pertence a uma pessoa
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Verifica se o vínculo está ativo
     * Vínculo ativo não tem data de fim
     */
    public function isActive(): bool
    {
        return $this->ends_at === null;
    }
}
