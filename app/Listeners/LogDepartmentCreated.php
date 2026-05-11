<?php

namespace App\Listeners;

use App\Events\DepartmentCreated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando um departamento é criado
 */
class LogDepartmentCreated
{
    /**
     * Handle the event
     */
    public function handle(DepartmentCreated $event): void
    {
        ActivityLog::create([
            'action' => 'department_created',
            'description' => "Departamento '{$event->department->name}' foi criado",
            'model_type' => Department::class,
            'model_id' => $event->department->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
