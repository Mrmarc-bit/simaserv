@extends('layouts.app')

@section('content')
<div class="glass" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
        <div>
            <h2 style="margin-bottom: 0.5rem; color: var(--primary);">Tiket Service: #{{ $service->ticket_code }}</h2>
            <p class="text-muted">Dibuat pada: {{ $service->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="text-center">
            <span class="text-muted" style="display: block; font-size: 0.8rem; margin-bottom: 5px;">Status Saat Ini</span>
            <span class="badge status-{{ strtolower($service->status) }}" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $service->status }}</span>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 2rem;">
        <div>
            <h4 class="text-muted">Informasi Pelanggan</h4>
            <p><strong>Nama:</strong> {{ $service->customer_name }}</p>
            <p><strong>Device:</strong> {{ $service->device }}</p>
            <p><strong>Device:</strong> {{ $service->device }}</p>
        </div>
        <div>
            @php
                // Parse Complaint and Accessories
                $parts = explode("\n\nKelengkapan: ", $service->complaint);
                $mainComplaint = $parts[0];
                $accessories = isset($parts[1]) ? $parts[1] : '-';
            @endphp

            <h4 class="text-muted">Keluhan Awal</h4>
            <p style="background: rgba(255,255,255,0.5); padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 1rem;">
                {{ $mainComplaint }}
            </p>

            <h4 class="text-muted">Kelengkapan</h4>
            <p style="background: #f1f5f9; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem;">
                <i class="fas fa-box-open" style="margin-right: 5px; color: #64748b;"></i> {{ $accessories }}
            </p>
        </div>
    </div>

    @if($service->damage_note)
    <div style="margin-bottom: 2rem;">
        <h4 class="text-muted">Catatan Teknisi / Kerusakan</h4>
        <div style="background: #fff7ed; color: #9a3412; padding: 1rem; border-radius: 8px; border: 1px solid #ffedd5;">
            {{ $service->damage_note }}
        </div>
    </div>
    @endif

    <h3 style="margin-bottom: 1rem;">Rincian Biaya</h3>
    <div style="margin-bottom: 2rem; text-align: right;">
        @php
            $waMessage = "Halo, ini tiket service saya:\n\n*Kode:* " . $service->ticket_code . "\n*Device:* " . $service->device . "\n*Status:* " . $service->status . "\n\nLink Cek: " . route('public.ticket.show', $service->ticket_code);
            $waLink = "https://wa.me/?text=" . urlencode($waMessage);
        @endphp
        <a href="{{ $waLink }}" target="_blank" class="btn" style="background: #25D366; color: white;">
            <i class="fab fa-whatsapp"></i> Simpan ke WhatsApp
        </a>
    </div>

    <table class="table" style="background: white; border-radius: 8px; overflow: hidden; margin-bottom: 2rem;">
        <thead>
            <tr>
                <th style="background: #f8fafc;">Item Pekerjaan / Sparepart</th>
                <th style="background: #f8fafc; text-align: right;">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($service->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center text-muted">Belum ada rincian biaya. Biaya akan muncul setelah pemeriksaan.</td>
            </tr>
            @endforelse
            <tr style="font-weight: bold; background: #f0f9ff;">
                <td>TOTAL ESTIMASI</td>
                <td style="text-align: right; color: var(--primary);">Rp {{ number_format($service->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="glass" style="padding: 1.5rem; text-align: center; background: #f8fafc;">
        <p class="text-muted" style="margin-bottom: 0.5rem;">Status Pembayaran</p>
        @if($service->payment_status == 'LUNAS')
            <h2 style="color: var(--success); margin: 0;"><i class="fas fa-check-circle"></i> LUNAS</h2>
            <p style="font-size: 0.9rem;">Metode: {{ $service->payment_method }}</p>
        @else
            <h2 style="color: var(--danger); margin: 0;">BELUM LUNAS</h2>
        @endif
    </div>

    <div class="text-center" style="margin-top: 2rem;">
        <a href="{{ route('home') }}" class="btn" style="color: var(--text-muted);"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
