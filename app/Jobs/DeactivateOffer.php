<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
//use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

use function Symfony\Component\String\b;

class DeactivateOffer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $offer;

    public function __construct($offer)
    {
        $this->offer=$offer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->offer->update(['active'=>false]);
    }
}
