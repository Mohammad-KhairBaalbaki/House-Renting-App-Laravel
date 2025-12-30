<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\HouseResource;
use App\Http\Resources\ReservationResource;
use App\Models\House;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    //
    protected $reservationService;
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }
    public function index()
    {
        $data = $this->reservationService->index();
        return $this->success(ReservationResource::collection($data), 'Reservations retrieved successfully');
    }
    public function canRent(){
        return $this->success(true, 'you can rent',200);
    }
    public function store(StoreReservationRequest $request)
    {
        $data = $this->reservationService->store($request->validated());
        if ($data === '1') {
            return $this->success(false, 'you have already requested to rent this house at this time', 400);
        } elseif ($data === '2') {
            return $this->success(false, 'This house is busy at this time', 400);
        }
        return $this->success(ReservationResource::make($data), 'Reservations stored successfully');
    }
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $data = $this->reservationService->update($request->validated(), $reservation);
        return $this->success(ReservationResource::make($data), 'Reservation updated successfully');
    }
    public function destroy(Reservation $reservation)
    {
        $data = $this->reservationService->delete($reservation);
        return $this->success(true, 'Reservation deleted successfully');
    }

    public function showReservationsOfHouse(House $house)
    {
        $data = $this->reservationService->showReservationsOfHouse($house);
        return $this->success(ReservationResource::collection($data), 'Reservations retrieved successfully');
    }

    public function myReservationsAndRents()
    {
        if (Auth::user()->hasRole('owner')) {
            $data = $this->reservationService->myReservations();

        } elseif (Auth::user()->hasRole('renter')) {
            $data = $this->reservationService->myRents();
        }
        return $this->success(ReservationResource::collection($data), 'Reservations retrieved successfully');
    }

    public function cancelReservation(Reservation $reservation)
    {
        $data = $this->reservationService->cancelReservation($reservation);
        if ($data === '1') {
            return $this->success(false, 'you cant cancel this reservation because its not yours', 400);
        } elseif ($data === '2') {
            return $this->success(false, 'you cant cancel this reservation because it is already cancelled', 400);
        } elseif ($data === '3') {
            return $this->success(false, 'you cant cancel this reservation because it has been accepted or rejected by the owner', 400);
        }
        return $this->success(ReservationResource::make($data), 'Reservation canceled successfully');
    }

    public function rejectReservation(Reservation $reservation)
    {
        $data = $this->reservationService->rejectReservation($reservation);
        if ($data === '1') {
            return $this->success(false, 'you cant cancel this reservation because its not yours', 400);
        } elseif ($data === '2') {
            return $this->success(false, 'you cant reject this reservation because it is already rejected', 400);
        } elseif ($data === '3') {
            return $this->success(false, 'you cant reject this reservation because it has been accepted by yours', 400);
        }
        return $this->success(ReservationResource::make($data), 'Reservation rejected successfully');
    }

    public function acceptReservation(Reservation $reservation)
    {
        $data = $this->reservationService->acceptReservation($reservation);
        if ($data === '1') {
            return $this->success(false, 'you cant accept this reservation because its not yours', 400);
        } elseif ($data === '2') {
            return $this->success(false, 'you cant accept this reservation because it is already accepted', 400);
        } elseif ($data === '3') {
            return $this->success(false, 'you cant accept this reservation because it has been rejected by yours', 400);
        }
        return $this->success(ReservationResource::make($data), 'Reservation accepted successfully',200);
    }
}
