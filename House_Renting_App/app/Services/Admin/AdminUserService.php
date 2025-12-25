<?php

namespace App\Services\Admin;

use App\Models\Status;
use App\Models\User;

class AdminUserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function list(array $filters = [])
    {
        $q = User::query()
            ->with(['roles','status','profileImage','idImage'])
            ->latest();

        if (!empty($filters['q'])) {
            $term = $filters['q'];
            $q->where(function($qq) use ($term){
                $qq->where('first_name','like',"%$term%")
                   ->orWhere('last_name','like',"%$term%")
                   ->orWhere('phone','like',"%$term%");
            });
        }

        if (!empty($filters['status_id'])) {
            $q->where('status_id', (int)$filters['status_id']);
        }

        return $q->paginate(10)->withQueryString();
    }

    public function updateStatus(User $user, int $statusId): User
    {
        $status = Status::findOrFail($statusId);
        $user->update(['status_id' => $status->id]);
        return $user->fresh(['roles','status','profileImage','idImage']);
    }
}
