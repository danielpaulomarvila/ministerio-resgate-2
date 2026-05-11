<?php

namespace App\Listeners;

use App\Events\PersonCreated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando uma pessoa é criada
 */
class LogPersonCreated
{
    /**
     * Handle the event
     */
    public function handle(PersonCreated $event): void
    {
        $userId = $event->createdByUserId ?? Auth::id();

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'person_created',
            'description' => "Pessoa criada: {$event->person->full_name}",
            'related_model_type' => 'Person',
            'related_model_id' => $event->person->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
