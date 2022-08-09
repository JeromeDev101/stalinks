<?php

namespace App\Jobs;

use App\Models\Tool;
use App\Models\User;
use App\Notifications\ExpiringTools;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendExpiringToolsNotification implements ShouldQueue
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
        $now = Carbon::now()->format('Y-m-d');
        $expiring = [];
        $days = [30, 15, 7, 3, 0];

        $users = User::whereIn('role_id', [1, 3, 8])->where('status', 'active')->where('isOurs', 0)->get();

        $tools = Tool::selectRaw('DATEDIFF(expired_at, "' . $now . '") AS days, name, expired_at, id, is_expired, is_notified')
            ->where('is_expired', '!=', 1)
            ->whereNotNull('expired_at')
            ->get()
            ->toArray();

        if (count($tools)) {
            // get tools by expiring date day
            foreach ($days as $day) {
                $expiring[$day] = array_filter($tools, function ($el) use ($day) {
                    return ($el['days'] == $day && $el['is_notified'] !== $day);
                });
            }

            foreach ($expiring as $key => $expire) {
                if (count($expire)) {
                    // send notifications for team
                    Notification::send($users, new ExpiringTools($expire, $key));

                    // update is_notified in tools table
                    $ids = array_column($expire, 'id');

                    Tool::whereIn('id', $ids)->update([
                        'is_notified' => $key,
                        'is_expired' => $key === 0 ? 1 : 0
                    ]);
                }
            }
        }
    }
}
