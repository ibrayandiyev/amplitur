<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SaleCoefficient;
use Illuminate\Database\Seeder;

class SaleCoefficientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SaleCoefficient::factory()->create([
            'is_default' => true,
        ]);
    }
}
