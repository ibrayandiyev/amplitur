<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Package;
use App\Models\Provider;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

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
            'event_id' => Event::factory()->create()->id,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->add(new DateInterval('P3D')),
            'flags' => null,
        ];
    }
}
