<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\AccessStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'password' => '$2y$10$hYsM.rme08eQFgTTL7veA.vlaaSTEL/VB14sIAIs1hZGNMYyO55MO', // secret
            'is_active' => $this->faker->boolean(),
            'status' => $this->faker->randomElement([
                AccessStatus::ACTIVE,
                AccessStatus::SUSPENDED,
                AccessStatus::BANNED,
                AccessStatus::PENDING,
            ]),
            'type' => $this->faker->randomElement([
                UserType::MASTER,
                UserType::ADMIN,
                UserType::MANAGER,
            ]),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ];
    }
}
