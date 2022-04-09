<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::factory()
            ->hasAddress()
            ->hasContacts(2)
            ->count(8)
            ->create();
    }
}
