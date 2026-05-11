<?php

namespace App\Listeners;

use App\Events\DepartmentUpdated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando um departamento é atualizado
 */
class LogDepartmentUpdated
{
    /**
     * Handle the event
     */
    public function handle(DepartmentUpdated $event): void
    {
        ActivityLog::create([
            'action' => 'department_updated',
            'description' => "Departamento '{$event->department->name}' foi atualizado",
            'model_type' => Department::class,
            'model_id' => $event->department->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $event->oldData ? json_encode($event->oldData) : null,
        ]);
    }
}
