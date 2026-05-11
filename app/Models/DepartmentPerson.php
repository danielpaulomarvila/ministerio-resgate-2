<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model pivot para vínculo entre pessoas e departamentos
 * Tabela intermediária que permite relacionamento many-to-many
 * com dados adicionais como função, categoria e período
 * No departamento Resgatados: category = junior (11-13 anos) ou jovem (14-17 anos)
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 */
class DepartmentPerson extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'department_id',
        'person_id',
        'role',
        'category',
        'starts_at',
        'ends_at',
        'status',
        'is_leader',
        'is_assistant',
        'can_manage_department',
        'notes',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_leader' => 'boolean',
        'is_assistant' => 'boolean',
        'can_manage_department' => 'boolean',
    ];

    /**
     * Relacionamento: Um vínculo pertence a um departamento
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relacionamento: Um vínculo pertence a uma pessoa
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: Usuário que criou o vínculo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Relacionamento: Usuário que atualizou o vínculo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Verifica se o vínculo está ativo
     * Vínculo ativo não tem data de fim
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at === null;
    }

    /**
     * Verifica se a pessoa é líder no departamento
     */
    public function isLeader(): bool
    {
        return $this->is_leader;
    }

    /**
     * Verifica se a pessoa é auxiliar no departamento
     */
    public function isAssistant(): bool
    {
        return $this->is_assistant;
    }

    /**
     * Verifica se a pessoa pode gerenciar o departamento
     */
    public function canManage(): bool
    {
        return $this->can_manage_department;
    }

    /**
     * Verifica se a pessoa é Júnior no departamento Resgatados
     * Júnior: 11-13 anos
     */
    public function isJunior(): bool
    {
        return $this->category === 'junior';
    }

    /**
     * Verifica se a pessoa é Jovem no departamento Resgatados
     * Jovem: 14-17 anos
     */
    public function isYoung(): bool
    {
        return $this->category === 'jovem';
    }
}
