<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivationStatus implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $is_active;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($is_active) {
        //
        $this->is_active = $is_active;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        //
    }
}
