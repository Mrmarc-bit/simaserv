<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_services()
    {
        $response = $this->get(route('admin.services.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_services()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.services.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Services Management');
    }

    public function test_admin_can_update_service_status()
    {
        $admin = User::factory()->create();
        $service = Service::factory()->create(['status' => 'MENUNGGU']);

        $response = $this->actingAs($admin)->put(route('admin.services.updateStatus', $service->id), [
            'status' => 'DIPERBAIKI'
        ]);

        $response->assertRedirect();
        $this->assertEquals('DIPERBAIKI', $service->fresh()->status);
    }

    public function test_admin_can_add_item_to_service()
    {
        $admin = User::factory()->create();
        $service = Service::factory()->create(['total_price' => 0]);

        // Add Item
        $response = $this->actingAs($admin)->post(route('admin.services.addItem', $service->id), [
            'item_name' => 'Ganti LCD',
            'price' => 1500000,
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('service_items', [
            'service_id' => $service->id,
            'item_name' => 'Ganti LCD',
            'price' => 1500000,
        ]);

        // Verify total price recalculated
        $this->assertEquals(1500000, $service->fresh()->total_price);
    }
}
