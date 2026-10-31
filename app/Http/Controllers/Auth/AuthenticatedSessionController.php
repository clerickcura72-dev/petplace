<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = auth()->user();
    $intendedRole = $request->input('role'); // 'vet' or 'owner'

    // Check if user is logging in from the correct page
    if ($intendedRole && $user->role !== $intendedRole) {
        auth()->logout();

        return back()->withErrors([
            'email' => 'This account is not registered as a ' . ($intendedRole === 'vet' ? 'Veterinarian' : 'Pet Owner') . '.',
        ]);
    }

    // Redirect based on role
    if ($user->role === 'vet') {
        return redirect()->route('vet.dashboard');
    }

    return redirect()->route('owner.dashboard');
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
