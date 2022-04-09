<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contactable_type' => Company::class,
            'name' => $this->faker->name,
            'responsible' => $this->faker->name,
            'value' => $this->faker->phoneNumber,
            'type' => 'residential',
            'is_primary' => true,
        ];
    }
}
