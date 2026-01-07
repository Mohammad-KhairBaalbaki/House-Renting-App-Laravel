<?php

namespace App\Services\Admin;

use App\Models\Reservation;

class AdminReservationService
{
    public function list(array $filters = [])
    {
        $q = Reservation::query()
            ->with([
                'status:id,type',
                'user:id,first_name,last_name,phone',
                'house:id,title,rent_value,user_id,address_id',
                'house.firstImage:id,house_id,url',
                'house.address.city.governorate',
            ]);

        if (!empty($filters['q'])) {
            $term = trim($filters['q']);

            $q->where(function ($qq) use ($term) {
                $qq->where('id', $term)
                  ->orWhereHas('user', function ($u) use ($term) {
                      $u->where('phone', 'like', "%{$term}%")
                        ->orWhere('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                  })
                  ->orWhereHas('house', function ($h) use ($term) {
                      $h->where('title', 'like', "%{$term}%");
                  });
            });
        }

        if (!empty($filters['status_id'])) {
            $q->where('status_id', (int) $filters['status_id']);
        }

        if (!empty($filters['start_from'])) {
            $q->whereDate('start_date', '>=', $filters['start_from']);
        }
        if (!empty($filters['start_to'])) {
            $q->whereDate('start_date', '<=', $filters['start_to']);
        }

        if (!empty($filters['min_duration'])) {
            $q->where('duration', '>=', (int)$filters['min_duration']);
        }
        if (!empty($filters['max_duration'])) {
            $q->where('duration', '<=', (int)$filters['max_duration']);
        }

        if (!empty($filters['min_rent'])) {
            $q->whereHas('house', fn($h) => $h->where('rent_value', '>=', (float)$filters['min_rent']));
        }
        if (!empty($filters['max_rent'])) {
            $q->whereHas('house', fn($h) => $h->where('rent_value', '<=', (float)$filters['max_rent']));
        }

        $allowedSorts = ['created_at', 'start_date', 'end_date', 'rent_value'];
        $sortBy  = $filters['sort_by']  ?? 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'created_at';
        if (!in_array($sortDir, ['asc','desc'])) $sortDir = 'desc';

        if ($sortBy === 'rent_value') {
            $q->join('houses', 'reservations.house_id', '=', 'houses.id')
              ->select('reservations.*')
              ->orderBy('houses.rent_value', $sortDir);
        } else {
            $q->orderBy($sortBy, $sortDir);
        }

        return $q->paginate(10)->withQueryString();
    }
}
