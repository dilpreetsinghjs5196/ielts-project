<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        // Attempt Admin Login
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
        }

        // Attempt Student Login
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // Assuming student dashboard route is named 'student.dashboard'
            return redirect()->intended('/student/dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
            $redirectPath = '/login';
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $redirectPath = '/login';
        } else {
            $redirectPath = '/login';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectPath);
    }
}
