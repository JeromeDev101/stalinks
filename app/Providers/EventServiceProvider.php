<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        'App\Events\NotificationEvent' => [
          'App\Listerners\NotificationEventListener',
        ],
        'App\Events\ArticleEvent' => [
          'App\Listerners\ArticleEventListener',
        ],
        'App\Events\BacklinkLiveEvent' => [
          'App\Listerners\BacklinkLiveEventListener',
        ],
        'App\Events\ExtDomainStatusUpdateEvent' => [
            'App\Listerners\ExtDomainStatusUpdateEventListener'
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

        //
    }
}
