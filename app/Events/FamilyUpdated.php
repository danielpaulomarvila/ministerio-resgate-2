<?php

namespace App\Events;

use App\Models\Family;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma família é atualizada
 * 
 * Usos:
 * - Registrar log de atividade
 * - Verificar alterações sensíveis
 */
class FamilyUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * A família que foi atualizada
     */
    public Family $family;

    /**
     * O usuário que atualizou a família
     */
    public ?int $updatedByUserId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Family $family, ?int $updatedByUserId = null)
    {
        $this->family = $family;
        $this->updatedByUserId = $updatedByUserId;
    }
}
