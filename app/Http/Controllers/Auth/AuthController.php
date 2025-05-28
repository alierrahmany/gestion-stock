<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $user->last_login = Carbon::now();
            $user->save();

            Log::info("User logged in", ['user_id' => $user->id]);

            return match($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'gestionnaire' => redirect()->intended(route('gestionnaire.dashboard')),
                default => redirect()->intended(route('magasin.dashboard')),
            };
        }

        Log::warning("Failed login attempt", ['email' => $request->email]);
        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);
        
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info("Password reset link sent", ['email' => $request->email]);
            return back()->with('status', __($status));
        }

        Log::warning("Password reset link failed", ['email' => $request->email, 'status' => $status]);
        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
                Log::info("Password reset successful", ['user_id' => $user->id]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', __($status))
                ->with('success', __('Password updated successfully'));
        }

        Log::warning("Password reset failed", ['email' => $request->email, 'status' => $status]);
        return back()->withErrors(['email' => __($status)]);
    }
}