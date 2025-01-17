<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = \App\Models\User::all()->pluck('id')->toArray(); //get users id

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'created_by' => fake()->randomElement($users),
        ];
    }
}
