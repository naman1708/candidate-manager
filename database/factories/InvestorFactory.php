<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class InvestorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fn = fake()->firstName();
        $ln = fake()->lastName();
        $fulln = $fn . ' '. $ln;

        return [
            'title' => fake()->randomElement(['Mr' , 'Ms', 'Miss', 'Mrs', 'Other']),
            'first_name' => $fn,
            'last_name' => $ln,
            // 'gender' => fake()->numberBetween(0,2), // 0 - male , 1 - female , 2 - others
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->postcode(),
            'adhaar' => fake()->numerify('#### #### #### ####'),
            'pan' => fake()->bothify('?????####?'),
            'bank_name' => fake()->randomElement(['ICICI Bank', 'HDFC Bank', 'IDFC First Bank', 'Axis Bank', 'Canara Bank','SBI']),
            'bank_account_holder_name' => $fulln,
            'bank_account_no' =>fake()->numerify('#### #### ####'),
            'bank_account_type' => fake()->randomElement(['Saving', 'Current', 'Other']),
            'bank_ifsc' =>  fake()->bothify('????-####'),
            'bank_branch_name' => fake()->randomElement(['Branch1','branch2','Branch3','Branch4']),
            'bank_remarks' => fake()->text(50) 

        ];
    }
}
