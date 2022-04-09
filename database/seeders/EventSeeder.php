<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()
            ->count(50)
            ->create([
                'category_id' => 1,
            ]);
    }
}
