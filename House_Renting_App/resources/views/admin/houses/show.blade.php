@extends('admin.layouts.dashboard')

@section('title', 'House Details')

@section('page')
    @php
        $city = optional(optional($house->address)->city);
        $gov = optional($city->governorate);

        $cityName = is_array($city->name ?? null)
            ? $city->name['en'] ?? ($city->name['ar'] ?? reset($city->name))
            : $city->name ?? '';
        $govName = is_array($gov->name ?? null)
            ? $gov->name['en'] ?? ($gov->name['ar'] ?? reset($gov->name))
            : $gov->name ?? '';

        $cover = optional($house->images->first())->url;

        $currentCityId = optional(optional($house->address)->city)->id;

        $lat = optional($house->address)->latitude;
        $lng = optional($house->address)->longitude;

        // map url
        $mapQuery = trim(
            implode(
                ', ',
                array_filter([$govName ?: null, $cityName ?: null, optional($house->address)->street ?: null]),
            ),
        );

        $mapUrl =
            $lat && $lng
                ? "https://www.google.com/maps?q={$lat},{$lng}"
                : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapQuery ?: 'Syria');

        $addressText = trim(
            implode(
                ', ',
                array_filter([
                    $govName ?: null,
                    $cityName ?: null,
                    optional($house->address)->street ?: null,
                    optional($house->address)->flat_number ? 'Flat ' . $house->address->flat_number : null,
                ]),
            ),
        );
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
        <div>
            <h2 class="mb-1" style="font-weight:950;">House Details</h2>
            <div class="text-muted">Full preview</div>
        </div>

        <a href="{{ route('admin.houses.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- HERO --}}
    <div class="house-hero reveal delay-2 mb-3">
        <div class="house-hero-cover">
            <button type="button" class="fit-toggle" data-fit-toggle>Fit</button>

            @if ($cover)
                <img class="cover-img is-cover" src="{{ asset('storage/' . $cover) }}" alt="cover">
            @else
                <div class="cover-fallback">
                    <i class="bi bi-house-door"></i>
                </div>
            @endif
        </div>

        <div class="house-hero-body">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="house-title">{{ $house->title ?? 'No title' }}</div>

                    <div class="house-sub text-muted">
                        {{ $govName }} {{ $govName && $cityName ? '-' : '' }} {{ $cityName }}
                        @if ($house->address)
                            • {{ $house->address->street }} ({{ $house->address->flat_number }})
                        @endif
                    </div>

                    <div class="chips mt-3">
                        <span class="chip chip-primary">
                            <i class="bi bi-shield-check"></i>
                            Status: <strong class="ms-1">{{ optional($house->status)->type ?? '-' }}</strong>
                        </span>

                        <span class="chip chip-sky">
                            <i class="bi bi-person"></i>
                            Owner:
                            <strong class="ms-1">
                                {{ optional($house->user)->first_name }} {{ optional($house->user)->last_name }}
                            </strong>
                        </span>

                        <span class="chip chip-soft">
                            <i class="bi bi-telephone"></i>
                            <strong class="ms-1">{{ optional($house->user)->phone }}</strong>
                        </span>

                        @if ($house->is_active)
                            <span class="chip chip-green"><i class="bi bi-check2-circle"></i> Active</span>
                        @else
                            <span class="chip chip-red"><i class="bi bi-x-circle"></i> Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="house-kpis">
                    <div class="hk">
                        <div class="hk-label">Rent</div>
                        <div class="hk-value">{{ $house->rent_value }}</div>
                    </div>
                    <div class="hk">
                        <div class="hk-label">Rooms</div>
                        <div class="hk-value">{{ $house->rooms }}</div>
                    </div>
                    <div class="hk">
                        <div class="hk-label">Space</div>
                        <div class="hk-value">{{ $house->space }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAILS + LOCATION --}}
    <div class="grid-2 mb-3">
        {{-- DETAILS --}}
        <div class="kpi reveal delay-2">
            <h5 class="m-0" style="font-weight:900;">Details</h5>

            <div class="info-grid mt-3 info-grid-lg">
                <div class="info-item info-item-lg">
                    <div class="label label-lg">Created</div>
                    <div class="value value-lg">{{ $house->created_at }}</div>
                </div>

                <div class="info-item info-item-lg">
                    <div class="label label-lg">Updated</div>
                    <div class="value value-lg">{{ $house->updated_at }}</div>
                </div>

                <div class="info-item info-item-lg" style="grid-column:1/-1;">
                    <div class="label label-lg">Description</div>
                    <div class="value value-lg" style="font-size:16px;line-height:1.7;">
                        {{ $house->description ?? '—' }}
                    </div>
                </div>

                <div class="info-item info-item-lg" style="grid-column:1/-1;">
                    <div class="label label-lg">Notes</div>
                    <div class="value value-lg" style="font-size:16px;line-height:1.7;">
                        {{ $house->notes ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- LOCATION --}}
        <div class="kpi reveal delay-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="m-0" style="font-weight:900;">Location</h5>
            </div>

            <div class="row g-3 mt-2">
                {{-- LEFT: Update City + read-only fields --}}
                <div class="col-lg-8">

                    @if (session('success'))
                        <div class="alert alert-success py-2 mb-2">{{ session('success') }}</div>
                    @endif

                    {{-- ✅ Update City Only (Cities list already filtered by house governorate in controller) --}}
                    <form method="POST" action="{{ route('admin.houses.city', $house) }}" class="mt-2">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="auth-label" style="font-size:13px;font-weight:900;">City</label>

                                <select name="city_id" class="form-select" style="border-radius:14px;padding:12px;"
                                    required>
                                    <option value="">Select City</option>

                                    @foreach ($cities as $c)
                                        @php
                                            $cName = is_array($c->name ?? null)
                                                ? $c->name['en'] ?? ($c->name['ar'] ?? reset($c->name))
                                                : $c->name ?? '';
                                        @endphp
                                        <option value="{{ $c->id }}"
                                            {{ (int) $currentCityId === (int) $c->id ? 'selected' : '' }}>
                                            {{ $cName }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('city_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror

                                @if ($cities->isEmpty())
                                    <div class="small text-muted mt-1">No cities available for this house governorate.</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <button class="btn btn-login w-100" type="submit" style="padding:14px;border-radius:16px;">
                                    Update City
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- read-only --}}
                    <div class="info-grid mt-3 info-grid-lg">
                        <div class="info-item info-item-lg">
                            <div class="label label-lg">Governorate</div>
                            <div class="value value-lg">{{ $govName ?: '—' }}</div>
                        </div>

                        <div class="info-item info-item-lg">
                            <div class="label label-lg">City</div>
                            <div class="value value-lg">{{ $cityName ?: '—' }}</div>
                        </div>

                        <div class="info-item info-item-lg">
                            <div class="label label-lg">Latitude</div>
                            <div class="value value-lg">{{ $lat ?? '—' }}</div>
                        </div>

                        <div class="info-item info-item-lg">
                            <div class="label label-lg">Longitude</div>
                            <div class="value value-lg">{{ $lng ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: address + map --}}
                <div class="col-lg-4">
                    <div class="addr-card">
                        <div class="addr-title">
                            <i class="bi bi-map"></i>
                            Address
                        </div>

                        <div class="addr-text">
                            {{ $addressText ?: '—' }}
                        </div>

                        <div class="addr-actions mt-3">
                            <a class="btn btn-lg-soft w-100" href="{{ $mapUrl }}" target="_blank" rel="noopener">
                                <i class="bi bi-geo-alt-fill"></i> Open Location
                            </a>
                        </div>

                        @if ($lat && $lng)
                            <div class="small text-muted mt-2">
                                Using coordinates: <strong>{{ $lat }}</strong>,
                                <strong>{{ $lng }}</strong>
                            </div>
                        @else
                            <div class="small text-muted mt-2">
                                Coordinates not provided — searching by address text.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- IMAGES --}}
    <div class="kpi reveal delay-3">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="m-0" style="font-weight:900;">Images</h5>
            <span class="text-muted small">{{ $house->images->count() }} image(s)</span>
        </div>

        @if ($house->images->isEmpty())
            <div class="text-muted mt-3">No images.</div>
        @else
            <div class="house-gallery-xl mt-3">
                @foreach ($house->images as $img)
                    <a class="img-box img-box-xl" href="{{ asset('storage/' . $img->url) }}" target="_blank">
                        <img class="img-big img-big-xl" src="{{ asset('storage/' . $img->url) }}" alt="house-image">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
