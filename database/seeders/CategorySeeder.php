<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create([
            'name' => 'Show',
            'slug' => 'show',
            'description' => null,
            'type' => 'event',
            'flags' => [
                'DURATION' => 'one-day',
            ],
        ]);

        Category::factory()->create([
            'name' => 'Festival',
            'slug' => 'festival',
            'description' => null,
            'type' => 'event',
            'flags' => [
                'DURATION' => 'range-date',
            ],
        ]);
    }
}
