<?php

namespace App\Jobs;

use App\Events\NotificationEvent;
use App\Models\Article;
use App\Models\Backlink;
use App\Models\User;
use App\Repositories\BackLinkRepository;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\PublisherRepository;
use App\Repositories\Traits\BuyTrait;
use App\Repositories\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BuyLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationTrait, BuyTrait;

    protected $request;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @param PublisherRepositoryInterface $publisherRepository
     * @param BackLinkRepositoryInterface  $backLinkRepository
     * @return void
     */
    public function handle(PublisherRepositoryInterface $publisherRepository)
    {
        //Fetch valid publisher
        $publisher = $publisherRepository->findOne([
            'url' => $this->request['url'],
            'valid' => 'valid'
        ]);

        //Create or update buyer_purchased record
        $this->updateStatus('Purchased', $publisher->id, $this->userId);

        //Create backlinks record
        $backlink = Backlink::create([
            'price'          => $publisher->price,
            'url_advertiser' => $this->request['url_advertiser'],
            'anchor_text'    => $this->request['anchor_text'],
            'link'           => $this->request['link'],
            'publisher_id'   => $publisher->id,
            'user_id'        => $this->userId,
            'status'         => 'Processing',
            'date_process'   => date('Y-m-d'),
            'ext_domain_id'  => 0,
            'int_domain_id'  => 0,
        ]);

        //Notify buyer
        $this->buyEventNotification($this->userId, $backlink);

        //Notify Seller
        $this->sellerReceivesOrderNotification($publisher->user_id, $backlink);

        //Create new article if URL doesnt include article
        if( isset($backlink->publisher->inc_article) &&  strtolower($backlink->publisher->inc_article) == "no"){
            Article::create([
                'id_backlink' => $backlink->id,
                'id_language' => $backlink->publisher->language_id,
            ]);
            $users = User::where('status','active')->where('role_id',4)->get();
            foreach($users as $user)
            {
                event(new NotificationEvent("New Article to be write today!", $user->id));
            }
        }
    }

    public function fail($exception = null)
    {
        \Log::error($exception);
    }
}
