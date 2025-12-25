@extends('admin.layouts.app')

@section('title', 'Admin Login')
@section('body_class', 'admin-auth-bg')

@section('content')
<div class="auth-screen">
  {{-- background decorations --}}
  <div class="auth-bg-orb orb-1"></div>
  <div class="auth-bg-orb orb-2"></div>
  <div class="auth-wave"></div>

  <div class="auth-wrap auth-wrap-single">
    <div class="auth-card auth-card-tall reveal delay-1" dir="ltr">
      <div class="auth-card-top">
        <div class="auth-brand reveal delay-2">
          <div class="auth-logo">
            <i class="bi bi-building"></i>
          </div>
          <div>
            <div class="auth-brand-title">M.R Dar</div>
            <div class="auth-brand-sub">Admin Panel</div>
          </div>
        </div>

        <h1 class="auth-title reveal delay-2">Welcome Back</h1>
        <p class="auth-sub reveal delay-3">Sign in with your phone & password</p>
      </div>

      <div class="auth-card-body auth-card-body-tall">
        <form method="POST" action="{{ route('admin.login') }}" novalidate>
          @csrf

          {{-- Phone --}}
          <label class="auth-label reveal delay-2" for="adminPhone">Phone</label>
          <div class="input-group field-lg mb-2 reveal delay-2">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input
              id="adminPhone"
              type="tel"
              class="form-control"
              name="phone"
              placeholder="e.g. 09xxxxxxxx"
              value="{{ old('phone') }}"
              required
              autocomplete="username"
            />
          </div>
          @error('phone')
            <div class="form-error mb-3">{{ $message }}</div>
          @enderror

          {{-- Password --}}
          <label class="auth-label reveal delay-2" for="adminPassword">Password</label>
          <div class="input-group field-lg mb-2 reveal delay-2">
            <button class="input-group-text" type="button"
                    data-toggle-password="#adminPassword"
                    aria-label="Show/Hide password">
              <i class="bi bi-eye"></i>
            </button>

            <input
              id="adminPassword"
              type="password"
              class="form-control"
              name="password"
              placeholder="Enter password"
              required
              autocomplete="current-password"
            />
          </div>
          @error('password')
            <div class="form-error mb-3">{{ $message }}</div>
          @enderror

          <button class="btn btn-login w-100 reveal delay-3" type="submit">
            Log In <i class="bi bi-arrow-right-short ms-1"></i>
          </button>

          <div class="auth-note reveal delay-3">
            © {{ date('Y') }} M.R Dar — Admin Access Only
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
