<?php

namespace App\Services;

use App\Http\Requests\StoreReservationRequest;
use App\Models\House;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ReservationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $reservations = Reservation::with("house.images", "house.address.city.governorate", "user", "status")->get();
        return $reservations;
    }
    public function store(array $data)
    {
        if (Reservation::where('house_id', $data['house_id'])->where('start_date', $data['start_date'])->where('user_id', Auth::id())->exists()) {
            return '1';
        } elseif (!($this->canReserve($data['house_id'], $data['start_date'], $this->calculateEndDate($data['start_date'], $data['duration'])))) {
            return '2';
        }
        $reservation = Reservation::create([
            'house_id' => $data['house_id'],
            'user_id' => Auth::id(),
            'start_date' => $data['start_date'],
            'duration' => $data['duration'],
            'end_date' => $this->calculateEndDate($data['start_date'], $data['duration']),
            'status_id' => 1,
        ]);
        return $reservation->load('house.images', 'house.address.city.governorate', 'user', 'status');
    }
    public function update(array $data, Reservation $reservation)
    {

    }
    public function delete(Reservation $reservation)
    {

    }

    public function showReservationsOfHouse(House $house)
    {
        $data = $house->reservations->where('status_id', operator: 2)->load('user','status');
        return $data;
    }

    private function calculateEndDate(string|Carbon $startDate, int $durationMonths): Carbon
    {
        if ($durationMonths <= 0) {
            throw new InvalidArgumentException('duration must be > 0');
        }

        $start = $startDate instanceof Carbon ? $startDate->copy() : Carbon::parse($startDate);

        // [start, end) end is exclusive
        return $start->addMonthsNoOverflow($durationMonths);
    }

    public function canReserve(
        int $houseId,
        string|Carbon $startDate,
        string|Carbon $endDate
    ): bool {
        $start = $startDate instanceof Carbon ? $startDate->copy() : Carbon::parse($startDate);
        $end = $endDate instanceof Carbon ? $endDate->copy() : Carbon::parse($endDate);

        $overlapExists = Reservation::query()
            ->where('house_id', $houseId)
            ->where('status_id', 2)//accepted
            ->where('start_date', '<=', $end)
            ->where('end_date', '>=', $start)
            ->lockForUpdate()
            ->exists();

        if ($overlapExists) {
            return false;
        }

        return true;
    }

    public function myReservations()
    {
        $data = Reservation::whereHas('house', function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('id', Auth::id());
            });
        });
        return $data->with('house.images', 'house.address.city.governorate', 'status', 'user','house.status')->get();
    }

    public function myRents()
    {
        $data = Reservation::where('user_id', Auth::id());
        return $data->with('house.images', 'house.address.city.governorate', 'status','house.status')->get();
        
    }

    public function cancelReservation(Reservation $reservation)
    {
        if ($reservation->user_id != Auth::id()) {
            return '1';
        }
        if ($reservation->status_id == 5) {
            return '2';
        }
        if ($reservation->status_id != 1) {
            return '3';
        }
        // $reservation->status_id = 5;
        $reservation->update(attributes: ['status_id' => 5]);

        $reservation->save();
        return $reservation->load('house.images', 'house.address.city.governorate', 'status');
    }

    public function rejectReservation(Reservation $reservation)
    {
        if ($reservation->house->user_id != Auth::id()) {
            return '1';
        }
        if ($reservation->status_id == 3) {
            return '2';
        }
        if ($reservation->status_id != 1) {
            return '3';
        }
        // $reservation->status_id = 3;
        $reservation->update(attributes: ['status_id' => 3]);

        $reservation->save();
        return $reservation->load('house.images', 'house.address.city.governorate', 'status');
    }

    public function acceptReservation(Reservation $reservation)
    {
        if ($reservation->house->user_id != Auth::id()) {
            return '1';
        }
        if ($reservation->status_id == 2) {
            return '2';
        }
        if ($reservation->status_id != 1) {
            return '3';
        }
        // $reservation->status_id = 2;
        $reservation->update(attributes: ['status_id' => 2]);
        $reservation->save();

        // Reject all other PENDING reservations that overlap
        Reservation::where('house_id', $reservation->house_id)
            ->where('id', '!=', $reservation->id)
            ->where('status_id', 1) // pending only
            ->where(function ($q) use ($reservation) {
                $q->where('start_date', '<=', $reservation->end_date)
                    ->where('end_date', '>=', $reservation->start_date);
            })
            ->update(['status_id' => 3]); // rejected
        return $reservation->load('house.images', 'house.address.city.governorate', 'status');
    }
}
