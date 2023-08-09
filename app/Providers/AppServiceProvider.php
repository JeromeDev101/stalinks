<?php

namespace App\Providers;

use App\Services\CreateSurvey;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\PublisherRepository;
use App\Repositories\ConfigRepository;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Contracts\ConfigRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the PublisherRepositoryInterface to the PublisherRepository implementation
        $this->app->bind(PublisherRepositoryInterface::class, PublisherRepository::class);

        // Bind the ConfigRepositoryInterface to the ConfigRepository implementation
        $this->app->bind(ConfigRepositoryInterface::class, ConfigRepository::class);
    }

    public $singletons = [
        CreateSurvey::class => CreateSurvey::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
