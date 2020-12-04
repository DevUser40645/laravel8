<?php

namespace App\Jobs\GeneralCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePointsJob extends AbstractJob
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$f = 1 / 0;
        parent::handle();
    }
}
