@extends('admin.layouts.dashboard')

@section('title', 'Create City')

@section('page')
    <div class="d-flex align-items-center justify-content-between mb-3 reveal delay-1">
        <div>
            <h2 class="mb-1" style="font-weight:950;">Create City</h2>
            <div class="text-muted">Add a city linked to an existing governorate</div>
        </div>

        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.cities.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="kpi reveal delay-2">
        <form method="POST" action="{{ route('admin.cities.store') }}" class="p-3">
            @csrf

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label small text-muted mb-1">Governorate</label>
                    <select name="governorate_id" class="form-select @error('governorate_id') is-invalid @enderror"
                        required>
                        <option value="" disabled @selected(old('governorate_id') === null)>Select governorate</option>

                        @foreach ($governorates as $g)
                            @php
                                $gEn = $g->getTranslation('name', 'en', false) ?: '';
                                $gAr = $g->getTranslation('name', 'ar', false) ?: '';
                                $gLabel = trim($gEn . ' / ' . $gAr, ' /');
                            @endphp
                            <option value="{{ $g->id }}" @selected((string) $g->id === (string) old('governorate_id'))>
                                {{ $gLabel ?: '#' . $g->id }}
                            </option>
                        @endforeach
                    </select>

                    @error('governorate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6"></div>

                <div class="col-lg-6">
                    <label class="form-label small text-muted mb-1">City Name (English)</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                        class="form-control @error('name_en') is-invalid @enderror" placeholder="e.g. Damascus" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label class="form-label small text-muted mb-1">City Name (Arabic)</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" dir="rtl"
                        class="form-control @error('name_ar') is-invalid @enderror" placeholder="مثال: دمشق" required>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.cities.index') }}">Cancel</a>
                <button class="btn btn-lg-soft btn-sm" type="submit">
                    <i class="bi bi-check2"></i> Save
                </button>
            </div>
        </form>
    </div>
@endsection
