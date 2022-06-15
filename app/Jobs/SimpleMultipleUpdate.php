<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimpleMultipleUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model, $ids, $attributes;

    /**
     * Create a new job instance.
     *
     * @param Model $model
     * @param array $ids
     * @param array $attributes
     */
    public function __construct(Model $model, array $ids, array $attributes)
    {
        $this->ids = $ids;
        $this->model = $model;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->model->whereIn('id', $this->ids)->get()->each(function ($item) {
            $item->update($this->attributes);
        });
    }
}
