<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use App\Models\House;
use App\Models\Status;
use App\Services\Admin\AdminHouseService;
use Illuminate\Http\Request;

class AdminHouseController extends Controller
{
    public function __construct(protected AdminHouseService $adminHouseService)
    {
        $this->adminHouseService = $adminHouseService;
    }

    public function index(Request $request)
    {
        $houses = $this->adminHouseService->list($request->only([
            'search',
            'status_id',
            'is_active',
            'governorate_id',
            'city_id',
            'rent',
            'min_rent',
            'max_rent',
            'min_space',
            'max_space',
            'rooms',
            'min_rooms',
            'max_rooms',
            'sort_by',
            'sort_dir'
        ]));

        $statuses = Status::whereNot("id", 5)->whereNot("id", 6)->orderBy('id')->get();
        $governorates = Governorate::orderBy('id')->get();
        $cities = City::orderBy('id')->get();

        return view('admin.houses.index', compact('houses', 'statuses', 'governorates', 'cities'));
    }



    public function show(House $house)
    {
        $house->load([
            'images',
            'status',
            'user',
            'address.city.governorate',
        ]);

        $currentGovId = optional(optional($house->address)->city)->governorate_id;

        $cities = City::with('governorate')
            ->when($currentGovId, fn($q) => $q->where('governorate_id', $currentGovId))
            ->orderBy('id')
            ->get();
        return view('admin.houses.show', compact('house', 'cities'));
    }
    public function updateCity(Request $request, House $house)
    {
        $data = $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
        ]);

        $this->adminHouseService->updateCity($house, (int) $data['city_id']);

        return back()->with('success', 'City updated successfully.');
    }

    public function updateStatus(Request $request, House $house)
    {
        $data = $request->validate([
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        $this->adminHouseService->updateStatus($house, (int) $data['status_id']);

        return back()->with('ok', 'House status updated successfully.');
    }
}
