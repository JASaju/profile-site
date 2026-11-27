<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuditLogger $auditLogger) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->auditLogger->record(
                $request,
                'auth.login.success',
                'User signed in.',
                'info'
            );

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->auditLogger->record(
            $request,
            'auth.login.failure',
            'A sign-in attempt failed.',
            'warning'
        );

        return back()->withErrors(['email' => 'Email or password is incorrect.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $this->auditLogger->record(
            $request,
            'auth.logout',
            'User signed out.',
            'info',
            userId: $userId
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
