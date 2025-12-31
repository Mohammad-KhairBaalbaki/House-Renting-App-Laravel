<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Notifications\ReservationStatusAccept;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
$reservation->user->notify(new ReservationStatusAccept($reservation)); 

// 2) FCM (اختياري) — اشتغل بس إذا ENABLE_FCM=true
if (env('ENABLE_FCM') === true || env('ENABLE_FCM') === 'true') {
    try {
        $tokens = $reservation->user->devices()->pluck('token')->toArray();

        app(\App\Services\FcmService::class)->sendToTokens(
            $tokens,
            'Reservation Update',
            'Your reservation status is now: ' . ($reservation->status?->type ?? ''),
            [
                'type' => 'reservation_status_changed',
                'reservation_id' => (string) $reservation->id,
                'status' => (string) ($reservation->status?->type ?? ''),
            ]
        );
    } catch (\Throwable $e) {
        Log::error('FCM failed', ['error' => $e->getMessage()]);
    }
}

}

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     */
    public function forceDeleted(Reservation $reservation): void
    {
        //
    }
}
