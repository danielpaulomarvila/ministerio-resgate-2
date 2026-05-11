<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um usuário é criado
 * 
 * Usos:
 * - Registrar log de atividade
 * - Notificar Secretaria sobre novo acesso
 * - Verificar se precisa de aprovação
 */
class UserCreated
{
    use Dispatchable, SerializesModels;

    /**
     * O usuário criado
     */
    public User $user;

    /**
     * O usuário que criou (se foi criado por outro usuário)
     */
    public ?int $createdByUserId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(User $user, ?int $createdByUserId = null)
    {
        $this->user = $user;
        $this->createdByUserId = $createdByUserId;
    }
}
