<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> $this->faker->name(),
            'email'=> $this->faker->email,
            'phone'=> $this->faker->e164PhoneNumber,
            'department_id'=> Department::inRandomOrder()->pluck('id')->first()
            
        ];
    }
}
