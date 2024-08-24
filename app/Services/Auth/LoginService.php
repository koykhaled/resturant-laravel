<?php

namespace App\Services\Auth;
use App\Exceptions\UserExceptions\InvalidCredintialsException;
use App\Exceptions\UserExceptions\UserNotActiveException;
use App\Exceptions\UserExceptions\UserNotFoundException;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{

    public function authenticate($request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::with('addresses')->whereEmail($request->email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->is_active === 0) {
            throw new UserNotActiveException();
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            throw new InvalidCredintialsException();
        }
        return [
            "user" => $user,
            "access_token" => $token
        ];
    }
}