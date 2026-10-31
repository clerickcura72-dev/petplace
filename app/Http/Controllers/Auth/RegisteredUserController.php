<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name'  => ['required', 'string', 'max:255'],
        'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        'role'       => ['required', 'in:vet,owner'],
    ]);

    // Create user account
    $user = User::create([
        'name'     => $request->first_name . ' ' . $request->last_name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $request->role,
    ]);

    // Save to separate table based on role
    if ($request->role === 'vet') {
        $request->validate([
            'license_number' => ['required', 'string', 'max:255'],
            'clinic_name'    => ['nullable', 'string', 'max:255'],
        ]);

        \App\Models\Vet::create([
            'user_id'        => $user->id,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'license_number' => $request->license_number,
            'clinic_name'    => $request->clinic_name,
        ]);
    } else {
        $request->validate([
            'phone'    => ['nullable', 'string', 'max:20'],
            'pet_name' => ['nullable', 'string', 'max:255'],
            'pet_type' => ['nullable', 'string', 'max:255'],
        ]);

        \App\Models\PetOwner::create([
            'user_id'    => $user->id,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phone'      => $request->phone,
            'pet_name'   => $request->pet_name,
            'pet_type'   => $request->pet_type,
        ]);
    }

    event(new Registered($user));
    Auth::login($user);

    if ($user->role === 'vet') {
        return redirect()->route('vet.dashboard');
    }

    return redirect()->route('owner.dashboard');
}

}