<?php

namespace App\Listeners;

use App\Events\ChildApproachingJuniorAgeDetected;
use App\Models\SystemAlert;
use Carbon\Carbon;

/**
 * Listener para criar alerta quando criança está próxima de completar 11 anos
 */
class CreateAlertForChildApproaching11
{
    /**
     * Handle the event
     */
    public function handle(ChildApproachingJuniorAgeDetected $event): void
    {
        // Verificar se já existe alerta pendente para esta pessoa
        $existingAlert = SystemAlert::where('type', 'child_turning_11')
            ->where('related_person_id', $event->person->id)
            ->where('status', 'pending')
            ->first();

        // Se já existe alerta pendente, não duplicar
        if ($existingAlert) {
            return;
        }

        // Criar novo alerta
        SystemAlert::create([
            'type' => 'child_turning_11',
            'title' => "Criança completando 11 anos em breve",
            'message' => "{$event->person->full_name} ({$event->person->ageGroupLabel()}) completará 11 anos em {$event->turning11Date->format('d/m/Y')} ({$event->daysUntil11} dias). Verificar transição para Júnior/Resgatados.",
            'related_person_id' => $event->person->id,
            'severity' => $event->daysUntil11 <= 30 ? 'high' : 'medium',
            'status' => 'pending',
            'due_date' => $event->turning11Date->addDays(7), // Alerta válido por 7 dias após completar 11
        ]);
    }
}
