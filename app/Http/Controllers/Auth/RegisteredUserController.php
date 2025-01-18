<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client; // Utilisez Client et non Clients
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'numeroTel' => ['required', 'numeric'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'adresse' => ['required', 'string', 'max:255'],
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? ''),
        ]);

        // Create Client
        Client::create([
            'user_id' => $user->id,
            'nom' => $request->nom,
            'age' => $request->age,
            'numeroTel' => $request->numeroTel,
            'gender' => $request->gender,
            'adresse' => $request->adresse,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to the login page after registration
        return redirect()->route('panier.index');
    }
}
