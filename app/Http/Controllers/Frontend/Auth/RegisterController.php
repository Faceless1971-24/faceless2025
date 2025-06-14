<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        // Generate a unique userid
        $uniqueId = 'USER' . uniqid();

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'userid' => $uniqueId, // Add a default unique userid
            'status' => 1, // Active
            'is_admin' => 0, // Not admin
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}