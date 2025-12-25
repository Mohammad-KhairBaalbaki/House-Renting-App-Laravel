@extends('admin.layouts.dashboard')

@section('title','User Details')

@section('page')
@php
  $p = optional($user->profileImage)->url;
  $iimg = optional($user->idImage)->url;

  $status = optional($user->status)->type ?? '-';
  $roleNames = $user->roles->pluck('name')->values();

  $isOwner  = $user->roles->contains(fn($r) => $r->name === 'owner');
  $isRenter = $user->roles->contains(fn($r) => $r->name === 'renter');
@endphp

<div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
  <div>
    <h3 class="mb-1" style="font-weight:900;">User Details</h3>
    <div class="text-muted">Read-only profile</div>
  </div>

  <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark btn-sm">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

{{-- Header Card --}}
<div class="kpi reveal delay-2 user-hero mb-3">
  <div class="user-hero-row">
    <div class="user-avatar">
      @if($p)
        <img src="{{ asset('storage/'.$p) }}" alt="profile">
      @else
        <div class="user-avatar-fallback">
          {{ strtoupper(substr($user->first_name ?? 'U',0,1)) }}{{ strtoupper(substr($user->last_name ?? 'U',0,1)) }}
        </div>
      @endif
    </div>

    <div class="user-hero-main">
      <div class="user-name">
        {{ $user->first_name }} {{ $user->last_name }}
      </div>

      <div class="user-sub">
        <span class="me-2"><i class="bi bi-telephone"></i> {{ $user->phone }}</span>
        <span><i class="bi bi-calendar3"></i> {{ $user->date_of_birth }}</span>
      </div>

      <div class="chips mt-3">
        <span class="chip chip-primary">
          <i class="bi bi-shield-check"></i>
          Status: <strong class="ms-1">{{ $status }}</strong>
        </span>

        <span class="chip chip-sky">
          <i class="bi bi-clock-history"></i>
          Joined: <strong class="ms-1">{{ optional($user->created_at)->format('Y-m-d') }}</strong>
        </span>

        <span class="chip chip-soft">
          <i class="bi bi-person-badge"></i>
          Roles:
          <strong class="ms-1">
            {{ $roleNames->isEmpty() ? '-' : $roleNames->join(', ') }}
          </strong>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="grid-2 mb-3">
  {{-- Details --}}
  <div class="kpi reveal delay-2">
    <div class="d-flex align-items-center justify-content-between">
      <h6 class="m-0">Information</h6>
      <span class="text-muted small">User #{{ $user->id }}</span>
    </div>

    <div class="info-grid mt-3 info-grid-lg">
      <div class="info-item info-item-lg">
        <div class="label label-lg">First Name</div>
        <div class="value value-lg">{{ $user->first_name }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Last Name</div>
        <div class="value value-lg">{{ $user->last_name }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Phone</div>
        <div class="value value-lg">{{ $user->phone }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Date of Birth</div>
        <div class="value value-lg">{{ $user->date_of_birth }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Status</div>
        <div class="value value-lg">
          <span class="badge-pill badge-a">{{ $status }}</span>
        </div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Created At</div>
        <div class="value value-lg">{{ $user->created_at }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Updated At</div>
        <div class="value value-lg">{{ $user->updated_at }}</div>
      </div>

      <div class="info-item info-item-lg">
        <div class="label label-lg">Roles</div>
        <div class="value value-lg">
          <div class="badges mt-1">
            @forelse($user->roles as $i => $r)
              <span class="badge-pill {{ ['badge-a','badge-b','badge-c','badge-d','badge-e'][$i % 5] }}">
                {{ $r->name }}
              </span>
            @empty
              <span class="text-muted">-</span>
            @endforelse
          </div>
        </div>
      </div>

      {{-- Activity --}}
      <div class="info-item info-item-lg" style="grid-column: 1 / -1;">
        <div class="label label-lg">Activity</div>

        @if($isOwner)
          <div class="value value-lg">
            <div class="d-flex flex-wrap gap-2 align-items-center">
              <span class="chip chip-sky">
                <i class="bi bi-house-door"></i>
                Houses: <strong class="ms-1">{{ $user->houses_count ?? 0 }}</strong>
              </span>
              <span class="text-muted">Owner account</span>
            </div>
          </div>
        @elseif($isRenter)
          <div class="value value-lg">
            <div class="d-flex flex-wrap gap-2 align-items-center">
              <span class="chip chip-soft">
                <i class="bi bi-receipt"></i>
                Rents: <strong class="ms-1">Coming soon</strong>
              </span>
              <span class="text-muted">Renter account</span>
            </div>
          </div>
        @else
          <div class="value value-lg text-muted">No owner/renter activity.</div>
        @endif

      </div>
    </div>
  </div>

  {{-- Images --}}
  <div class="kpi reveal delay-2">
    <h6 class="m-0">Images</h6>

    <div class="mt-3">
      <div class="img-section mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="text-muted small">Profile Image</div>
          @if($p)
            <a class="btn btn-sm btn-outline-dark" target="_blank" href="{{ asset('storage/'.$p) }}">
              <i class="bi bi-box-arrow-up-right"></i> Open
            </a>
          @endif
        </div>

        @if($p)
          <a class="img-box" href="{{ asset('storage/'.$p) }}" target="_blank">
            <img src="{{ asset('storage/'.$p) }}" alt="profile" class="img-big">
          </a>
        @else
          <div class="text-muted">No profile image</div>
        @endif
      </div>

      <div class="img-section">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="text-muted small">ID Image</div>
          @if($iimg)
            <a class="btn btn-sm btn-outline-dark" target="_blank" href="{{ asset('storage/'.$iimg) }}">
              <i class="bi bi-box-arrow-up-right"></i> Open
            </a>
          @endif
        </div>

        @if($iimg)
          <a class="img-box" href="{{ asset('storage/'.$iimg) }}" target="_blank">
            <img src="{{ asset('storage/'.$iimg) }}" alt="id" class="img-big">
          </a>
        @else
          <div class="text-muted">No ID image</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
