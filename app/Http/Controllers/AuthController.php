<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Drink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        return view('auth.register', [
            'redirectTo' => $this->resolveRedirectPath($request->query('redirect')),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        Auth::login($user);
        $this->mergeGuestCart($request, $user);

        return redirect()->to($this->resolveRedirectPath($request->input('redirect')) ?? route('index'));
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.login', [
            'redirectTo' => $this->resolveRedirectPath($request->query('redirect')),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {
            /** @var User $user */
            $user = Auth::user();
            $this->mergeGuestCart($request, $user);

            if ($user->role === 'admin') {
                return redirect()->route('admin.drinks.index');
            }

            return redirect()->to($this->resolveRedirectPath($request->input('redirect')) ?? route('index'));
        }

        return back()->withErrors(['email' => 'Неверные данные.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('cart');

        return redirect()->route('index');
    }

    private function mergeGuestCart(Request $request, User $user): void
    {
        $guestCart = $request->session()->get('cart', []);

        foreach ($guestCart as $item) {
            if (! isset($item['drink_id'], $item['size'], $item['quantity'], $item['price'])) {
                continue;
            }

            $drink = Drink::query()->find($item['drink_id']);

            if (! $drink) {
                continue;
            }

            $cartItem = $user->cartItems()->firstOrNew([
                'drink_id' => $drink->id,
                'size' => $item['size'],
            ]);

            $cartItem->price = (int) $item['price'];
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + (int) $item['quantity'];
            $cartItem->save();
        }

        $request->session()->forget('cart');
    }

    private function resolveRedirectPath(?string $redirect): ?string
    {
        if (! $redirect) {
            return null;
        }

        $path = parse_url($redirect, PHP_URL_PATH);
        $query = parse_url($redirect, PHP_URL_QUERY);

        if (is_string($path) && Str::startsWith($path, ['/'])) {
            return $query ? $path.'?'.$query : $path;
        }

        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl !== '' && Str::startsWith($redirect, $appUrl)) {
            $relative = Str::replaceFirst($appUrl, '', $redirect);

            return Str::startsWith($relative, ['/']) ? $relative : '/';
        }

        return null;
    }
}
