@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="m-0">لوحة تحكم الأدمن</h4>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-outline-dark btn-sm">تسجيل خروج</button>
            </form>
        </div>

        <div class="card p-3">
            <div>أهلًا {{ auth()->user()->first_name }} 👋</div>
            <div class="text-muted small">هون رح نبني إدارة الشقق/الحجوزات/المستخدمين…</div>
        </div>
    </div>
@endsection
