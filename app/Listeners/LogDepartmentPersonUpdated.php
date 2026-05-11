<?php

namespace App\Listeners;

use App\Events\DepartmentPersonUpdated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando o vínculo de uma pessoa com departamento é atualizado
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 */
class LogDepartmentPersonUpdated
{
    /**
     * Handle the event
     */
    public function handle(DepartmentPersonUpdated $event): void
    {
        ActivityLog::create([
            'action' => 'department_person_updated',
            'description' => "Vínculo de '{$event->person->full_name}' no departamento '{$event->department->name}' foi atualizado (função: '{$event->departmentPerson->role}')",
            'model_type' => DepartmentPerson::class,
            'model_id' => $event->departmentPerson->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $event->oldData ? json_encode($event->oldData) : null,
        ]);
    }
}
