<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model para departamentos da igreja
 * Departamentos futuros: Secretaria, Tesouraria, Cantina, Louvor, Mídia, Resgatados, Intercessão, Recepção, Pastoral
 */
class Department extends Model
{
    use SoftDeletes;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    /**
     * Relacionamento: Um departamento pode ter várias pessoas vinculadas
     * Usa a tabela pivot department_people
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'department_people')
            ->withPivot('role_name', 'category', 'starts_at', 'ends_at', 'status')
            ->withTimestamps();
    }

    /**
     * Verifica se o departamento está ativo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
