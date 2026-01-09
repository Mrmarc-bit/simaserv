<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_service()
    {
        $service = Service::factory()->create();
        $item = ServiceItem::factory()->create(['service_id' => $service->id]);

        $this->assertInstanceOf(Service::class, $item->service);
        $this->assertEquals($service->id, $item->service->id);
    }

    public function test_creating_item_updates_service_total_price()
    {
        $service = Service::factory()->create(['total_price' => 0]);

        ServiceItem::create([
            'service_id' => $service->id,
            'item_name' => 'Test Item 1',
            'price' => 150,
        ]);

        $this->assertEquals(150, $service->fresh()->total_price);
    }

    public function test_updating_item_updates_service_total_price()
    {
        $service = Service::factory()->create(['total_price' => 0]);
        $item = ServiceItem::create([
            'service_id' => $service->id,
            'item_name' => 'Test Item 1',
            'price' => 100,
        ]);

        $this->assertEquals(100, $service->fresh()->total_price);

        $item->update(['price' => 200]);

        $this->assertEquals(200, $service->fresh()->total_price);
    }

    public function test_deleting_item_updates_service_total_price()
    {
        $service = Service::factory()->create(['total_price' => 0]);
        $item = ServiceItem::create([
            'service_id' => $service->id,
            'item_name' => 'Test Item 1',
            'price' => 100,
        ]);

        $this->assertEquals(100, $service->fresh()->total_price);

        $item->delete();

        $this->assertEquals(0, $service->fresh()->total_price);
    }
}
