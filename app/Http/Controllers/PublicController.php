<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'device' => 'required|string|max:255',
            'complaint' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        // Generate Queue Number (Set to 0 since not used anymore)
        $queueNumber = 0;

        // Generate Ticket Code: SRV-250001
        $year = Carbon::now()->format('y');
        $countYear = Service::whereYear('created_at', Carbon::now()->year)->count() + 1;
        $ticketCode = 'SRV-' . $year . sprintf('%04d', $countYear);

        while(Service::where('ticket_code', $ticketCode)->exists()) {
             $countYear++;
             $ticketCode = 'SRV-' . $year . sprintf('%04d', $countYear);
        }

        $service = Service::create([
            'ticket_code' => $ticketCode,
            'queue_number' => $queueNumber,
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'device' => $request->device,
            'quantity' => $request->quantity,
            'complaint' => $request->complaint . ($request->accessories ? "\n\nKelengkapan: " . implode(', ', $request->accessories) : "\n\nKelengkapan: Unit Only"),
            'status' => 'MENUNGGU',
        ]);

        // Send Email if exists
        if($service->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($service->email)->send(new \App\Mail\NewTicketMail($service));
            } catch (\Exception $e) {
                // Log error but don't stop the flow
                \Illuminate\Support\Facades\Log::error('Mail Send Error: ' . $e->getMessage());
            }
        }

        // Send WhatsApp Notification
        if($service->phone) {
            try {
                $waMessage = "Halo {$service->customer_name},\n\nTerima kasih telah mempercayakan service perangkat Anda di SIMASERV.\n\nKode Tiket: *{$service->ticket_code}*\nPerangkat: {$service->device}\nKeluhan: {$service->complaint}\n\nCek status service Anda di: " . route('public.ticket.show', $service->ticket_code);
                
                \App\Services\WhatsAppService::send($service->phone, $waMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('WA Send Error: ' . $e->getMessage());
            }
        }

        return redirect()->route('public.ticket.show', ['ticket_code' => $service->ticket_code])
                         ->with('success', 'Antrian berhasil diambil! Kode Tiket: ' . $service->ticket_code);
    }

    public function show($ticket_code)
    {
        $service = Service::where('ticket_code', $ticket_code)->with('items')->firstOrFail();
        return view('public.ticket', compact('service'));
    }

    public function help()
    {
        return view('public.help');
    }
}
