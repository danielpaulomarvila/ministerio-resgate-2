<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberProfile extends Model
{
    use SoftDeletes;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'person_id',
        'membership_number',
        'membership_date',
        'membership_status',
        'approved_by_user_id',
        'notes',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'membership_date' => 'date',
    ];

    /**
     * Relacionamento: Um perfil de membro pertence a uma pessoa
     * Só existe para pessoas batizadas
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: Um perfil de membro é aprovado por um usuário
     * Geralmente alguém da Secretaria
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Verifica se o membro está ativo
     */
    public function isActive(): bool
    {
        return $this->membership_status === 'active';
    }
}
