<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreEmailAttachments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $files;

    /**
     * Create a new job instance.
     *
     * @param $files
     */
    public function __construct($files)
    {
        $this->files = $files;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $stored_files = [];

        if (count($this->files) !== 0) {
            foreach ($this->files as $file) {
                $file_path = $file->store('attachments', 'public');

                $stored_files[] = [
                    'path' => $file_path,
                    'size' => $file->getSize(),
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType()
                ];
            }
        }

        return $stored_files;
    }
}
