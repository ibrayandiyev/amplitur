<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClientSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(EventSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SaleCoefficientSeeder::class);
        // $this->call(OfferSeeder::class);
    }
}
