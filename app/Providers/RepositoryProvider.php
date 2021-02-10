<?php

namespace App\Providers;

use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use App\Repositories\Contracts\NotificationInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CountryRepository;
use App\Repositories\HostingRepository;
use App\Repositories\ExtDomainRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\HostingRepositoryInterface;
use App\Repositories\Contracts\IntDomainRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Contracts\MailLogRepositoryInterface;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Repositories\Contracts\MailRepositoryInterface;
use App\Repositories\IntDomainRepository;
use App\Repositories\ConfigRepository;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\BackLinkRepository;
use App\Repositories\CrawlContactRepository;
use App\Repositories\RoleRepository;
use App\Repositories\LogRepository;
use App\Repositories\MailLogRepository;
use App\Repositories\DomainProviderRepository;
use App\Repositories\MailRepository;
use App\Repositories\Contracts\DomainProviderRepositoryInterface;

class RepositoryProvider extends ServiceProvider
{
    /**
     * The repository mappings for the application.
     *
     * @var array
     */
    protected $repositories = [
        UserRepositoryInterface::class => UserRepository::class,
        CountryRepositoryInterface::class => CountryRepository::class,
        ExtDomainRepositoryInterface::class => ExtDomainRepository::class,
        IntDomainRepositoryInterface::class => IntDomainRepository::class,
        BackLinkRepositoryInterface::class => BackLinkRepository::class,
        CrawlContactRepositoryInterface::class => CrawlContactRepository::class,
        HostingRepositoryInterface::class => HostingRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        LogRepositoryInterface::class => LogRepository::class,
        MailLogRepositoryInterface::class => MailLogRepository::class,
        DomainProviderRepositoryInterface::class => DomainProviderRepository::class,
        ConfigRepositoryInterface::class => ConfigRepository::class,
        MailRepositoryInterface::class => MailRepository::class,
        PublisherRepositoryInterface::class => PublisherRepository::class,
        NotificationInterface::class => NotificationRepository::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $class) {
            $this->app->singleton($interface, $class);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
