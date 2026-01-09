@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-title">
        <h1>Services Management</h1>
        <p>View, manage, and fulfill customer service requests.</p>
    </div>
    <div>
        <button class="btn btn-outline"><i class="fas fa-file-export"></i> Export</button>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('home') }}'"><i class="fas fa-plus"></i> Add New Service</button>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <a href="{{ route('admin.services.index') }}" class="tab-link {{ !request('status') ? 'active' : '' }}">
        All Services <span class="badge-count">{{ \App\Models\Service::where('status', '!=', 'DIAMBIL')->count() }}</span>
    </a>
    <a href="{{ route('admin.services.index', ['status' => 'MENUNGGU']) }}" class="tab-link {{ request('status') == 'MENUNGGU' ? 'active' : '' }}">
        Menunggu <span class="badge-count">{{ \App\Models\Service::where('status', 'MENUNGGU')->count() }}</span>
    </a>
    <a href="{{ route('admin.services.index', ['status' => 'DIPERBAIKI']) }}" class="tab-link {{ request('status') == 'DIPERBAIKI' ? 'active' : '' }}">
        Processing <span class="badge-count">{{ \App\Models\Service::where('status', 'DIPERBAIKI')->count() }}</span>
    </a>
    <a href="{{ route('admin.services.index', ['status' => 'SELESAI']) }}" class="tab-link {{ request('status') == 'SELESAI' ? 'active' : '' }}">
        Completed <span class="badge-count">{{ \App\Models\Service::where('status', 'SELESAI')->count() }}</span>
    </a>
</div>

<!-- Data Table Card -->
<div class="data-card">
    <!-- Toolbar -->
    <div class="toolbar">
        <form action="{{ route('admin.services.index') }}" method="GET" style="flex: 1; display: flex; gap: 1rem; align-items: center;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('date'))
                <input type="hidden" name="date" value="{{ request('date') }}">
            @endif
            <div style="position: relative; flex: 1; max-width: 350px;">
                <input type="text" name="search" class="search-input" placeholder="Search orders..." value="{{ request('search') }}" style="width: 100%; padding-left: 2.5rem;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #9ca3af;"></i>
            </div>
            
            <!-- Filters Mockup (Visual only for now) -->
            <select style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 8px; background: white; color: #4b5563;">
                <option>Last 7 days</option>
                <option>This Month</option>
            </select>
            
            <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem;">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox"></th>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Deliver To (Device)</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Tracking (Complaint)</th>
                <th>Status</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $srv)
            <tr>
                <td><input type="checkbox"></td>
                <td><span style="font-weight: 600; font-family: monospace;">{{ $srv->ticket_code }}</span></td>
                <td>
                    <div class="customer-cell">
                        <span class="customer-name">{{ $srv->customer_name }}</span>
                        <span class="customer-email">{{ $srv->phone }}</span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 24px; height: 24px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-desktop text-muted" style="font-size: 0.7rem;"></i></span>
                        <span style="font-size: 0.875rem;">{{ $srv->device }}</span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        @if($srv->payment_method == 'QRIS')
                            <i class="fas fa-qrcode"></i> QRIS
                        @elseif($srv->payment_method == 'CASH')
                            <i class="fas fa-money-bill"></i> Cash
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </td>
                <td>{{ $srv->created_at->format('d-m-Y') }}</td>
                <td><span style="color: var(--text-muted); font-size: 0.8rem;">{{ Str::limit($srv->complaint, 20) }}</span></td>
                <td>
                    <span class="status status-{{ strtolower($srv->status) }}">
                        {{ ucwords(strtolower($srv->status)) }}
                    </span>
                </td>
                <td><strong>${{ number_format($srv->total_price, 0, ',', '.') }}</strong></td>
                <td>
                    <a href="{{ route('admin.services.show', $srv->id) }}" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem;">Manage</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 3rem;">
                    <div style="display: flex; flex-direction: column; align-items: center; color: var(--text-muted);">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>No services found matching your criteria.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        {{ $services->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>
</div>
@endsection
