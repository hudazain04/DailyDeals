<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            UserSeeder::class,
            CustomerSeeder::class,
            MerchantSeeder::class,
            StoreSeeder::class,
            VerificationSeeder::class,
            VerificationCodeSeeder::class,
            CategorySeeder::class,
            BranchSeeder::class,
            EmployeeSeeder::class,
            NumberSeeder::class,
            ProductSeeder::class,
            CategoryRequestSeeder::class,
            RateSeeder::class,
            FavoriteSeeder::class,
            NotificationSeeder::class,
            NotificationUserSeeder::class,
            OfferSeeder::class,
            CommentSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ImageSeeder::class,
            ProductInfoSeeder::class,
            AdvertisementSeeder::class,
            ComplaintSeeder::class,
            PercentageOfferSeeder::class,
            DiscountOfferSeeder::class,
            GiftOfferSeeder::class,
            ExtraOfferSeeder::class,
            ConversationSeeder::class,
            QRSeeder::class,
            FAQSeeder::class,
            BranchProductSeeder::class,
            OfferProductSeeder::class,
            OfferBranchSeeder::class,
            NotifiedSeeder::class,
            TypeOfOfferRequestSeeder::class,
            MessageSeeder::class,

        ]);
    }
}
