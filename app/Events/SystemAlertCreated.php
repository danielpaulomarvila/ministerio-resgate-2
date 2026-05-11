<?php

namespace App\Events;

use App\Models\SystemAlert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um alerta do sistema é criado
 * 
 * Usos:
 * - Registrar log de atividade
 * - Notificar Secretaria sobre novo alerta
 */
class SystemAlertCreated
{
    use Dispatchable, SerializesModels;

    /**
     * O alerta criado
     */
    public SystemAlert $alert;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(SystemAlert $alert)
    {
        $this->alert = $alert;
    }
}
