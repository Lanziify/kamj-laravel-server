<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create($fields);

        event(new Registered($user));

        return [
            'user' => $user,
        ];
    }
    public function login(LoginRequest $request)
    {
        $token = $request->authenticate();

        return [
            'access_token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }
    public function logout()
    {
        return Auth::logout();
    }
    public function verifyEmail($id, $hash)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            return false;
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return [
            "message" => "Email successfully verified"
        ];
    }
    public function refresh()
    {
        $token = JWTAuth::getToken();

        return [
            'access_token' => JWTAuth::refresh($token),
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }
    // public function user()
    // {
    //     return auth()->user();
    // }

    public function checkTooManyFailedAttempts()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // throw new Exception('IP address banned. Too many login attempts.');
        return response()->json(
            [
                "field" => "password",
                "message" => "Too many login attempts",
            ],
            401
        );
    }

    public function throttleKey()
    {
        return Str::lower(request('email'));
    }
}
