<?php

namespace App\Jobs;

use App\Mail\BuyerNewsletterEmail;
use App\Models\Publisher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendNewsletterToBuyers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get subscribed buyers email
        $subscribed_buyer_emails = $this->getSubscribedBuyersEmails();

        // get new urls for buyers
        $urls = $this->getNewUrls();

        // send mail to buyers

        if ($urls['count']) {
            foreach ($subscribed_buyer_emails as $buyer) {
                try {
                    Mail::to($buyer)->send(new BuyerNewsletterEmail($buyer, $urls));
                } catch (\Exception $e) {
                    \Log::error($e);
                }
            }
        }
    }

    /**
     * get subscribed buyers email
     *
     * @return array
     */
    protected function getSubscribedBuyersEmails() {
        return User::where('role_id', 5)
            ->where('isOurs', 1)
            ->where('status', 'active')
            ->where('is_subscribed', 1)
            ->get();
    }

    /**
     * get new urls
     *
     * @return array
     */
    protected function getNewUrls() {
        $urls = Publisher::leftJoin('users', 'publisher.user_id', '=', 'users.id')
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->whereNotNull('users.id')
            ->where('registration.account_validation', 'valid')
            ->where('publisher.valid', 'valid')
            ->where('publisher.qc_validation', 'yes')
            ->whereNotNull('publisher.href_fetched_at')
            ->where(function($query) {
                $query->whereDate('publisher.created_at', Carbon::today())
                    ->orWhereDate('publisher.updated_at', Carbon::today());
            })
            ->orderBy('publisher.id', 'DESC');

        return [
            'data' => $urls->take(5)->pluck('url')->toArray(),
            'count' => $urls->count()
        ];
    }
}
