<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function show()
    {
        $user = User::where("id", Auth::id())->first();
        return $user->load('roles');
    }
    public function update(array $request)
    {
        $user = User::where("id", Auth::id())->first();
        $user->update($request);

        if (isset($request['profile_image'])) {
            $oldUrl = $user->images()->where('type', 'profile_image')->first();
            if ($oldUrl)
                Storage::disk('public')->delete($oldUrl);

            $user->images()
                ->where('type', 'profile_image')
                ->delete();

            if (isset($request['profile_image'])) {
                $path = 'profile_images';
                $file = $request['profile_image'];
                $user->images()->create([
                    'user_id' => $user->id,
                    'url' => ImageService::uploadImage($file, $path),
                    'type' => 'profile_image'
                ]);
            }
        }
        return $user->load('roles', 'images');
    }
    public function delete()
    {
        $user = User::where("id", Auth::id())->first();
        $user->tokens()->delete();
        return $user->delete();


    }
}
