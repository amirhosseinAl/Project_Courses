<?php

namespace Modules\User\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\Log as ModelsLog;

class SaveLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $key;
    protected $attributes_id;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $key, $attributes_id = null, $data = [])
    {
        $this->user_id       = $user_id;
        $this->key           = $key;
        $this->attributes_id = $attributes_id;
        $this->data          = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ModelsLog::create([
            'user_id'       => $this->user_id,
            'key'           => $this->key,
            'attributes_id' => $this->attributes_id,
            'data'          => json_encode($this->data),
        ]);
    }
}
