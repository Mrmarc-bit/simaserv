<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceDoneMail;
use App\Services\WhatsAppService;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total' => Service::count(),
            'selesai' => Service::where('status', 'SELESAI')->count(),
            'pending' => Service::whereIn('status', ['MENUNGGU', 'DIPERIKSA', 'DIPERBAIKI'])->count(),
            'today' => Service::whereDate('created_at', now())->count(),
        ];
        // Get recent services (Exclude Completed/Taken)
        $recentServices = Service::whereNotIn('status', ['SELESAI', 'DIAMBIL'])
                                 ->latest()
                                 ->take(5)
                                 ->get();

        // Calendar Data
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $calendarServices = Service::selectRaw('
                DATE(created_at) as date, 
                count(*) as total,
                SUM(CASE WHEN status IN ("SELESAI", "DIAMBIL") THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status IN ("MENUNGGU", "DIPERIKSA", "DIPERBAIKI") THEN 1 ELSE 0 END) as pending
            ')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return view('admin.dashboard', compact('stats', 'recentServices', 'calendarServices', 'startOfMonth'));
    }

    public function services(Request $request)
    {
        $query = Service::query();
        if ($request->has('search') && $request->search) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('ticket_code', 'like', "%$s%")
                  ->orWhere('customer_name', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'PENDING') {
                $query->whereIn('status', ['MENUNGGU', 'DIPERIKSA', 'DIPERBAIKI']);
            } elseif ($request->status === 'ALL') {
                // Show everything, including DIAMBIL
            } else {
                $query->where('status', $request->status);
            }
        } elseif (!$request->has('date')) {
            // By default (if no status AND no date filter), exclude DIAMBIL
            $query->where('status', '!=', 'DIAMBIL');
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $services = $query->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with('items')->findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    public function invoice($id)
    {
        $service = Service::with('items')->findOrFail($id);
        return view('admin.services.invoice', compact('service'));
    }

    public function updateStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'status' => 'required|in:MENUNGGU,DIPERIKSA,DIPERBAIKI,SELESAI,DIAMBIL',
            'damage_note' => 'nullable|string',
            'payment_status' => 'nullable|in:BELUM_LUNAS,LUNAS',
        ]);

        // Logic Check: Can't pick up if unpaid
        if ($request->status == 'DIAMBIL') {
            // Check provided payment status OR existing one if not provided
            $payStatus = $request->input('payment_status', $service->payment_status);
            
            if ($payStatus == 'BELUM_LUNAS') {
                return back()->with('error', 'Gagal! Barang tidak bisa diambil jika pembayaran belum lunas.');
            }
        }

        // Logic Check: Can't finish if no items/cost
        if (in_array($request->status, ['SELESAI', 'DIAMBIL'])) {
            if ($service->items()->count() == 0) {
                return back()->with('error', 'Gagal! Harap input rincian biaya/tindakan (Service Items) terlebih dahulu sebelum menyelesaikan service.');
            }
        }

        $data = [
            'status' => $request->status,
            'damage_note' => $request->damage_note,
        ];

        if ($request->has('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        $service->update($data);

        // Notify User if Status is SELESAI
        if ($request->status == 'SELESAI') {
            // Send WhatsApp
            if ($service->phone) {
                try {
                    $total = number_format($service->total_price, 0, ',', '.');
                    $link = route('public.ticket.show', $service->ticket_code);
                    $waMessage = "Halo {$service->customer_name},\n\nKabar gembira! Perangkat {$service->device} Anda telah SELESAI diperbaiki.\n\nStatus: *SELESAI*\nTotal Biaya: Rp {$total}\n\nSilakan datang untuk pengambilan unit.\nCek Detail: {$link}\n\nTerima kasih - SIMASERV";
                    
                    WhatsAppService::send($service->phone, $waMessage);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('WA Notification Error: ' . $e->getMessage());
                }
            }

            // Send Email
            if ($service->email) {
                try {
                    Mail::to($service->email)->send(new ServiceDoneMail($service));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email Notification Error: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Status updated successfully');
    }

    public function updatePayment(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS',
            'payment_status' => 'required|in:BELUM_LUNAS,LUNAS',
        ]);

        $service->update([
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Payment info updated successfully');
    }

    public function addItem(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'item_name' => 'required|string',
            'price' => 'required|integer|min:0',
        ]);

        $service->items()->create([
            'item_name' => $request->item_name,
            'price' => $request->price,
        ]);
        
        // Auto-recalculate triggered by model event, but let's be sure
        // service->recalculateTotal() is called in Observer/Booted
        
        return back()->with('success', 'Item added successfully');
    }

    public function deleteItem($itemId)
    {
        $item = ServiceItem::findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item removed successfully');
    }

    public function reports(Request $request)
    {
        // Default to current month/year if not specified
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Base Query
        $query = Service::where('status', 'DIAMBIL')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        // Clones for stats
        $totalRevenue = (clone $query)->sum('total_price');
        $count = (clone $query)->count();
        
        // Data for Table
        $services = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.reports.index', compact('services', 'totalRevenue', 'count', 'month', 'year'));
    }

    public function exportReports(Request $request)
    {
        // Export is YEARLY based on user request ("export csv nya itu pertahun")
        $year = $request->input('year', date('Y'));
        
        $fileName = 'Annual_Report_' . $year . '.csv';

        $services = Service::where('status', 'DIAMBIL')
            ->whereYear('created_at', $year) // Only filter by Year
            ->latest()
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Ticket Code', 'Date Created', 'Date Completed', 'Customer Name', 'Phone', 'Device', 'Complaint', 'Total Price', 'Payment Method');

        $callback = function() use($services, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            $grandTotal = 0;

            foreach ($services as $service) {
                $row['Ticket Code']  = $service->ticket_code;
                $row['Date Created']    = $service->created_at->format('Y-m-d H:i');
                $row['Date Completed']  = $service->updated_at->format('Y-m-d H:i');
                $row['Customer Name']  = $service->customer_name;
                $row['Phone']  = $service->phone;
                $row['Device']  = $service->device;
                $row['Complaint']  = $service->complaint;
                $row['Total Price']  = $service->total_price;
                $row['Payment Method']  = $service->payment_method;
                
                $grandTotal += $service->total_price;

                fputcsv($file, array($row['Ticket Code'], $row['Date Created'], $row['Date Completed'], $row['Customer Name'], $row['Phone'], $row['Device'], $row['Complaint'], $row['Total Price'], $row['Payment Method']));
            }
            
            // Add Empty Row
            fputcsv($file, []);
            // Add Grand Total Row
            fputcsv($file, ['', '', '', '', '', '', 'TOTAL PENDAPATAN TAHUN INI', $grandTotal, '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function help()
    {
        return view('admin.help');
    }

}
