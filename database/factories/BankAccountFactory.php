<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider_id' => Provider::factory()->create()->id,
            'company_id' => Company::factory()->create()->id,
            'currency' => Currency::REAL,
            'bank' => '001',
            'agency' => $this->faker->numberBetween(1000, 9999),
            'account_type' => 'current',
            'account_number' => $this->faker->numberBetween(1000, 9999),
        ];
    }
}
