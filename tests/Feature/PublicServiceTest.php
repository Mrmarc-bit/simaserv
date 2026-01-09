<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_view_home_page()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Lacak Service');
    }

    public function test_public_user_can_submit_service_request()
    {
        Mail::fake();

        $data = [
            'customer_name' => 'John Doe',
            'phone' => '081234567890',
            'email' => 'john@example.com',
            'device' => 'Laptop Asus ROG',
            'quantity' => 1,
            'complaint' => 'Mati total, tidak mau nyala',
        ];

        $response = $this->post(route('queue.store'), $data);

        $this->assertDatabaseHas('services', [
            'customer_name' => 'John Doe',
            'device' => 'Laptop Asus ROG',
            'status' => 'MENUNGGU',
            'queue_number' => 1,
        ]);

        $service = Service::where('email', 'john@example.com')->first();
        
        $response->assertRedirect(route('public.ticket.show', $service->ticket_code));
        $response->assertSessionHas('success');

        // Check format ticket code
        $this->assertStringStartsWith('SRV-', $service->ticket_code);
    }

    public function test_public_user_cannot_submit_invalid_data()
    {
        $response = $this->post(route('queue.store'), [
            'customer_name' => '', // Required
        ]);

        $response->assertSessionHasErrors(['customer_name']);
        $this->assertDatabaseCount('services', 0);
    }

    public function test_public_user_can_view_ticket_status()
    {
        $service = Service::factory()->create();

        $response = $this->get(route('public.ticket.show', $service->ticket_code));

        $response->assertStatus(200);
        $response->assertSee($service->ticket_code);
        $response->assertSee($service->customer_name);
    }
}
