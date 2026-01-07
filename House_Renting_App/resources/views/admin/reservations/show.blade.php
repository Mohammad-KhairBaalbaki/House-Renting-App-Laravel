@extends('admin.layouts.dashboard')

@section('title','Reservation #'.$reservation->id)

@section('page')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h2 class="mb-1" style="font-weight:950;">Reservation #{{ $reservation->id }}</h2>
    <div class="text-muted">View details</div>
  </div>
  <a class="btn btn-outline-dark" href="{{ route('admin.reservations.index') }}">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="kpi p-3">
      <h5 class="mb-3">Reservation Info</h5>
      <div class="d-flex justify-content-between"><span class="text-muted">Status</span><span class="badge-pill badge-a">{{ $reservation->status?->type ?? '-' }}</span></div>
      <hr>
      <div class="d-flex justify-content-between"><span class="text-muted">Start</span><span>{{ $reservation->start_date }}</span></div>
      <div class="d-flex justify-content-between"><span class="text-muted">End</span><span>{{ $reservation->end_date }}</span></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Duration Per Month</span><span>{{ $reservation->duration }}</span></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Created</span><span>{{ $reservation->created_at?->format('Y-m-d H:i') }}</span></div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="kpi p-3">
      <h5 class="mb-3">User</h5>
      <div class="fw-bold">{{ $reservation->user?->first_name }} {{ $reservation->user?->last_name }}</div>
      <div class="text-muted">{{ $reservation->user?->phone }}</div>

      <div class="row g-2 mt-3">
        <div class="col-6">
          <div class="text-muted small mb-1">Profile</div>
          @if($reservation->user?->profileImage?->url)
            <img class="img-fluid rounded" src="{{ asset('storage/'.$reservation->user->profileImage->url) }}" alt="profile">
          @else
            <div class="text-muted small">No profile image</div>
          @endif
        </div>
        <div class="col-6">
          <div class="text-muted small mb-1">ID</div>
          @if($reservation->user?->idImage?->url)
            <img class="img-fluid rounded" src="{{ asset('storage/'.$reservation->user->idImage->url) }}" alt="id">
          @else
            <div class="text-muted small">No ID image</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="kpi p-3">
      <h5 class="mb-3">House</h5>
      <div class="d-flex justify-content-between flex-wrap gap-2">
        <div>
          <div class="fw-bold">{{ $reservation->house?->title }}</div>
          <div class="text-muted small">Rent: {{ $reservation->house?->rent_value }}</div>
          @php
            $city = optional(optional(optional($reservation->house)->address)->city);
            $gov  = optional($city->governorate);
            $cityName = is_array($city->name ?? null) ? ($city->name['en'] ?? $city->name['ar'] ?? reset($city->name)) : ($city->name ?? '');
            $govName  = is_array($gov->name ?? null)  ? ($gov->name['en'] ?? $gov->name['ar'] ?? reset($gov->name))   : ($gov->name ?? '');
          @endphp
          <div class="text-muted small">{{ $govName }} {{ $govName && $cityName ? '-' : '' }} {{ $cityName }}</div>
        </div>
        <div class="text-muted small">
          Owner: <strong>{{ $reservation->house?->user?->first_name }} {{ $reservation->house?->user?->last_name }}</strong>
          ({{ $reservation->house?->user?->phone }})
        </div>
      </div>

      <hr>

      <div class="row g-2">
        @forelse($reservation->house?->images ?? [] as $img)
          <div class="col-6 col-md-3">
            <img class="img-fluid rounded" src="{{ asset('storage/'.$img->url) }}" alt="house">
          </div>
        @empty
          <div class="text-muted">No house images.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
