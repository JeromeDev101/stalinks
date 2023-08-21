<?php

namespace App\Providers;

use App\Models\User;
use App\Events\BuyEvent;
use App\Models\Backlink;
use App\Models\ExtDomain;
use App\Models\Registration;
use App\Events\AddWalletEvent;
use App\Listeners\BuyListener;
use App\Events\NewArticleEvent;
use App\Events\SellerPaidEvent;
use App\Events\WriterPaidEvent;
use App\Observers\UserObserver;
use App\Events\ArticleDoneEvent;
use App\Events\BacklinkLiveEvent;
use App\Events\BuyerDebitedEvent;
use App\Events\UserValidateEvent;
use App\Events\ArticleCreatedEvent;
use App\Observers\BacklinkObserver;
use App\Events\UserUnvalidatedEvent;
use App\Listeners\AddwalletListener;
use App\Observers\ExtDomainObserver;
use App\Listeners\NewArticleListener;
use App\Listeners\SellerPaidListener;
use App\Listeners\WriterPaidListener;
use Illuminate\Support\Facades\Event;
use App\Listeners\ArticleDoneListener;
use Illuminate\Auth\Events\Registered;
use App\Events\BacklinkLiveSellerEvent;
use App\Events\BacklinkLiveWriterEvent;
use App\Events\BillingReuploadDocEvent;
use App\Events\SellerConfirmationEvent;
use App\Listeners\BacklinkLiveListener;
use App\Listeners\UserValidateListener;
use App\Observers\RegistrationObserver;
use App\Events\SellerReceivesOrderEvent;
use App\Events\TeamInChargeUpdatedEvent;
use App\Events\WriterExamProcessedEvent;
use App\Listeners\BuyerDebittedListener;
use App\Events\LinkInjectionRequestEvent;
use App\Listeners\ArticleCreatedListener;
use App\Events\BacklinkStatusChangedEvent;
use App\Listeners\UserUnvalidatedListener;
use App\Events\RefundRequestProcessedEvent;
use App\Listeners\BacklinkLiveSellerListener;
use App\Listeners\BacklinkLiveWriterListener;
use App\Listeners\BillingReuploadDocListener;
use App\Listeners\SellerConfirmationListener;
use App\Listeners\SellerReceivesOrderListener;
use App\Listeners\TeamInChargeUpdatedListener;
use App\Listeners\WriterExamProcessedListener;
use App\Listeners\LinkInjectionRequestListener;
use App\Events\SellerConfirmedPendingOrderEvent;
use App\Listeners\BacklinkStatusChangedListener;
use App\Listeners\RefundRequestProcessedListener;
use App\Listeners\SellerConfirmedPendingOrderListener;
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

        AddWalletEvent::class => [
            AddwalletListener::class
        ],

        'App\Events\ExtDomainStatusUpdateEvent' => [
            'App\Listerners\ExtDomainStatusUpdateEventListener'
        ],

        BuyEvent::class => [
            BuyListener::class
        ],

        BacklinkLiveEvent::class => [
            BacklinkLiveListener::class
        ],

        SellerConfirmationEvent::class => [
            SellerConfirmationListener::class
        ],

        BacklinkLiveSellerEvent::class => [
            BacklinkLiveSellerListener::class
        ],

        BacklinkLiveWriterEvent::class => [
            BacklinkLiveWriterListener::class
        ],

        BuyerDebitedEvent::class => [
            BuyerDebittedListener::class
        ],

        SellerReceivesOrderEvent::class => [
            SellerReceivesOrderListener::class
        ],

        BacklinkStatusChangedEvent::class => [
            BacklinkStatusChangedListener::class
        ],

        SellerPaidEvent::class => [
            SellerPaidListener::class
        ],

        NewArticleEvent::class => [
            NewArticleListener::class
        ],

        ArticleDoneEvent::class => [
            ArticleDoneListener::class
        ],

        WriterPaidEvent::class => [
            WriterPaidListener::class
        ],

        UserValidateEvent::class => [
            UserValidateListener::class
        ],

        TeamInChargeUpdatedEvent::class => [
            TeamInChargeUpdatedListener::class
        ],

        UserUnvalidatedEvent::class => [
            UserUnvalidatedListener::class
        ],

        SellerConfirmedPendingOrderEvent::class => [
            SellerConfirmedPendingOrderListener::class
        ],

        WriterExamProcessedEvent::class => [
            WriterExamProcessedListener::class
        ],

        BillingReuploadDocEvent::class => [
            BillingReuploadDocListener::class
        ],

        RefundRequestProcessedEvent::class => [
            RefundRequestProcessedListener::class
        ],

        ArticleCreatedEvent::class => [
            ArticleCreatedListener::class
        ],

        LinkInjectionRequestEvent::class => [
            LinkInjectionRequestListener::class
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

        Backlink::observe(BacklinkObserver::class);
        Registration::observe(RegistrationObserver::class);
        ExtDomain::observe(ExtDomainObserver::class);
    }
}
