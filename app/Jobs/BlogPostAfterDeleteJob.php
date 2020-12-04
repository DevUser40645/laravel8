<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BlogPostAfterDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $blogPostId;

    /**
     * Create a new job instance.
     *
     * @param $blogPostId
     *
     * @return void
     */
    public function __construct($blogPostId)
    {
        $this->blogPostId = $blogPostId;
        $this->onQueue('deleted');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logs()->warning("Post in blog was deleted [{$this->blogPostId}]");
    }
}
