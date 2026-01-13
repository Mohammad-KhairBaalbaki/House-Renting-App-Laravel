<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\User;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected AdminUserService $adminuserServic) {
        $this->adminuserServic=$adminuserServic;
    }

    public function show(User $user)
{
    $user->load(['roles','status','profileImage','idImage'])->loadCount('houses');;
    return view('admin.users.show', compact('user'));
}

    public function index(Request $request)
    {
        $users = $this->adminuserServic->list($request->only(['q','status_id']));
        $statuses = Status::whereNot("id",5)->whereNot("id",6)->orderBy('id')->get();

        return view('admin.users.index', compact('users','statuses'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status_id' => ['required','integer','exists:statuses,id'],
        ]);

        $this->adminuserServic->updateStatus($user, (int)$request->status_id);

        return back()->with('ok', 'Status updated successfully.');
    }
}