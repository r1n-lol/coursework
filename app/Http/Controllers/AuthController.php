<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();


        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('index');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {
            return redirect()->route('index');
        }
        return back()->withErrors(['email' => 'Неверные данные.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
