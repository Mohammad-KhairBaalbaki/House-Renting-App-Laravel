<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data)
    {
        if(isset($data['role']))
        {
            $roleId = $data['role'];
            unset($data['role']);
        }
        $user = User::create($data);
        $role = Role::findOrFail($roleId);

        if ($role) {
            $user->assignRole($role);
        }

        $user = $user->fresh();
        $token = $user->createToken('api token')->plainTextToken;
        $user->access_token = $token;
        return $user->load('roles');
    }

    public function login(array $data)
    {
        if (isset($data['email'])) {
            // Attempt login with email
            $credentials = ['email' => $data['email'], 'password' => $data['password']];
        } elseif (isset($data['phone'])) {
            // Attempt login with phone
            $credentials = ['phone' => $data['phone'], 'password' => $data['password']];
        }
        if (!Auth::attempt($credentials)) {
            return false;
        }
        $user = Auth::user();
        $token = $user->createToken('api token')->plainTextToken;
        $user->access_token = $token;
        return $user->load('roles');
    }
}
