<?php

namespace App\Providers;

use App\Models\AnimatedHeaderStep0;
use App\Models\EventStep0;
use App\Models\PublicationsStep0;
use App\Observers\AnimatedHeaderStep0Observer;
use App\Observers\EventStep0Observer;
use App\Observers\PublicationsStep0Observer;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Models\Module;
use App\Observers\ModuleObserver;
use App\Models\ModuleStep;
use App\Observers\ModuleStepObserver;
use App\Models\Language;
use App\Observers\LanguageObserver;
use App\Models\PhotoGalleryStep1;
use App\Observers\PhotoGalleryStep1Observer;
use App\Models\Page;
use App\Observers\PageObserver;
use App\Models\Partner;
use App\Observers\PartnerObserver;

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

        Module::observe(ModuleObserver::class);
        ModuleStep::observe(ModuleStepObserver::class);
        Language::observe(LanguageObserver::class);
        Page::observe(PageObserver::class);
        Partner::observe(PartnerObserver::class);
        AnimatedHeaderStep0::observe(AnimatedHeaderStep0Observer::class);
        PublicationsStep0::observe(PublicationsStep0Observer::class);
        EventStep0::observe(EventStep0Observer::class);
        
    }
}
