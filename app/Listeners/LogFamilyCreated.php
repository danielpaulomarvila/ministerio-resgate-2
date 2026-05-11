<?php

namespace App\Listeners;

use App\Events\FamilyCreated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando uma família é criada
 */
class LogFamilyCreated
{
    /**
     * Handle the event
     */
    public function handle(FamilyCreated $event): void
    {
        $userId = $event->createdByUserId ?? Auth::id();

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'family_created',
            'description' => "Família criada: {$event->family->name}",
            'related_model_type' => 'Family',
            'related_model_id' => $event->family->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
