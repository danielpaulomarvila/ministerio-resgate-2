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
        'uuid',
        'full_name',
        'preferred_name',
        'last_name',
        'birth_date',
        'gender',
        'marital_status',
        'education_level',
        'nationality',
        'birthplace',
        'profession',
        'occupation',
        'primary_phone',
        'secondary_phone',
        'whatsapp',
        'email',
        'photo_path',
        'contact_notes',
        'general_notes',
        'is_baptized',
        'baptism_date',
        'conversion_date',
        'invited_by_person_id',
        'person_status',
        'created_by_user_id',
        'updated_by_user_id',
        'deleted_by_user_id',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date',
        'conversion_date' => 'date',
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
     * Relacionamento: Uma pessoa pode ter um registro de documentos
     * Documentos foram separados para deixar a tabela people mais limpa
     */
    public function document(): HasOne
    {
        return $this->hasOne(PersonDocument::class);
    }

    /**
     * Relacionamento: Uma pessoa pode ter múltiplas moradas
     * Moradas foram separadas para deixar a tabela people mais limpa
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * Relacionamento: Uma pessoa pode ter uma morada principal
     */
    public function primaryAddress(): HasOne
    {
        return $this->hasOne(PersonAddress::class)->where('is_primary', true);
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
     * Atualizado na Etapa 2 com novos nomes de campos
     */
    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members')
            ->withPivot('role', 'is_responsible', 'joined_at', 'left_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Uma pessoa pode estar em departamentos
     * Usa a tabela pivot department_people
     * IMPORTANTE: Vínculo em departamento não cria membro automaticamente
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_people')
            ->withPivot('role', 'category', 'starts_at', 'ends_at', 'status', 'is_leader', 'is_assistant', 'can_manage_department', 'notes')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Departamentos ativos da pessoa
     */
    public function activeDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_people')
            ->withPivot('role', 'category', 'starts_at', 'ends_at', 'status', 'is_leader', 'is_assistant', 'can_manage_department', 'notes')
            ->wherePivot('status', 'active')
            ->whereNull('ends_at')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Vínculos department_people da pessoa
     */
    public function departmentMemberships(): HasMany
    {
        return $this->hasMany(DepartmentPerson::class);
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
     * Relacionamento: Uma pessoa pode ter sido convidada/influenciada/indicada por outra pessoa
     * Campo único para informar quem trouxe a pessoa para a igreja
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'invited_by_person_id');
    }

    /**
     * Relacionamento: Uma pessoa pode ter convidado/influenciado/indicado outras pessoas
     */
    public function invitedPeople(): HasMany
    {
        return $this->hasMany(Person::class, 'invited_by_person_id');
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

    /**
     * Retorna o rótulo da categoria de idade para exibição
     * Classifica por idade apenas, sem criar vínculo com departamento
     * 
     * @return string|null Rótulo da categoria ou null se não houver data de nascimento
     */
    public function ageGroupLabel(): ?string
    {
        $age = $this->getAgeAttribute();
        
        if ($age === null) {
            return null;
        }
        
        if ($age < 11) {
            return 'Menor de 11 anos';
        }
        
        if ($age >= 11 && $age < 14) {
            return 'Júnior (11-13 anos)';
        }
        
        if ($age >= 14 && $age < 18) {
            return 'Jovem (14-17 anos)';
        }
        
        return 'Adulto (18 anos ou mais)';
    }

    /**
     * Verifica se a pessoa pode ter usuário no sistema
     * Regra: menores de 11 anos não podem ter usuário próprio
     * 
     * @return bool True se pode ter usuário
     */
    public function canHaveUser(): bool
    {
        // Menores de 11 anos não podem ter usuário próprio
        if ($this->isUnder11YearsOld()) {
            return false;
        }
        
        // Júniores (11-13) podem ter usuário, mas precisam de supervisão
        // Jovens (14-17) podem ter usuário
        // Adultos podem ter usuário
        return true;
    }

    /**
     * Verifica se a pessoa pode ser membro
     * Regra: Pessoa batizada com 11 anos ou mais pode ser membro
     *
     * @return bool True se pode ser membro (é batizada e tem 11+ anos)
     */
    public function canBeMember(): bool
    {
        return (bool) $this->is_baptized
            && $this->age !== null
            && $this->age >= 11;
    }

    /**
     * Verifica se a pessoa precisa de responsável/supervisão para ter usuário
     * Regra: Júniores (11-13 anos) precisam de responsável ativo autorizado
     * 
     * @return bool True se precisa de responsável
     */
    public function requiresGuardianForUser(): bool
    {
        return $this->isJunior();
    }

    /**
     * Verifica se a pessoa tem responsável ativo autorizado para login
     * Usado para validar elegibilidade de Júniores
     * 
     * @return bool True se tem responsável ativo com can_authorize_login = true
     */
    public function hasActiveGuardianAuthorizedForLogin(): bool
    {
        if (!$this->requiresGuardianForUser()) {
            return true; // Não precisa de responsável
        }

        return $this->guardianshipsAsMinor()
            ->where('status', 'active')
            ->where('can_authorize_login', true)
            ->whereHas('guardian', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->exists();
    }

    /**
     * Retorna a idade da pessoa
     * Método auxiliar para compatibilidade
     * 
     * @return int|null
     */
    public function age(): ?int
    {
        return $this->getAgeAttribute();
    }

    /**
     * Retorna o grupo de idade da pessoa
     * Método auxiliar para compatibilidade
     * 
     * @return string|null
     */
    public function ageGroup(): ?string
    {
        return $this->ageGroupLabel();
    }
}
