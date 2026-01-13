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
    if (! $reservation->wasChanged('status_id')) {
        return;
    }

    $reservation->load('status', 'house', 'user');

    $receiver = $reservation->user;
    if (! $receiver) {
        return;
    }

    $receiver->notify(new ReservationStatusAccept($reservation));

    if (filter_var(env('ENABLE_FCM', false), FILTER_VALIDATE_BOOLEAN)) {
        try {
            $tokens = $receiver->devices()->pluck('token')->toArray();

            $locale = $receiver->locale ?? 'en';
            $status = $reservation->status?->type ?? 'unknown';

            $title = __('notifications.reservation_title', [], $locale);
            $body  = __('notifications.reservation_body_'.$status, [], $locale);

            app(FcmService::class)->sendToTokens(
                $tokens,
                $title,
                $body,
                [
                    'type' => 'reservation_status_changed',
                    'reservation_id' => (string) $reservation->id,
                    'status' => (string) $status,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('FCM failed', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
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
