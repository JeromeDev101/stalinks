<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class DeleteEmailAttachments implements ShouldQueue
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
        collect(Storage::disk('public')->listContents('attachments', true))
            ->each(function($file) {
                if ($file['type'] == 'file' && $file['timestamp'] < now()->subMonth()->getTimestamp()) {
                    Storage::disk('public')->delete($file['path']);
                }
            });
    }
}
