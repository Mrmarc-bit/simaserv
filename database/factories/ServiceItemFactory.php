<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ServiceItem;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceItem>
 */
class ServiceItemFactory extends Factory
{
    protected $model = ServiceItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'item_name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
