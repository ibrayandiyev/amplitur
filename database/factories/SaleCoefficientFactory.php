<?php

namespace Database\Factories;

use App\Models\SaleCoefficient;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleCoefficientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleCoefficient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => '1%',
            'value' => 1.01010,
            'is_default' => false,
        ];
    }
}
