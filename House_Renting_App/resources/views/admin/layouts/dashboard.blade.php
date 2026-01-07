@extends('admin.layouts.app')

@section('content')
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="brand">
      <div class="brand-logo">M</div>
      <div class="brand-text">
        <div class="brand-title">M.R Dar</div>
        <div class="brand-sub">Admin Dashboard</div>
      </div>
    </div>

    <nav class="admin-nav">
      <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
         href="{{ route('admin.dashboard') }}">
        <span>Welcome</span>
        <small>Home</small>
      </a>
          <a class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
     href="{{ route('admin.users.index') }}">
    <span>Users</span>
    <small>Manage</small>
  </a>

      {{-- later --}}
     <a class="nav-item {{ request()->routeIs('admin.houses.*') ? 'active' : '' }}"
   href="{{ route('admin.houses.index') }}">
  <span>Houses</span>
  <small>Manage</small>
</a>
<a class="nav-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}"
   href="{{ route('admin.reservations.index') }}">
  <span>Reservations</span>
  <small>Browse</small>
</a>



  
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <div class="me">
        <div class="me-name">{{ auth()->user()->first_name ?? 'admin' }}</div>
        <div class="me-sub">{{ auth()->user()->phone ?? '' }}</div>
      </div>

      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-outline-dark btn-sm">Logout</button>
      </form>
    </div>

    <div class="admin-content">
      @yield('page')
    </div>
  </main>
</div>
@endsection
