<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'full_name',
        'preferred_name',
        'birth_date',
        'gender',
        'email',
        'phone',
        'document_number',
        'photo_path',
        'is_baptized',
        'baptism_date',
        'person_status',
        'notes',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date',
        'is_baptized' => 'boolean',
    ];

    /**
     * Relacionamento: Uma pessoa pode ter um usuário de login
     * Nem toda pessoa tem usuário (ex: crianças menores de 11 anos)
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Relacionamento: Uma pessoa pode ter um perfil de membro
     * Só existe para pessoas batizadas
     */
    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    /**
     * Relacionamento: Uma pessoa pode pertencer a famílias
     * Usa a tabela pivot family_members
     */
    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members')
            ->withPivot('relationship_type', 'is_main_responsible', 'starts_at', 'ends_at')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Uma pessoa pode estar em departamentos
     * Usa a tabela pivot department_people
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_people')
            ->withPivot('role_name', 'category', 'starts_at', 'ends_at', 'status')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Uma pessoa (menor) pode ter responsáveis
     * Quando a pessoa é o menor de idade
     */
    public function guardianshipsAsMinor(): HasMany
    {
        return $this->hasMany(GuardianShip::class, 'minor_person_id');
    }

    /**
     * Relacionamento: Uma pessoa pode ser responsável por menores
     * Quando a pessoa é o responsável
     */
    public function guardianshipsAsGuardian(): HasMany
    {
        return $this->hasMany(GuardianShip::class, 'guardian_person_id');
    }

    /**
     * Relacionamento: Uma pessoa pode ter alertas do sistema
     * Ex: alerta de criança completando 11 anos
     */
    public function systemAlerts(): HasMany
    {
        return $this->hasMany(SystemAlert::class, 'related_person_id');
    }

    /**
     * Calcula a idade atual da pessoa com base na birth_date
     * Retorna null se não houver data de nascimento
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    /**
     * Verifica se a pessoa é menor de 11 anos
     * Usado para definir se pode ter usuário
     */
    public function isUnder11YearsOld(): bool
    {
        $age = $this->getAgeAttribute();
        return $age !== null && $age < 11;
    }

    /**
     * Verifica se a pessoa está na faixa de Júnior (11-13 anos)
     * Usado para departamento Resgatados
     */
    public function isJunior(): bool
    {
        $age = $this->getAgeAttribute();
        return $age !== null && $age >= 11 && $age < 14;
    }

    /**
     * Verifica se a pessoa está na faixa de Jovem (14-17 anos)
     * Usado para departamento Resgatados
     */
    public function isYoung(): bool
    {
        $age = $this->getAgeAttribute();
        return $age !== null && $age >= 14 && $age < 18;
    }

    /**
     * Verifica se a pessoa é adulta (18 anos ou mais)
     */
    public function isAdult(): bool
    {
        $age = $this->getAgeAttribute();
        return $age !== null && $age >= 18;
    }
}
