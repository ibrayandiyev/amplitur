<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::factory()
            ->hasAddress()
            ->hasContacts(2)
            ->hasCompanies(2)
            ->create();
    }
}
