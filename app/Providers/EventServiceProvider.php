<?php

namespace App\Providers;

use App\Events\DepartmentCreated;
use App\Events\DepartmentLeaderChanged;
use App\Events\DepartmentPersonAttached;
use App\Events\DepartmentPersonRemoved;
use App\Events\DepartmentPersonUpdated;
use App\Events\DepartmentUpdated;
use App\Listeners\LogDepartmentCreated;
use App\Listeners\LogDepartmentLeaderChanged;
use App\Listeners\LogDepartmentPersonAttached;
use App\Listeners\LogDepartmentPersonRemoved;
use App\Listeners\LogDepartmentPersonUpdated;
use App\Listeners\LogDepartmentUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Events de Departamentos - Etapa 10
        DepartmentCreated::class => [
            LogDepartmentCreated::class,
        ],
        
        DepartmentUpdated::class => [
            LogDepartmentUpdated::class,
        ],
        
        DepartmentPersonAttached::class => [
            LogDepartmentPersonAttached::class,
        ],
        
        DepartmentPersonUpdated::class => [
            LogDepartmentPersonUpdated::class,
        ],
        
        DepartmentPersonRemoved::class => [
            LogDepartmentPersonRemoved::class,
        ],
        
        DepartmentLeaderChanged::class => [
            LogDepartmentLeaderChanged::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
