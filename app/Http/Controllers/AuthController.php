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
    /**
     * Hardcoded admin credentials
     * Username: Admin
     * Password: 12345
     */
    private const ADMIN_CREDENTIALS = [
        'name' => 'Admin',
        'password' => '12345'
    ];

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check for admin credentials first
        if ($request->name === self::ADMIN_CREDENTIALS['name'] && 
            $request->password === self::ADMIN_CREDENTIALS['password']) {
            
            // Find or create admin user
            $admin = User::firstOrCreate(
                ['name' => self::ADMIN_CREDENTIALS['name']],
                [
                    'password' => bcrypt(self::ADMIN_CREDENTIALS['password']),
                    'email' => 'admin@terprima.com'
                ]
            );

            Auth::login($admin);
            return redirect()->intended('/dashboard');
        }

        // Try normal authentication for other users
        if (!Auth::attempt($request->only('name', 'password'))) {
            throw ValidationException::withMessages([
                'name' => ['Nama atau kata sandi tidak valid.'],
            ]);
        }

        return redirect()->intended('/dashboard');
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
