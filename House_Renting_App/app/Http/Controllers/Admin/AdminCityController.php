<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;

class AdminCityController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $governorateId = $request->get('governorate_id');

        $cities = City::query()
            ->with('governorate')
            ->when($governorateId, fn($qr) => $qr->where('governorate_id', $governorateId))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name->en', 'like', "%{$q}%")
                        ->orWhere('name->ar', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        $governorates = Governorate::orderBy('id')->get();

        return view('admin.cities.index', compact('cities', 'governorates'));
    }

    public function create()
    {
        $governorates = Governorate::orderBy('id')->get();
        return view('admin.cities.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'governorate_id' => ['required', 'integer', 'exists:governorates,id'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
        ]);

        City::create([
            'governorate_id' => (int) $data['governorate_id'],
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'],
            ],
        ]);

        return redirect()
            ->route('admin.cities.index')
            ->with('ok', 'City created successfully.');
    }
}
