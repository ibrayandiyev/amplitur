<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Provider;
use App\Models\SaleCoefficient;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $provider = Provider::factory()->create();
        $startsAt = $this->faker->dateTimeThisYear;

        return [
            'provider_id' => $provider->id,
            'company_id' => Company::factory()->create(['provider_id' => $provider->id])->id,
            'package_id' => Package::factory()->create()->id,
            'sale_coefficient_id' => SaleCoefficient::factory()->create()->id,
            'type' => $this->faker->randomElement(OfferType::toArray()),
            'expires_at' => $startsAt->add(new DateInterval('P2D')),
            'ip' => $this->faker->ipv4,
            'currency' => $this->faker->randomElement(Currency::toArray()),
            'status' => $this->faker->randomElement(ProcessStatus::toArray()),
            'flags' => null,
        ];
    }
}
