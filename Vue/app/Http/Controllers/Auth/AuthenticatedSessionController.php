<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'canRegister' => Route::has('register'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();
        if ($user && $user->is_locked) {
            return back()->withErrors(['email' => 'Account is locked.']);
        }
        try {
            $request->authenticate();
            $request->session()->regenerate();
            $user->update(['failed_attempts' => 0,]);
            $this->logEvent($user->id, 'login', $request);
            return redirect()->intended(route('dashboard'))->with('success','Welcome! You have successfully logged in!');;

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($user) {
                $user->increment('failed_attempts');
                $this->logEvent($user->id, 'failed_login', $request);

                if ($user->failed_attempts >= 5) {
                    $user->update(['is_locked' => true]);
                    $this->logEvent($user->id, 'lockout', $request);
                }
            }
            throw $e;
        }
    }

    private function logEvent($userId, $type, $request)
    {
        \DB::table('authentication_logs')->insert([
            'user_id' => $userId,
            'event_type' => $type,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            \DB::table('authentication_logs')->insert([
                'user_id' => Auth::id(),
                'event_type' => 'logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
