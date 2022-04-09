<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::factory()
            ->for(Provider::factory()
                    ->hasAddress()
                    ->hasContacts(2)
                    ->hasCompanies(2))
            ->hasAddress()
            ->count(5)
            ->create();
    }
}
