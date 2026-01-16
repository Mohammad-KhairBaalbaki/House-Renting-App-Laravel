<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Status;
use App\Services\Admin\AdminReservationService;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function __construct(protected AdminReservationService $service)
    {
    }

    public function index(Request $request)
    {
        $reservations = $this->service->list($request->only([
            'q',
            'status_id',
            'min_rent',
            'max_rent',
            'start_from',
            'start_to',
            'min_duration',
            'max_duration',
            'sort_by',
            'sort_dir'
        ]));

        $statuses = Status::orderBy('id')->get();

        return view('admin.reservations.index', compact('reservations', 'statuses'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'status',
            'user.profileImage',
            'user.idImage',
            'house.images',
            'house.user:id,first_name,last_name,phone',
            'house.address.city.governorate',
        ]);

        return view('admin.reservations.show', compact('reservation'));
    }
}
