<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GetAhrefMonthlyValid;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;

class GetAhrefMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahref:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get ahref for valid urls monthly';

    protected $publisherRepository;
    protected $configRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PublisherRepositoryInterface $publisherRepository, ConfigRepositoryInterface $configRepository) {
        parent::__construct();
        $this->publisherRepository = $publisherRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start get ahref of valid urls');

        // Dispatch the job with the resolved dependencies
        GetAhrefMonthlyValid::dispatch($this->publisherRepository, $this->configRepository);
    }
}
