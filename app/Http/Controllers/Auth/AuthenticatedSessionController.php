<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CompanySetting;
use App\Models\EmployeeTimeSheet;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     *
     * @return \Illuminate\View\View
     */
    public function adminLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if this is an admin login
        if ($request->is('admin/*') && !Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You do not have admin access.']);
        }

        // Redirect to appropriate place based on login path
        if ($request->is('admin/*')) {
            return redirect()->intended(route('dashboard.index'));
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogout(Request $request)
    {
        Cache::forget("menus_{$request->user()->id}");
        Cache::forget("user_permissions_{$request->user()->id}");

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect based on logout path
        if ($request->is('admin/*')) {
            return redirect()->route('admin.login');
        }

        return redirect('/');
    }
}