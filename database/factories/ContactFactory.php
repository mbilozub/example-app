<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->email,
            'name' => fake()->firstName,
            'city' => fake()->city,
        ];
    }

    /**
     * @param UserFactory|User $factory
     */
    public function forUser($factory): self
    {
        return $this->for($factory, 'user');
    }
}
