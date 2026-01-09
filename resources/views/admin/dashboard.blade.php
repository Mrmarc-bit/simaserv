@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-title">
        <h1>Overview</h1>
        <p>Ringkasan kinerja service hari ini.</p>
    </div>
    <div>
        <a href="{{ route('admin.services.index') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Ticket Baru</a>
    </div>
</div>

<!-- Stats Cards (Matching Image Style) -->
<div class="stats-grid">
    <!-- New Orders / Service Baru -->
    <div class="stat-card clickable-card" onclick="window.location.href='{{ route('admin.services.index', ['date' => now()->toDateString()]) }}'">
        <div class="stat-header">
            <span>Service Baru</span>
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['today'] }}</div>
        <div class="stat-trend" style="color: #16a34a;">
            <i class="fas fa-arrow-up"></i> 
            <span>Hari Ini</span>
        </div>
    </div>

    <!-- Awaiting Payment / Dalam Proses -->
    <div class="stat-card clickable-card" onclick="window.location.href='{{ route('admin.services.index', ['status' => 'PENDING']) }}'">
        <div class="stat-header">
            <span>Dalam Proses</span>
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-trend" style="color: #ca8a04;">
            <i class="fas fa-tools"></i> 
            <span>Unit Sedang Dikerjakan</span>
        </div>
    </div>

    <!-- Orders Today / Selesai -->
    <div class="stat-card clickable-card" onclick="window.location.href='{{ route('admin.services.index', ['status' => 'SELESAI']) }}'">
        <div class="stat-header">
            <span>Selesai (Total)</span>
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['selesai'] }}</div>
        <div class="stat-trend trend-up">
            <i class="fas fa-check"></i> 
            <span>Unit Siap Ambil</span>
        </div>
    </div>

    <!-- Sales Today / Total Unit -->
    <div class="stat-card clickable-card" onclick="window.location.href='{{ route('admin.services.index', ['status' => 'ALL']) }}'">
        <div class="stat-header">
            <span>Total Masuk</span>
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-trend trend-up">
            <span>Sejak Awal</span>
        </div>
    </div>
</div>

<style>
    .clickable-card {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .clickable-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>

<!-- Calendar Section -->
<div class="data-card" style="margin-bottom: 2rem;">
    <div class="toolbar" style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.dashboard', ['month' => $startOfMonth->copy()->subMonth()->month, 'year' => $startOfMonth->copy()->subMonth()->year]) }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h3 style="margin: 0;">Kalender Service - {{ $startOfMonth->translatedFormat('F Y') }}</h3>
        <a href="{{ route('admin.dashboard', ['month' => $startOfMonth->copy()->addMonth()->month, 'year' => $startOfMonth->copy()->addMonth()->year]) }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
    <div class="calendar-wrapper">
        <div class="calendar-grid">
            <!-- Headers -->
            <div class="calendar-header">Minggu</div>
            <div class="calendar-header">Senin</div>
            <div class="calendar-header">Selasa</div>
            <div class="calendar-header">Rabu</div>
            <div class="calendar-header">Kamis</div>
            <div class="calendar-header">Jumat</div>
            <div class="calendar-header">Sabtu</div>

            <!-- Empty slots for previous month -->
            @for($i = 0; $i < $startOfMonth->dayOfWeek; $i++)
                <div class="calendar-day empty"></div>
            @endfor

            <!-- Days -->
            @for($day = 1; $day <= $startOfMonth->daysInMonth; $day++)
                @php
                    $currentDate = $startOfMonth->copy()->day($day);
                    $dateStr = $currentDate->toDateString();
                    $hasService = isset($calendarServices[$dateStr]);
                    $data = $hasService ? $calendarServices[$dateStr] : null;
                    $isToday = $dateStr === now()->toDateString();
                @endphp
                <a href="{{ route('admin.services.index', ['date' => $dateStr]) }}" class="calendar-day {{ $hasService ? 'has-service' : '' }} {{ $isToday ? 'is-today' : '' }}">
                    <div class="day-number">{{ $day }}</div>
                    @if($hasService)
                        @if($data->pending == 0)
                            <div class="service-badge" style="background: #dcfce7; color: #166534;">
                                <i class="fas fa-check-circle" style="font-size: 0.7rem;"></i> Completed
                            </div>
                        @else
                            <div class="service-badge" style="background: #fef9c3; color: #854d0e;">
                                {{ $data->pending }} Proses
                            </div>
                        @endif
                        <div style="font-size: 0.65rem; color: #9ca3af; text-align: center; margin-top: 2px;">
                            Total: {{ $data->total }}
                        </div>
                    @endif
                </a>
            @endfor
        </div>
    </div>
</div>

<style>
    .calendar-wrapper {
        overflow-x: auto;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(100px, 1fr));
        border-left: 1px solid var(--border-color);
    }
    .calendar-header {
        padding: 1rem;
        background: #f9fafb;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-muted);
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
    }
    .calendar-day {
        min-height: 100px;
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        background: white;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        text-decoration: none; /* Ensure no underline */
        color: inherit;
    }
    .calendar-day:hover {
        background: #f8fafc;
    }
    .calendar-day.empty {
        background: #f9fafb;
    }
    .calendar-day.is-today {
        background: #f0fdf4; /* Light green tint for today */
    }
    .day-number {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    .is-today .day-number {
        color: #16a34a;
    }
    .service-badge {
        background: var(--primary-light);
        color: var(--primary);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        text-align: center;
    }
</style>

<div class="data-card">
    <div class="toolbar">
        <h3>Service Terbaru</h3>
        <div style="margin-left: auto;">
             <a href="{{ route('admin.services.index') }}" style="color: var(--primary); font-weight: 500;">Lihat Semua &rarr;</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Customer</th>
                <th>Device</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentServices as $srv)
            <tr>
                <td><span style="font-family: monospace; font-weight: 600;">{{ $srv->ticket_code }}</span></td>
                <td>
                    <div class="customer-cell">
                        <span class="customer-name">{{ $srv->customer_name }}</span>
                        <span class="customer-email">{{ $srv->phone }}</span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-laptop" style="color: var(--text-muted);"></i>
                        {{ $srv->device }}
                    </div>
                </td>
                <td><span class="status status-{{ strtolower($srv->status) }}">{{ $srv->status }}</span></td>
                <td>{{ $srv->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.services.show', $srv->id) }}" style="color: var(--primary); font-weight: 600;">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
