<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_code' => $this->faker->unique()->bothify('TC-####'),
            'queue_number' => $this->faker->numberBetween(1, 100),
            'customer_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'device' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'complaint' => $this->faker->sentence(),
            'damage_note' => $this->faker->optional()->sentence(),
            'status' => 'MENUNGGU',
            'payment_method' => 'CASH',
            'payment_status' => 'BELUM_LUNAS',
            'total_price' => 0,
        ];
    }
}
