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
  <div class="kpi reveal delay-3">
  <div class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">Results</div>
      <div class="text-muted small">{{ $reservations->total() }} reservations</div>
    </div>

    <div class="d-grid gap-3">
      @forelse($reservations as $r)
        @php
          $u = $r->user;
          $h = $r->house;
          $st = strtolower($r->status?->type ?? '');
          $pill = match($st){
            'accepted' => 'status-accepted',
            'rejected' => 'status-rejected',
            default => 'status-pending',
          };

          $thumb = $h?->firstImage?->url ?? ($h?->images?->first()?->url ?? null);
        @endphp

        <div class="res-card">
          <div class="res-thumb">
            @if($thumb)
              <img src="{{ asset('storage/'.$thumb) }}" alt="house">
            @else
              <i class="bi bi-house-door" style="font-size:28px; opacity:.6;"></i>
            @endif
          </div>

          <div class="res-meta">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div style="min-width:0;">
                <p class="res-title text-truncate mb-1">
                  #{{ $r->id }} — {{ $h?->title ?? 'House' }}
                </p>
                <div class="res-sub text-truncate">
                  User: <strong>{{ $u?->first_name }} {{ $u?->last_name }}</strong>
                  <span class="mx-1">•</span>
                  {{ $u?->phone }}
                </div>
              </div>

              <div class="text-end">
                <span class="status-pill {{ $pill }}">{{ $r->status?->type ?? '-' }}</span>
                <div class="text-muted small mt-2">{{ $r->created_at?->format('Y-m-d H:i') }}</div>
              </div>
            </div>

            <div class="res-grid">
              <div class="res-k">
                <div class="k">Start</div>
                <div class="v">{{ \Carbon\Carbon::parse($r->start_date)->format('Y-m-d') }}</div>
              </div>
              <div class="res-k">
                <div class="k">End</div>
                <div class="v">{{ \Carbon\Carbon::parse($r->end_date)->format('Y-m-d') }}</div>
              </div>
              <div class="res-k">
                <div class="k">Duration</div>
                <div class="v">{{ $r->duration }}</div>
              </div>
              <div class="res-k">
                <div class="k">Rent</div>
                <div class="v">{{ $h?->rent_value ?? '-' }}</div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
              <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.reservations.show', $r) }}">
                <i class="bi bi-eye"></i> View
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center text-muted py-4">No reservations found.</div>
      @endforelse
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $reservations->links() }}
</div>


<div class="mt-3">
  {{ $reservations->links() }}
</div>
@endsection
