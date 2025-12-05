<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function homes(): View
    {
        return view('auth.homes');
    }

    protected function create(array $data): User
    {
        return User::create([
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function stores(Request $request): RedirectResponse
    {   
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) { 
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('auth.homes')->with('success', 'Login successful!');
            } else {
                return redirect()->route('auth.registerhome')->with('success', 'Login successful!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'You have been logged out');
    }
}