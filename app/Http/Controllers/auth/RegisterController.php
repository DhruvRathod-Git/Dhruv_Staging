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

class RegisterController extends Controller
{

    public function index()
    {
        return view('auth.register');
    }

    public function home()
    {
        return view('auth.registerhome');
    }

    public function create()
    {
        //
    }

    
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255','email'],
        'password' => ['required', 'min:4'],
        'password_confirmation' => ['required', 'min:4'],
        // 'number' => ['required', 'digits:10'],
    ]);
    
    $User = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'number' => $request->number,
        ]);
        // dd($request->all());
        
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
      ]);

        if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
    return redirect()->route('auth.registerhome')->with('success', 'Successfully Signed up!');
}

        return back()->withErrors(['email' => 'The provided credentials do not match our records',])
        ->onlyInput('email');
    }
}