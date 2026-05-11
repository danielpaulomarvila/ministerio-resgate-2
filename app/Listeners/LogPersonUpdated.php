<?php

namespace App\Listeners;

use App\Events\PersonUpdated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando uma pessoa é atualizada
 */
class LogPersonUpdated
{
    /**
     * Handle the event
     */
    public function handle(PersonUpdated $event): void
    {
        $userId = $event->updatedByUserId ?? Auth::id();

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'person_updated',
            'description' => "Pessoa atualizada: {$event->person->full_name}",
            'related_model_type' => 'Person',
            'related_model_id' => $event->person->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
