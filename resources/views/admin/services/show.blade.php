@extends('layouts.admin')

@section('content')
<!-- Header with Breadcrumb -->
<div class="page-header">
    <div class="page-title">
        <h1>Ticket #{{ $service->ticket_code }}</h1>
        <p>Detail service dan update status perbaikan.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <button class="btn btn-outline" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <a href="{{ route('admin.services.invoice', $service->id) }}" target="_blank" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Invoice
        </a>
    </div>
</div>

<div style="max-width: 1000px; margin: 0 auto;">

    <!-- Customer & Device Info Card -->
    <div class="stat-card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem;">
            <h3 style="margin: 0; font-size: 1.1rem;">Customer & Device</h3>
            <span class="status status-{{ strtolower($service->status) }}">{{ $service->status }}</span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.25rem;">Customer Name</p>
                <p style="font-weight: 600; font-size: 1rem; margin-top: 0;">{{ $service->customer_name }}</p>
                
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.25rem; margin-top: 1rem;">Phone / WhatsApp</p>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                    <span style="font-weight: 500;">{{ $service->phone }}</span>
                </div>
            </div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.25rem;">Device Model & Qty</p>
                <p style="font-weight: 600; font-size: 1rem; margin-top: 0;">
                    {{ $service->device }} 
                    <span style="background: #e2e8f0; font-size: 0.75rem; padding: 2px 8px; border-radius: 99px; margin-left: 0.5rem; color: #475569;">x{{ $service->quantity }}</span>
                </p>

                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.25rem; margin-top: 1rem;">Registered Date</p>
                <p style="margin-top: 0;">{{ $service->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

    <!-- Process & Update Card -->
    <div class="stat-card" style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1.5rem 0; font-size: 1.1rem;">Process & Update Ticket</h3>

        <!-- Main Update Form Definition (Invisible wrapper) -->
        <form id="updateStatusForm" action="{{ route('admin.services.updateStatus', $service->id) }}" method="POST">
            @csrf
            @method('PUT')
        </form>

        <!-- 1. Status Selection -->
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 600;">STATUS PERBAIKAN</label>
            <select form="updateStatusForm" name="status" id="mainStatusSelect" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: white;">
                @foreach(['MENUNGGU', 'DIPERIKSA', 'DIPERBAIKI', 'SELESAI', 'DIAMBIL'] as $st)
                    <option value="{{ $st }}" {{ $service->status == $st ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 2. Service Items Section (Interleaved, Visible if needed) -->
        <div id="serviceItemsWrapper" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: var(--text-muted); border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">RINCIAN BIAYA & SPAREPART</h4>
            
            <!-- Items Table -->
            @if($service->items->count() > 0)
            <table class="table" style="margin-bottom: 1.5rem; background: white; border-radius: 8px; overflow: hidden;">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th style="padding: 0.5rem 1rem; font-size: 0.85rem;">Item</th>
                        <th style="text-align: right; padding: 0.5rem 1rem; font-size: 0.85rem;">Harga</th>
                        <th style="width: 40px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($service->items as $item)
                    <tr>
                        <td style="padding: 0.5rem 1rem;">{{ $item->item_name }}</td>
                        <td style="text-align: right; padding: 0.5rem 1rem; font-family: monospace;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.items.delete', $item->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this)" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px;">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background: #fdf2f8; font-weight: bold;">
                        <td style="padding: 0.75rem 1rem; color: #db2777;">TOTAL</td>
                        <td style="text-align: right; padding: 0.75rem 1rem; color: #db2777;">Rp {{ number_format($service->total_price, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            @else
            <p style="text-align: center; color: #94a3b8; font-style: italic; margin-bottom: 1.5rem;">Belum ada item biaya tambahan.</p>
            @endif

            <!-- Add Item Form -->
            <form action="{{ route('admin.services.addItem', $service->id) }}" method="POST" style="display: flex; gap: 0.5rem;">
                @csrf
                <input type="text" name="item_name" placeholder="Nama Item / Jasa" required style="flex: 2; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                <input type="number" name="price" placeholder="Harga" required style="flex: 1; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                <button type="submit" class="btn btn-primary" style="padding: 0 1rem; font-size: 0.9rem;"><i class="fas fa-plus"></i></button>
            </form>
        </div>

        <!-- 3. Payment Status (Hidden by default) -->
        <div id="paymentField" style="margin-bottom: 1.5rem; display: none;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 600;">STATUS PEMBAYARAN</label>
            <select form="updateStatusForm" name="payment_status" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; background: {{ $service->payment_status == 'LUNAS' ? '#dcfce7' : '#fee2e2' }}; color: {{ $service->payment_status == 'LUNAS' ? '#166534' : '#991b1b' }}; font-weight: 600;">
                <option value="BELUM_LUNAS" {{ $service->payment_status == 'BELUM_LUNAS' ? 'selected' : '' }}>BELUM LUNAS</option>
                <option value="LUNAS" {{ $service->payment_status == 'LUNAS' ? 'selected' : '' }}>LUNAS</option>
            </select>
        </div>

        <!-- 4. Technician Note -->
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 600;">CATATAN TEKNISI</label>
            <textarea form="updateStatusForm" name="damage_note" rows="3" placeholder="Tulis diagnosa, tindakan, atau catatan untuk customer..." style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; resize: vertical;">{{ $service->damage_note }}</textarea>
        </div>

        <!-- Submit Button -->
        <button form="updateStatusForm" type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; font-size: 1rem; font-weight: 600;">
            <i class="fas fa-save" style="margin-right: 8px;"></i> Simpan Status & Kirim Notifikasi
        </button>
    </div>

    <!-- Items/Biaya Card (Removed as it's merged above) -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('mainStatusSelect');
        const paymentField = document.getElementById('paymentField');
        const serviceItemsWrapper = document.getElementById('serviceItemsWrapper');
        const paymentStatusSelect = document.querySelector('select[name="payment_status"]');
        
        // Pass item count from Blade to JS
        const hasItems = {{ $service->items->count() > 0 ? 'true' : 'false' }};

        function handleStatusChange() {
            const status = statusSelect.value;
            const isFinished = (status === 'SELESAI' || status === 'DIAMBIL');
            
            // 1. Show/Hide Payment Field
            if (isFinished) {
                paymentField.style.display = 'block';
            } else {
                paymentField.style.display = 'none';
            }

            // 2. Logic: If DIAMBIL, payment MUST be LUNAS
            if (status === 'DIAMBIL') {
                paymentStatusSelect.value = 'LUNAS';
            }

            // 3. Show Service Items Wrapper
            if (isFinished || hasItems) {
                serviceItemsWrapper.style.display = 'block';
            } else {
                serviceItemsWrapper.style.display = 'none';
            }
        }

        // Init on load
        handleStatusChange();

        // Listen for changes
        statusSelect.addEventListener('change', handleStatusChange);
        
        if (statusSelect.tomselect) {
            statusSelect.tomselect.on('change', handleStatusChange);
        }
    });

    function confirmDelete(btn) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Item biaya ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        })
    }
</script>
@endsection
