<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userservice;
    public function __construct(UserService $userservice)
    {
        $this->userservice=$userservice;
    }
    public function index(){
        $user=$this->userservice->show();
        return $this->success(new UserResource($user));

    }
     public function update(UpdateUserRequest $request){
        $user=$this->userservice->update($request->validated());
        return $this->success(new UserResource($user));

    }
    public function delete(){
        $user=$this->userservice->delete();
        return $this->success("","deleted succes");

    }
}
