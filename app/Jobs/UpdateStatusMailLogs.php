<?php

namespace App\Jobs;

use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mailgun\Mailgun;

class UpdateStatusMailLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mg;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mg = Mailgun::create(config('gun.mail_api'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $opened = [];
        $failed = [];
        $rejected = [];
        $reported = [];
        $delivered = [];

        $temp_count = 0;
        $all_array = [];
        $message_id_array = [];

        // get last 5 days (current log retention is 5 days), with status 0, 250, 552
        $start_date = Carbon::now()->subDays(5)->format('Y-m-d');

        Reply::whereIn('status_code', [0, 250, 552])
            ->where('is_sent', 1)
            ->where('created_at', '>=', $start_date)
            ->orderBy('id', 'desc')
            ->chunk(50, function ($replies) use(&$message_id_array) {
                $message_ids = implode(' OR ', $replies->pluck('message_id')->all());

                $message_id_array[] = $message_ids;
            });

        // iterate through message ids
        foreach ($message_id_array as &$item) {
            $arr_items = $this->mg->events()->get('stalinks.com', [
                'message-id' => $item,
                'limit' => 300,
                'event' => 'rejected OR failed OR delivered OR opened OR complained',
                'tags' => 'test1'
            ]);

            $returned_items = $this->getEmailStatusItems($arr_items);

            $all_array = array_merge($all_array, $returned_items);

            $temp_count = $temp_count + count($returned_items);
        }

        foreach ($all_array as $key => $value) {

            // get rejected
            if ($value->getEvent() === 'rejected') {
                $message = $value->getMessage();
                $id = $this->messageIdExtractor($message);

                if (!in_array($id, $rejected)) {
                    $rejected[] = $id;
                }
            }

            // get failed
            if ($value->getEvent() === 'failed') {
                $message = $value->getMessage();
                $id = $this->messageIdExtractor($message);

                if (!in_array($id, $failed)) {
                    $failed[] = $id;
                }
            }

            // get delivered
            if ($value->getEvent() === 'delivered') {
                $message = $value->getMessage();
                $id = $this->messageIdExtractor($message);

                if (!in_array($id, $delivered)) {
                    $delivered[] = $id;
                }
            }

            // get opened
            if ($value->getEvent() === 'opened') {
                $message = $value->getMessage();
                $id = $this->messageIdExtractor($message);

                if (!in_array($id, $opened)) {
                    $opened[] = $id;
                }
            }

            // get reported
            if ($value->getEvent() === 'complained') {
                $message = $value->getMessage();
                $id = $this->messageIdExtractor($message);

                if (!in_array($id, $reported)) {
                    $reported[] = $id;
                }
            }
        }

        // get failed items that are not on delivered array
        $failed_temp = [];

        foreach ($failed as $key => $fail) {
            if (!in_array($fail, $delivered)) {
                $failed_temp[] = $fail;
            }
        }

        // update rejected
        if (count($rejected) !== 0) {
            Reply::where('is_sent', 1)
                ->where('status_code', '!=', 250)
                ->whereIn('message_id', $rejected
                )->update([
                    'status_code' => 500,
                    'message_status' => 'REJECTED'
                ]);
        }

        // update failed
        if (count($failed_temp) !== 0) {
            Reply::where('is_sent', 1)
                ->where('status_code', '!=', 250)
                ->whereIn('message_id', $failed_temp)
                ->update([
                    'status_code' => 552,
                    'message_status' => 'FAILED'
                ]);
        }

        // update delivered
        if (count($delivered) !== 0) {
            Reply::where('is_sent', 1)
                ->whereIn('message_id', $delivered)
                ->update([
                    'status_code' => 250,
                    'message_status' => 'DELIVERED'
                ]);
        }

        // update opened
        if (count($opened) !== 0) {
            Reply::where('is_sent', 1)
                ->whereIn('message_id', $opened)
                ->update([
                    'status_code' => 260,
                    'message_status' => 'OPENED'
                ]);
        }

        // update reported
        if (count($reported) !== 0) {
            Reply::where('is_sent', 1)
                ->whereIn('message_id', $reported)
                ->update([
                    'status_code' => 570,
                    'message_status' => 'REPORTED'
                ]);
        }
    }

    protected function getEmailStatusItems($result)
    {
        $items = $result;
        $items_array = $items->getItems();
        $items_count = count($items->getItems());

        while($items_count !== 0) {
            $next = $this->mg->events()->nextPage($items);
            $items = $next;
            $items_count = count($items->getItems());

            if ($items_count !== 0) {
                $items_array = array_merge($items_array, $items->getItems());
            }
        }

        return $items_array;
    }

    protected function messageIdExtractor($message)
    {
        $id = '';

        if (array_key_exists('headers', $message)) {
            if (array_key_exists('message-id', $message['headers'])) {
                $id = $message['headers']['message-id'];
            }
        }

        if ($id !== '') {
            return $id;
        }
    }
}
