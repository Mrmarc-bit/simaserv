@extends('layouts.admin')

@section('content')
<!-- Header -->
<!-- Header -->
<div class="page-header" style="flex-wrap: wrap; gap: 1rem;">
    <div class="page-title">
        <h1>Laporan Bulanan</h1>
        <p>Data service yang sudah DIAMBIL pada periode ini.</p>
    </div>
    
    <div style="display: flex; gap: 1rem; align-items: center;">
        <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 0.5rem; background: white; padding: 0.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
            <select name="month" onchange="this.form.submit()" style="border: none; background: transparent; font-weight: 500; outline: none; cursor: pointer;">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                    </option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()" style="border: none; background: transparent; font-weight: 500; outline: none; cursor: pointer; border-left: 1px solid #e2e8f0; padding-left: 0.5rem;">
                @foreach(range(date('Y'), 2024) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.reports.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-outline">
            <i class="fas fa-file-export"></i> Export CSV
        </a>
    </div>
</div>

<!-- Financial Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <span>Total Pendapatan (Service Diambil)</span>
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-value" style="color: var(--primary);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-trend" style="color: var(--secondary);">
            <i class="fas fa-info-circle"></i> 
            <span>Akumulasi dari {{ $count }} transaksi selesai</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <span>Total Unit Diambil</span>
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-value">{{ $count }}</div>
        <div class="stat-trend trend-up">
            <i class="fas fa-smile"></i> 
            <span>Customer Puas</span>
        </div>
    </div>
</div>

<!-- Data Table Card -->
<div class="data-card">
    <div class="toolbar">
        <h3>Riwayat Transaksi</h3>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Customer</th>
                <th>Device</th>
                <th>Payment</th>
                <th>Tanggal Selesai</th>
                <th>Pendapatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $srv)
            <tr>
                <td><span style="font-weight: 600; font-family: monospace;">{{ $srv->ticket_code }}</span></td>
                <td>
                    <div class="customer-cell">
                        <span class="customer-name">{{ $srv->customer_name }}</span>
                        <span class="customer-email">{{ $srv->phone }}</span>
                    </div>
                </td>
                <td>{{ $srv->device }}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        @if($srv->payment_method == 'QRIS')
                            <i class="fas fa-qrcode"></i> QRIS
                        @elseif($srv->payment_method == 'CASH')
                            <i class="fas fa-money-bill"></i> Cash
                        @else
                            <span class="text-muted">-</span>
                        @endif
                        <span class="status {{ $srv->payment_status == 'LUNAS' ? 'status-lunas' : 'status-belum-lunas' }}" style="font-size: 0.65rem;">
                            {{ $srv->payment_status }}
                        </span>
                    </div>
                </td>
                <td>{{ $srv->updated_at->format('d M Y') }}</td>
                <td><strong>Rp {{ number_format($srv->total_price, 0, ',', '.') }}</strong></td>
                <td>
                    <a href="{{ route('admin.services.show', $srv->id) }}" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 3rem;">
                    <div style="display: flex; flex-direction: column; align-items: center; color: var(--text-muted);">
                        <i class="fas fa-clipboard-check" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Belum ada service yang statusnya DIAMBIL.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        {{ $services->links('pagination::simple-tailwind') }}
    </div>
</div>
@endsection
