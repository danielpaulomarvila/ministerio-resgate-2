<?php

namespace App\Events;

use App\Models\Family;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma família é criada
 * 
 * Usos:
 * - Registrar log de atividade
 * - Notificar Secretaria
 */
class FamilyCreated
{
    use Dispatchable, SerializesModels;

    /**
     * A família que foi criada
     */
    public Family $family;

    /**
     * O usuário que criou a família
     */
    public ?int $createdByUserId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Family $family, ?int $createdByUserId = null)
    {
        $this->family = $family;
        $this->createdByUserId = $createdByUserId;
    }
}
