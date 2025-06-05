<?php

namespace App\Http\Controllers\Auth;

use App\Models\Collector;
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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'role' => ['required', 'string', 'in:user,collector'], // <-- Validasi role
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role, // <-- Simpan role
        'password' => Hash::make($request->password),
        // 'balance' => 0, // Default balance jika ada di model User $attributes
    ]);

    // Jika role adalah collector, buat juga entri di tabel collectors
    if ($request->role === 'collector') {
        Collector::create([
            'user_id' => $user->id,
            // 'assigned_area' => 'Default Area', // Atau biarkan kosong jika nullable
        ]);
    }

    event(new Registered($user));

    Auth::login($user);

    return redirect(route(config('fortify.home'), absolute: false)); // Atau ke dashboard user
}
}
