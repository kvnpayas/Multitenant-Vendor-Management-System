<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'vendor_id' => Vendor::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => 'pending',
        ];

    }
}
