<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function login(array $data): bool
    {
        $credentials = [
            'phone' => $data['phone'],
            'password' => $data['password'],
        ];

        if (!Auth::attempt($credentials, true)) {
            return false;
        }

        $user = Auth::user();
        if (!$user || !$user->hasRole('admin')) {
            Auth::logout();
            return false;
        }

        request()->session()->regenerate();
        return true;
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
