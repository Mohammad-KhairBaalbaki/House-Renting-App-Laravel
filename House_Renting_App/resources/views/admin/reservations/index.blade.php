@extends('admin.layouts.dashboard')

@section('title','Reservations')

@section('page')
<div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
  <div>
    <h2 class="mb-1" style="font-weight:950;">Reservations</h2>
    <div class="text-muted">Browse reservations (view only)</div>
  </div>
</div>

<div class="kpi reveal delay-2 mb-3 filter-bar">
  <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.reservations.index') }}">

    <div class="col-lg-4">
      <label class="form-label small text-muted mb-1">Search</label>
      <input class="form-control" name="q" value="{{ request('q') }}"
             placeholder="Reservation id / user phone / house title">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Status</label>
      <select class="form-select" name="status_id">
        <option value="">All</option>
        @foreach($statuses as $st)
          <option value="{{ $st->id }}" @selected((string)$st->id === request('status_id'))>{{ $st->type }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Min Rent</label>
      <input type="number" step="0.01" class="form-control" name="min_rent" value="{{ request('min_rent') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Max Rent</label>
      <input type="number" step="0.01" class="form-control" name="max_rent" value="{{ request('max_rent') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Start From</label>
      <input type="date" class="form-control" name="start_from" value="{{ request('start_from') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Start To</label>
      <input type="date" class="form-control" name="start_to" value="{{ request('start_to') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Min Days</label>
      <input type="number" class="form-control" name="min_duration" value="{{ request('min_duration') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Max Days</label>
      <input type="number" class="form-control" name="max_duration" value="{{ request('max_duration') }}">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Sort</label>
      <select class="form-select" name="sort_by">
        <option value="created_at" @selected(request('sort_by','created_at')==='created_at')>Created</option>
        <option value="start_date" @selected(request('sort_by')==='start_date')>Start</option>
        <option value="end_date" @selected(request('sort_by')==='end_date')>End</option>
        <option value="rent_value" @selected(request('sort_by')==='rent_value')>Rent</option>
      </select>
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Dir</label>
      <select class="form-select" name="sort_dir">
        <option value="desc" @selected(request('sort_dir','desc')==='desc')>Desc</option>
        <option value="asc"  @selected(request('sort_dir')==='asc')>Asc</option>
      </select>
    </div>

    <div class="col-lg-4 d-flex gap-2">
      <button class="btn btn-lg-soft btn-sm flex-grow-1" type="submit">
        <i class="bi bi-funnel"></i> Apply
      </button>
      <a class="btn btn-outline-dark btn-sm flex-grow-1" href="{{ route('admin.reservations.index') }}">
        <i class="bi bi-arrow-counterclockwise"></i> Reset
      </a>
    </div>
  </form>
</div>

<div class="kpi reveal delay-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>House</th>
          <th>Dates</th>
          <th>Days</th>
          <th>Rent</th>
          <th>Status</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      @forelse($reservations as $r)
        @php
          $u = $r->user;
          $h = $r->house;
        @endphp
        <tr>
          <td class="fw-bold">#{{ $r->id }}</td>
          <td>
            <div class="fw-bold">{{ $u?->first_name }} {{ $u?->last_name }}</div>
            <div class="text-muted small">{{ $u?->phone }}</div>
          </td>
          <td>
            <div class="fw-bold">{{ $h?->title }}</div>
            <div class="text-muted small">{{ $h?->rent_value }}</div>
          </td>
          <td class="small">
            <div><strong>Start:</strong> {{ $r->start_date }}</div>
            <div><strong>End:</strong> {{ $r->end_date }}</div>
          </td>
          <td>{{ $r->duration }}</td>
          <td>{{ $h?->rent_value }}</td>
          <td><span class="badge-pill badge-a">{{ $r->status?->type ?? '-' }}</span></td>
          <td class="small text-muted">{{ $r->created_at?->format('Y-m-d H:i') }}</td>
          <td class="text-end">
            <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.reservations.show', $r) }}">
              <i class="bi bi-eye"></i> View
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center text-muted py-4">No reservations found.</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $reservations->links() }}
</div>
@endsection
