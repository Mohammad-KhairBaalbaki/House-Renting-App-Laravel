@extends('admin.layouts.dashboard')

@section('title', 'Cities')

@section('page')
    <div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
        <div>
            <h2 class="mb-1" style="font-weight:950;">Cities</h2>
            <div class="text-muted">Manage cities (create + list)</div>
        </div>

        <a class="btn btn-lg-soft btn-sm" href="{{ route('admin.cities.create') }}">
            <i class="bi bi-plus-lg"></i> Create City
        </a>
    </div>

    @if (session('ok'))
        <div class="alert alert-success reveal delay-1">{{ session('ok') }}</div>
    @endif

    <div class="kpi reveal delay-2 mb-3 filter-bar">
        <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.cities.index') }}">

            <div class="col-lg-6">
                <label class="form-label small text-muted mb-1">Search</label>
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="City name (AR/EN)">
            </div>

            <div class="col-lg-3">
                <label class="form-label small text-muted mb-1">Governorate</label>
                <select class="form-select" name="governorate_id">
                    <option value="">All</option>
                    @foreach ($governorates as $g)
                        @php
                            $gEn = $g->getTranslation('name', 'en', false) ?: '';
                            $gAr = $g->getTranslation('name', 'ar', false) ?: '';
                            $gLabel = trim($gEn . ' / ' . $gAr, ' /');
                        @endphp
                        <option value="{{ $g->id }}" @selected((string) $g->id === (string) request('governorate_id'))>
                            {{ $gLabel ?: '#' . $g->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-lg-soft btn-sm flex-grow-1" type="submit">
                    <i class="bi bi-funnel"></i> Apply
                </button>
                <a class="btn btn-outline-dark btn-sm flex-grow-1" href="{{ route('admin.cities.index') }}">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="kpi reveal delay-3">
        <div class="table-responsive">
            <table class="table align-middle admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>City (EN)</th>
                        <th>City (AR)</th>
                        <th>Governorate (EN)</th>
                        <th>Governorate (AR)</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cities as $c)
                        @php
                            $cEn = $c->getTranslation('name', 'en', false) ?: '-';
                            $cAr = $c->getTranslation('name', 'ar', false) ?: '-';

                            $g = $c->governorate;
                            $gEn = $g?->getTranslation('name', 'en', false) ?: '-';
                            $gAr = $g?->getTranslation('name', 'ar', false) ?: '-';
                        @endphp
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td class="fw-bold">{{ $cEn }}</td>
                            <td dir="rtl">{{ $cAr }}</td>
                            <td>{{ $gEn }}</td>
                            <td dir="rtl">{{ $gAr }}</td>
                            <td class="text-muted small">{{ $c->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No cities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cities->links() }}
        </div>
    </div>
@endsection
