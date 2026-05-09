<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'person_id', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relacionamento: Um usuário pertence a uma pessoa
     * Nem toda pessoa tem usuário (ex: crianças menores de 11 anos)
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: Um usuário pode ter múltiplos logs de atividade
     * Usado para auditoria e rastreamento de ações
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relacionamento: Um usuário pode aprovar perfis de membro
     * Quando tem permissão de Secretaria
     */
    public function approvedMemberProfiles(): HasMany
    {
        return $this->hasMany(MemberProfile::class, 'approved_by_user_id');
    }

    /**
     * Relacionamento: Um usuário pode resolver alertas do sistema
     * Quando tem permissão de Secretaria
     */
    public function resolvedAlerts(): HasMany
    {
        return $this->hasMany(SystemAlert::class, 'resolved_by_user_id');
    }

    /**
     * Verifica se o usuário está ativo no sistema
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
