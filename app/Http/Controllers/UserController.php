<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function register(UserRegisterRequest $request) : JsonResponse | UserResource
    {
        $data = $request->validated();
        if(User::query()->where('email', $data['email'])->count() == 1){
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => 'The email has already been taken'
                ]
            ]));
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);

    }

    public function login(UserLoginRequest $request) : UserResource
    {
        $data = $request->validated();
        $user = User::query()->where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => 'email or password is incorrect'
                ]
            ],400));
        }
        $token = Str::uuid()->toString();
        $user->token = $token;
        $user->save();
        return new UserResource($user);
    }

    public function get() : UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request) : JsonResponse | UserResource
    {
        $data = $request->validated();
        $user = Auth::user();
        if (isset($data['name'])){
            $user->name = $data['name'];
        }
        if (isset($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return (new UserResource($user))
            ->response()
            ->header('Authorization', $user->token);
    }
}
