<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        return view('auth.login');
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'nrp' => 'required|string',
        ]);

        $user = User::where('name', $request->name)
                    ->where('nrp', $request->nrp)
                    ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'name' => ['Nama atau NRP tidak valid.'],
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/contents');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
