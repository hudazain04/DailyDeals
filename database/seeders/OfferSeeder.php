<?php

namespace Database\Seeders;

use App\Models\Discount_Offer;
use App\Models\Extra_Offer;
use App\Models\Gift_Offer;
use App\Models\Offer;
use App\Models\Percentage_Offer;
use App\Types\OfferType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::factory()->count(10)->create();
        Offer::factory()
            ->count(10)
            ->create()
            ->each(function ($offer) {
                switch ($offer->type) {
                    case OfferType::Percentage:
                        Percentage_Offer::factory()->create(['offer_id' => $offer->id]);
                        break;

                    case OfferType::Discount:
                        Discount_Offer::factory()->create(['offer_id' => $offer->id]);
                        break;

                    case OfferType::Extra:
                        Extra_Offer::factory()->create(['offer_id' => $offer->id]);
                        break;

                    case OfferType::Gift:
                        Gift_Offer::factory()->create(['offer_id' => $offer->id]);
                        break;
                }
            });
    }
}
