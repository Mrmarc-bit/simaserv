<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_items_relationship()
    {
        /** @var \App\Models\Service $service */
        $service = Service::factory()->create();
        /** @var \App\Models\ServiceItem $item */
        $item = ServiceItem::factory()->create(['service_id' => $service->id]);

        $this->assertTrue($service->items->contains($item));
        $this->assertEquals(1, $service->items->count());
    }

    public function test_it_can_recalculate_total_price()
    {
        $service = Service::factory()->create(['total_price' => 0]);

        ServiceItem::factory()->create([
            'service_id' => $service->id,
            'price' => 100,
        ]);

        ServiceItem::factory()->create([
            'service_id' => $service->id,
            'price' => 200,
        ]);

        $service->recalculateTotal();

        $this->assertEquals(300, $service->fresh()->total_price);
    }
}
