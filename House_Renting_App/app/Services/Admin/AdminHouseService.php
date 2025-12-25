<?php

namespace App\Services\Admin;

use App\Models\House;

class AdminHouseService
{
    public function list(array $filters = [])
    {
        $q = House::query()
            ->with([
                'user:id,first_name,last_name,phone',
                'status:id,type',
                'firstImage:id,house_id,url',
                'address.city.governorate'
            ]);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $q->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('rooms', 'like', "%{$search}%")
                    ->orWhere('space', 'like', "%{$search}%")
                    ->orWhereHas('address.city', function ($city) use ($search) {
                        $city->where('name->ar', 'like', "%{$search}%")
                             ->orWhere('name->en', 'like', "%{$search}%");
                    })
                    ->orWhereHas('address.city.governorate', function ($gov) use ($search) {
                        $gov->where('name->ar', 'like', "%{$search}%")
                            ->orWhere('name->en', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('phone', 'like', "%{$search}%")
                          ->orWhere('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['city_id'])) {
            $cityId = (int) $filters['city_id'];
            $q->whereHas('address.city', fn($c) => $c->where('id', $cityId));
        }

        if (!empty($filters['governorate_id'])) {
            $govId = (int) $filters['governorate_id'];
            $q->whereHas('address.city.governorate', fn($g) => $g->where('id', $govId));
        }

        if (!empty($filters['status_id'])) {
            $q->where('status_id', (int)$filters['status_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $q->where('is_active', (bool)$filters['is_active']);
        }

        if (!empty($filters['rent'])) {
            $q->where('rent_value', (float)$filters['rent']);
        }
        if (!empty($filters['min_rent'])) {
            $q->where('rent_value', '>=', (float)$filters['min_rent']);
        }
        if (!empty($filters['max_rent'])) {
            $q->where('rent_value', '<=', (float)$filters['max_rent']);
        }

        if (!empty($filters['min_space'])) {
            $q->where('space', '>=', (float)$filters['min_space']);
        }
        if (!empty($filters['max_space'])) {
            $q->where('space', '<=', (float)$filters['max_space']);
        }

        if (!empty($filters['min_rooms'])) {
            $q->where('rooms', '>=', (int)$filters['min_rooms']);
        }
        if (!empty($filters['max_rooms'])) {
            $q->where('rooms', '<=', (int)$filters['max_rooms']);
        }
        if (!empty($filters['rooms'])) {
            $q->where('rooms', (int)$filters['rooms']);
        }

        $allowedSorts = ['rent_value', 'space', 'rooms', 'created_at'];
        $sortBy  = $filters['sort_by']  ?? 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');

        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'created_at';
        if (!in_array($sortDir, ['asc','desc'])) $sortDir = 'desc';

        $q->orderBy($sortBy, $sortDir);

        return $q->paginate(10)->withQueryString();
    }

    public function updateStatus(House $house, int $statusId): House
    {
        $house->update(['status_id' => $statusId]);
        return $house->fresh(['status','user']);
    }
    public function updateCity(House $house, int $cityId): House
{
    $address = $house->address;


    $address->update(['city_id' => $cityId]);
    return $house->fresh(['address.city.governorate']);
}
}
