<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model para departamentos da igreja
 * Departamentos: Secretaria, Tesouraria, Cantina, Louvor, Mídia, Jovens/Resgatados, Intercessão, Recepção, Pastoral
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 * - Liderar departamento não cria permissão automaticamente sem controle
 */
class Department extends Model
{
    use SoftDeletes;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'slug',
        'description',
        'department_type',
        'parent_department_id',
        'leader_person_id',
        'assistant_leader_person_id',
        'color',
        'icon',
        'meeting_day',
        'meeting_time',
        'location',
        'sort_order',
        'is_system',
        'notes',
        'status',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'meeting_time' => 'datetime:H:i',
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relacionamento: Departamento pai (hierarquia)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

    /**
     * Relacionamento: Departamentos filhos
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_department_id');
    }

    /**
     * Relacionamento: Líder do departamento
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'leader_person_id');
    }

    /**
     * Relacionamento: Auxiliar do departamento
     */
    public function assistantLeader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'assistant_leader_person_id');
    }

    /**
     * Relacionamento: Usuário que criou o departamento
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Relacionamento: Usuário que atualizou o departamento
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Relacionamento: Um departamento pode ter várias pessoas vinculadas
     * Usa a tabela pivot department_people
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'department_people')
            ->withPivot('role', 'category', 'starts_at', 'ends_at', 'status', 'is_leader', 'is_assistant', 'can_manage_department', 'notes')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Pessoas ativas no departamento
     */
    public function activePeople(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'department_people')
            ->withPivot('role', 'category', 'starts_at', 'ends_at', 'status', 'is_leader', 'is_assistant', 'can_manage_department', 'notes')
            ->wherePivot('status', 'active')
            ->whereNull('ends_at')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Vínculos department_people
     */
    public function departmentPeople(): HasMany
    {
        return $this->hasMany(DepartmentPerson::class);
    }

    /**
     * Relacionamento: Vínculos ativos department_people
     */
    public function activeDepartmentPeople(): HasMany
    {
        return $this->hasMany(DepartmentPerson::class)
            ->where('status', 'active')
            ->whereNull('ends_at');
    }

    /**
     * Verifica se o departamento está ativo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Verifica se o departamento é do sistema
     */
    public function isSystem(): bool
    {
        return $this->is_system;
    }

    /**
     * Conta o número de membros ativos no departamento
     */
    public function activeMembersCount(): int
    {
        return $this->activeDepartmentPeople()->count();
    }

    /**
     * Retorna label com nomes dos líderes
     */
    public function leadersLabel(): string
    {
        if ($this->leader) {
            return $this->leader->full_name;
        }
        
        $leaders = $this->activeDepartmentPeople()->where('is_leader', true)->get();
        if ($leaders->isNotEmpty()) {
            return $leaders->map(fn($dp) => $dp->person->full_name)->join(', ');
        }
        
        return 'Sem líder';
    }
}
