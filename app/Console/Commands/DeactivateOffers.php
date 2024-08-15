<?php

namespace App\Console\Commands;

use App\Jobs\DeactivateOffer;
use App\Models\Offer;
use Illuminate\Console\Command;

class DeactivateOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate expired offers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offer = Offer::where('active', true)
            ->where('created_at', '<=', now()->subDays('period'))
            ->get()
            ->each(function ($offer) {
                DeactivateOffer::dispatch($offer);
            });
    }

}
