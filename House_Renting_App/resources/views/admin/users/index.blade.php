@extends('admin.layouts.dashboard')

@section('title', 'Users')

@section('page')
    <div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
        <div>
            <h3 class="mb-1" style="font-weight:900;">Users</h3>
            <div class="text-muted">List users, images, and update status</div>
        </div>
    </div>

    @if (session('ok'))
        <div class="alert alert-success reveal delay-1">{{ session('ok') }}</div>
    @endif

    <div class="kpi reveal delay-2">

        <form class="row g-2 mb-3" method="GET" action="{{ route('admin.users.index') }}">
            <div class="col-md-6">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search: name or phone">
            </div>

            <div class="col-md-4">
                <select class="form-select" name="status_id">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st->id }}" @selected((string) $st->id === request('status_id'))>
                            {{ $st->type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-lg-soft w-100" type="submit" style="padding:12px 14px;font-size:15px;">
                    Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Profile</th>
                        <th>ID Image</th>
                        <th>Status</th>
                        <th style="width:120px;">View</th>
                        <th style="width:200px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>

                            <td>
                                <div class="fw-bold">{{ $u->first_name }} {{ $u->last_name }}</div>
                                <div class="text-muted small">{{ optional($u->status)->type }}</div>
                            </td>

                            <td>{{ $u->phone }}</td>

                            <td>
                                @foreach ($u->roles as $i => $r)
                                    <span
                                        class="badge-pill {{ ['badge-a', 'badge-b', 'badge-c', 'badge-d', 'badge-e'][$i % 5] }}">
                                        {{ $r->name }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                @php $p = optional($u->profileImage)->url; @endphp
                                @if ($p)
                                    <a href="{{ asset('storage/' . $p) }}" target="_blank">
                                        <img class="thumb" src="{{ asset('storage/' . $p) }}" alt="profile">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @php $iimg = optional($u->idImage)->url; @endphp
                                @if ($iimg)
                                    <a href="{{ asset('storage/' . $iimg) }}" target="_blank">
                                        <img class="thumb" src="{{ asset('storage/' . $iimg) }}" alt="id">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Status (edit) --}}
                            <td>
                                <form method="POST" action="{{ route('admin.users.status', $u) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select class="form-select form-select-sm" name="status_id">
                                        @foreach ($statuses as $st)
                                            <option value="{{ $st->id }}" @selected($u->status_id == $st->id)>
                                                {{ $st->type }}
                                            </option>
                                        @endforeach
                                    </select>
                            </td>

                            {{-- View button --}}
                            <td>
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.users.show', $u) }}">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>

                            {{-- Action --}}
                            <td>
                                <button class="btn btn-sm btn-outline-dark" type="submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
