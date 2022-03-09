<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Models\Module;
use App\Models\ModuleStep;
use App\Models\Language;
use App\Observers\ModuleObserver;
use App\Observers\ModuleStepObserver;
use App\Observers\LanguageObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Module :: observe(ModuleObserver :: class);
        ModuleStep :: observe(ModuleStepObserver :: class);
        Language :: observe(LanguageObserver :: class);
    }
}
