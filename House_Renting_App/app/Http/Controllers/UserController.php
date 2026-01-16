<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userservice;
    public function __construct(UserService $userservice)
    {
        $this->userservice = $userservice;
    }
    public function index()
    {
        $user = $this->userservice->show();
        return $this->success(new UserResource($user));

    }
    public function update(UpdateUserRequest $request)
    {
        $user = $this->userservice->update($request->validated());
        return $this->success(new UserResource($user));

    }
    public function delete(DeleteUserRequest $request)
    {
        $user = $this->userservice->delete($request);
        if (!$user) {
            return $this->success(false, "you can't delete your account because there are others reserved one of your houses !", 400);
        }
        return $this->success("", "deleted succes");

    }
}
