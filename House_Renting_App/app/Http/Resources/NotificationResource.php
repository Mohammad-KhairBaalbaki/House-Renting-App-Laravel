<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = is_array($this->data) ? $this->data : [];

        $status = $data['status'] ?? null;

        return [
            'type' => $data['type'] ?? null,

           // 'status' => $status,

            'status' => $status
                ? __('notifications.status_' . $status)
                : null,

            'title' => $status
                ? __('notifications.title_' . $status)
                : null,

            'message' => $status
                ? __('notifications.message_' . $status)
                : null,

            'house' => $data['house'] ?? null,

            'reservation_id' => $data['reservation_id'] ?? null,
            'house_id' => $data['house_id'] ?? null,

            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
