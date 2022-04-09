<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category' => 'national',
            'type' => 'credit',
            'name' => $this->faker->name(),
            'code' => $this->faker->slug(),
            'max_installments' => 12,
            'first_installment_billet' => true,
            'offline' => true,
            'komerci' => false,
            'rede' => true,
            'cielo' => true,
            'shopline' => false,
            'paypal' => false,
            'bradesco' => false,
        ];
    }
}
