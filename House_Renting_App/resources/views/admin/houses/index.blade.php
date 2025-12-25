@extends('admin.layouts.dashboard')

@section('title','Houses')

@section('page')
<div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
  <div>
    <h2 class="mb-1" style="font-weight:950;">Houses</h2>
    <div class="text-muted">Browse houses & update status (admin)</div>
  </div>
</div>

@if(session('ok'))
  <div class="alert alert-success reveal delay-1">{{ session('ok') }}</div>
@endif

{{-- FILTERS --}}
{{-- FILTERS (COMPACT) --}}
<div class="kpi reveal delay-2 mb-3 filter-bar">
  <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.houses.index') }}">

    <div class="col-lg-4">
      <label class="form-label small text-muted mb-1">Search</label>
      <input class="form-control" name="search" value="{{ request('search') }}"
             placeholder="Title / description / city / governorate / owner phone">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Governorate</label>
      <select class="form-select" name="governorate_id">
        <option value="">All</option>
        @foreach($governorates as $g)
          @php
            $gn = is_array($g->name ?? null) ? ($g->name['en'] ?? $g->name['ar'] ?? reset($g->name)) : ($g->name ?? '');
          @endphp
          <option value="{{ $g->id }}" @selected((string)$g->id === request('governorate_id'))>{{ $gn }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">City</label>
      <select class="form-select" name="city_id">
        <option value="">All</option>
        @foreach($cities as $c)
          @php
            $cn = is_array($c->name ?? null) ? ($c->name['en'] ?? $c->name['ar'] ?? reset($c->name)) : ($c->name ?? '');
          @endphp
          <option value="{{ $c->id }}" @selected((string)$c->id === request('city_id'))>{{ $cn }}</option>
        @endforeach
      </select>
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
      <label class="form-label small text-muted mb-1">Active</label>
      <select class="form-select" name="is_active">
        <option value="">All</option>
        <option value="1" @selected(request('is_active') === '1')>Active</option>
        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
      </select>
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Min Rent</label>
      <input type="number" step="0.01" class="form-control" name="min_rent"
             value="{{ request('min_rent') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Max Rent</label>
      <input type="number" step="0.01" class="form-control" name="max_rent"
             value="{{ request('max_rent') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Min Space</label>
      <input type="number" step="0.01" class="form-control" name="min_space"
             value="{{ request('min_space') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Max Space</label>
      <input type="number" step="0.01" class="form-control" name="max_space"
             value="{{ request('max_space') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Min Rooms</label>
      <input type="number" class="form-control" name="min_rooms"
             value="{{ request('min_rooms') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Max Rooms</label>
      <input type="number" class="form-control" name="max_rooms"
             value="{{ request('max_rooms') }}" placeholder="0">
    </div>

    <div class="col-lg-2">
      <label class="form-label small text-muted mb-1">Sort</label>
      <select class="form-select" name="sort_by">
        <option value="created_at" @selected(request('sort_by','created_at')==='created_at')>Created</option>
        <option value="rent_value" @selected(request('sort_by')==='rent_value')>Rent</option>
        <option value="space" @selected(request('sort_by')==='space')>Space</option>
        <option value="rooms" @selected(request('sort_by')==='rooms')>Rooms</option>
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
      <a class="btn btn-outline-dark btn-sm flex-grow-1" href="{{ route('admin.houses.index') }}">
        <i class="bi bi-arrow-counterclockwise"></i> Reset
      </a>
    </div>
  </form>
</div>


{{-- GRID --}}
<div class="house-grid reveal delay-3">
  @forelse($houses as $h)
    @php
      $img = optional($h->firstImage)->url;
      $city = optional(optional($h->address)->city);
      $gov  = optional($city->governorate);

      $cityName = is_array($city->name ?? null) ? ($city->name['en'] ?? $city->name['ar'] ?? reset($city->name)) : ($city->name ?? '');
      $govName  = is_array($gov->name ?? null)  ? ($gov->name['en'] ?? $gov->name['ar'] ?? reset($gov->name))   : ($gov->name ?? '');
    @endphp

    <div class="house-card">
      <a class="house-card-cover" href="{{ route('admin.houses.show', $h) }}">
        <button type="button" class="fit-toggle" data-fit-toggle>Fit</button>

        @if($img)
          <img class="cover-img is-cover" src="{{ asset('storage/'.$img) }}" alt="house">
        @else
          <div class="house-cover-fallback"><i class="bi bi-house-door"></i></div>
        @endif
      </a>

      <div class="house-card-body">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div>
            <div class="house-card-title">{{ $h->title ?? 'No title' }}</div>
            <div class="house-card-sub text-muted">
              {{ $govName }} {{ $govName && $cityName ? '-' : '' }} {{ $cityName }}
              @if($h->address)
                • {{ $h->address->street }} ({{ $h->address->flat_number }})
              @endif
            </div>
          </div>

          <div class="text-end">
            @if($h->is_active)
              <span class="badge-pill badge-b">Active</span>
            @else
              <span class="badge-pill badge-d">Inactive</span>
            @endif
            <div class="mt-2">
              <span class="badge-pill badge-a">{{ optional($h->status)->type ?? '-' }}</span>
            </div>
          </div>
        </div>

        <div class="house-mini-kpis mt-3">
          <div class="mk">
            <div class="mk-l">Rent</div>
            <div class="mk-v">{{ $h->rent_value }}</div>
          </div>
          <div class="mk">
            <div class="mk-l">Rooms</div>
            <div class="mk-v">{{ $h->rooms }}</div>
          </div>
          <div class="mk">
            <div class="mk-l">Space</div>
            <div class="mk-v">{{ $h->space }}</div>
          </div>
        </div>

        <div class="house-owner mt-3">
          <div class="text-muted small">Owner</div>
          <div class="fw-bold">{{ optional($h->user)->first_name }} {{ optional($h->user)->last_name }}</div>
          <div class="text-muted small">{{ optional($h->user)->phone }}</div>
        </div>

        <div class="house-actions mt-3">
          <a class="btn btn-outline-dark" href="{{ route('admin.houses.show', $h) }}">
            <i class="bi bi-eye"></i> View
          </a>

          <form method="POST" action="{{ route('admin.houses.status', $h) }}" class="d-flex gap-2 flex-grow-1">
            @csrf
            @method('PATCH')
            <select class="form-select" name="status_id">
              @foreach($statuses as $st)
                <option value="{{ $st->id }}" @selected($h->status_id == $st->id)>{{ $st->type }}</option>
              @endforeach
            </select>
            <button class="btn btn-lg-soft" type="submit">Save</button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="kpi text-center text-muted py-5">
      No houses found.
    </div>
  @endforelse
</div>

<div class="mt-3">
  {{ $houses->links() }}
</div>
@endsection
