<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signin(LoginRequest $request)
    {
        $request->authenticate();

        return [
            'status' => 'success',
            'token' => auth()->user()->createToken('HRM API Access Token')->plainTextToken
        ];
    }

    public function signout()
    {
        
        auth()->user()->tokens()->delete();

        return [
            'status' => 'success',
            'message' => 'Successfully logged out',
        ];
    }
}
