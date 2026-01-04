<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusAccept extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $reservation;
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        $status = $this->reservation->status?->type ?? 'unknown';
        $now = now();
        return [
            'type' => 'reservation_status_changed',
            'reservation_id' => $this->reservation->id,
            'house_id' => $this->reservation->house_id,
            'status' => $status,
            "house" => $this->reservation->house?->title,
            'date' => $now->toDateString(),
            'time' => $now->format('H:i'),
            'title' => $this->titleByStatus($status),
            'message' => $this->messageByStatus($status),
        ];
    }
    private function messageByStatus(string $status): string
    {
        return match ($status) {
            'accepted' => 'Your reservation has been accepted by the owner.',
            'rejected' => 'Your reservation has been rejected by the owner.',
            'cancelled' => 'The reservation has been cancelled.',
            default => "Reservation status changed to: {$status}",
        };
    }
    private function titleByStatus(string $status): string
    {
        return match ($status) {
            'accepted' => 'Reservation Accepted',
            'rejected' => 'Reservation Rejected',
            'cancelled' => 'Reservation Cancelled',
            default => 'Reservation Updated',
        };
    }
}
