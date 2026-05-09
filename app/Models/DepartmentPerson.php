<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model pivot para vínculo entre pessoas e departamentos
 * Tabela intermediária que permite relacionamento many-to-many
 * com dados adicionais como função, categoria e período
 * No departamento Resgatados: category = junior (11-13 anos) ou jovem (14-17 anos)
 */
class DepartmentPerson extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'department_id',
        'person_id',
        'role_name',
        'category',
        'starts_at',
        'ends_at',
        'status',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
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
     * Verifica se o vínculo está ativo
     * Vínculo ativo não tem data de fim
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at === null;
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
