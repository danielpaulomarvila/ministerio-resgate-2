<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando um usuário é criado
 */
class LogUserCreated
{
    /**
     * Handle the event
     */
    public function handle(UserCreated $event): void
    {
        $userId = $event->createdByUserId ?? Auth::id();

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'user_created',
            'description' => "Usuário criado: {$event->user->email}",
            'related_model_type' => 'User',
            'related_model_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
