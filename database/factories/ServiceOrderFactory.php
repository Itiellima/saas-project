<?php

namespace Database\Factories;

use App\Models\ServiceOrder;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'vehicle_plate' => strtoupper(fake()->bothify('???#?##')),
            'vehicle_model' => fake()->word(),
            'vehicle_km' => fake()->numberBetween(0, 200000),
            'vehicle_enter' => now(),
            'status' => 'open',
        ];
    }
}
