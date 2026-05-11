<?php

namespace App\Events;

use App\Models\MemberProfile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um perfil de membro é atualizado
 * 
 * Usos:
 * - Registrar log de atividade
 * - Notificar Secretaria sobre alterações de membresia
 */
class MemberProfileUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * O perfil de membro atualizado
     */
    public MemberProfile $memberProfile;

    /**
     * O usuário que atualizou
     */
    public ?int $updatedByUserId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(MemberProfile $memberProfile, ?int $updatedByUserId = null)
    {
        $this->memberProfile = $memberProfile;
        $this->updatedByUserId = $updatedByUserId;
    }
}
