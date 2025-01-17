<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookLoan>
 */
class BookLoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $books = \App\Models\Book::all()->pluck('id')->toArray(); //get books id
        $members = \App\Models\Member::all()->pluck('id')->toArray(); //get members id
        $users = \App\Models\User::all()->pluck('id')->toArray(); // get users id

        return [
            'book_id' => fake()->randomElement($books),
            'member_id' => fake()->randomElement($members),
            'loan_date' => fake()->date(),
            'estimated_return' => fake()->date(),
            'status' => 'In Loan',
            'created_by' => fake()->randomElement($users),
        ];
    }
}
