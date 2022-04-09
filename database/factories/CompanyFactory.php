<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Enums\ProcessStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->company(),
            'legal_name' => $this->faker->company(),
            'website' => $this->faker->url,
            'logo' => $this->faker->imageUrl(),
            'registry' => $this->faker->randomNumber(6),
            'country' => $this->faker->countryCode(),
            'status' => $this->faker->randomElement([
                ProcessStatus::IN_ANALYSIS,
                ProcessStatus::ACTIVE,
                ProcessStatus::SUSPENDED,
            ]),
            'language' => $this->faker->randomElement([
                Language::PORTUGUESE,
                Language::ENGLISH,
                Language::SPANISH,
            ]),
        ];
    }
}
