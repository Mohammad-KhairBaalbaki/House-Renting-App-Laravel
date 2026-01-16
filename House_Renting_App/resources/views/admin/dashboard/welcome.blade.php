@extends('admin.layouts.dashboard')

@section('title', 'Welcome')

@section('page')
    <div class="reveal delay-1">
        <h3 class="mb-1" style="font-weight:900;">Welcome to M.R Dar</h3>
        <div class="text-muted mb-3">System overview</div>
    </div>

    {{-- Row 1 --}}
    <div class="grid-4 mb-3">

        {{-- Total Users + Roles --}}
        <div class="kpi reveal delay-2">
            <h6>Total Users</h6>
            <div class="num">{{ $totalUsers }}</div>

            <div class="badges">
                @php $roleColors = ['badge-a','badge-b','badge-c','badge-d','badge-e']; @endphp
                @foreach ($roles as $i => $role)
                    <span class="badge-pill {{ $roleColors[$i % count($roleColors)] }}">
                        {{ $role->name }}: {{ $role->users_count }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- Activated --}}
        <div class="kpi reveal delay-2">
            <h6>Activated Accounts</h6>
            <div class="num">{{ $activatedUsers }}</div>
            <div class="badges"><span class="badge-pill badge-d">accepted</span></div>
        </div>

        {{-- Unactivated --}}
        <div class="kpi reveal delay-2">
            <h6>Unactivated Accounts</h6>
            <div class="num">{{ $unactivatedUsers }}</div>
            <div class="badges"><span class="badge-pill badge-c">pending</span></div>
        </div>

        {{-- Banned --}}
        <div class="kpi reveal delay-2">
            <h6>Banned Accounts</h6>
            <div class="num">{{ $bannedUsers }}</div>
            <div class="badges"><span class="badge-pill badge-e">blocked</span></div>
        </div>
    </div>

    {{-- Row 2 --}}
    <div class="grid-2 mb-3">

        {{-- Houses + 3 dots breakdown --}}
        <div class="kpi reveal delay-3">
            <div class="kpi-actions dropdown">
                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown" aria-label="details">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 220px;">
                    <div class="small text-muted mb-1">Houses breakdown</div>

                    @forelse($housesBreakdown as $status => $count)
                        <div class="d-flex justify-content-between small">
                            <span>{{ $status }}</span>
                            <strong>{{ $count }}</strong>
                        </div>
                    @empty
                        <div class="small text-muted">No data</div>
                    @endforelse
                </div>
            </div>

            <h6>Total Houses</h6>
            <div class="num">{{ $housesTotal ?? '-' }}</div>
            <div class="meta">Click the 3 dots for details</div>
        </div>

        {{-- Bookings --}}
        <div class="kpi reveal delay-3">
            <h6>Total Bookings</h6>
            <div class="num">{{ $bookingsTotal ?? '-' }}</div>
            <div class="meta">Not implemented yet</div>
        </div>
    </div>

    {{-- Chart (Registrations over time) --}}
    <div class="kpi reveal delay-3">
        <h6>Users Registered Over Time (Last 14 days)</h6>
        <div style="height: 300px;">
            <canvas id="usersChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($chartLabels);
        const data = @json($chartData);

        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'New Users',
                    data,
                    tension: 0.35,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
