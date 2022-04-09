<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\PersonType;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'legal_name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'birthdate' => $this->faker->dateTimeThisCentury(),
            'identity' => $this->faker->rg(false),
            'document' => $this->faker->cpf(false),
            'passport' => $this->faker->randomNumber(6),
            'registry' => $this->faker->randomNumber(6),
            'gender' => $this->faker->randomElement([
                Gender::MALE,
                Gender::FEMALE,
                Gender::OTHER,
            ]),
            'language' => $this->faker->randomElement([
                Language::PORTUGUESE,
                Language::ENGLISH,
                Language::SPANISH,
            ]),
            'username' => $this->faker->userName,
            'password' => '$2y$10$hYsM.rme08eQFgTTL7veA.vlaaSTEL/VB14sIAIs1hZGNMYyO55MO', // secret,
            'is_active' => $this->faker->boolean(),
            'is_valid' => $this->faker->boolean(),
            'is_newsletter_subscriber' => $this->faker->boolean(),
            'type' => $this->faker->randomElement([
                PersonType::FISICAL,
                PersonType::LEGAL,
            ]),
            'responsible_name' => $this->faker->name(),
            'responsible_email' => $this->faker->safeEmail(),
            'country' => $this->faker->countryCode(),
        ];
    }
}
