<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'platform' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        $user->devices()->updateOrCreate(
            ['token' => $data['token']],
            ['platform' => $data['platform'] ?? null, 'last_seen_at' => now()]
        );

        return $this->success(true, 'Device token saved');
    }
}

