<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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
    
        $user = Auth::user(); // Récupère l'utilisateur connecté
    
        // Vérifiez le rôle de l'utilisateur et redirigez en conséquence
        if ($user->role === 'client') {
            return redirect()->route('panier.index'); // Redirection vers le panier du client
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard'); // Redirection vers le tableau de bord de l'administrateur
        }
    
        // Par défaut, redirige vers la page d'accueil si le rôle ne correspond pas
        return redirect('/');
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
