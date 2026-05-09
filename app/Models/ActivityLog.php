<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para auditoria e histórico de ações
 * Usado para rastrear alterações importantes futuramente
 * Essencial para segurança e compliance
 */
class ActivityLog extends Model
{
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    // Cast de tipos de dados
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Relacionamento: Um log de atividade pertence a um usuário
     * Usuário que realizou a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se é um log de criação
     */
    public function isCreation(): bool
    {
        return $this->action === 'create';
    }

    /**
     * Verifica se é um log de atualização
     */
    public function isUpdate(): bool
    {
        return $this->action === 'update';
    }

    /**
     * Verifica se é um log de exclusão
     */
    public function isDeletion(): bool
    {
        return $this->action === 'delete';
    }

    /**
     * Verifica se é um log de login
     */
    public function isLogin(): bool
    {
        return $this->action === 'login';
    }
}
