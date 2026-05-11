<?php

namespace App\Listeners;

use App\Events\ImportantDataChanged;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar alterações de dados sensíveis
 */
class LogImportantDataChanged
{
    /**
     * Handle the event
     */
    public function handle(ImportantDataChanged $event): void
    {
        $userId = $event->userId ?? Auth::id();

        // Converter valores para string para log
        $oldValue = is_array($event->oldValue) || is_object($event->oldValue) 
            ? json_encode($event->oldValue) 
            : (string) $event->oldValue;
        
        $newValue = is_array($event->newValue) || is_object($event->newValue) 
            ? json_encode($event->newValue) 
            : (string) $event->newValue;

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'important_data_changed',
            'description' => "Dado sensível alterado em {$event->dataType} #{$event->recordId}: {$event->field}",
            'details' => json_encode([
                'field' => $event->field,
                'old_value' => $oldValue,
                'new_value' => $newValue,
            ]),
            'related_model_type' => $event->dataType,
            'related_model_id' => $event->recordId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
