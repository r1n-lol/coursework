<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cartItems = CartController::cartItemsForRequest($request);

        if ($cartItems === []) {
            return redirect()->route('cart.index')->with('cart_message', 'Корзина пока пустая.');
        }

        $cartTotal = collect($cartItems)->sum(fn (array $item) => $item['price'] * $item['quantity']);

        return view('pages.checkout', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cartItems = CartController::cartItemsForRequest($request);

        if ($cartItems === []) {
            return redirect()->route('cart.index')->with('cart_message', 'Корзина пока пустая.');
        }

        $request->merge([
            'card_number' => preg_replace('/\D+/', '', (string) $request->input('card_number')),
            'card_cvv' => preg_replace('/\D+/', '', (string) $request->input('card_cvv')),
            'card_expiry' => preg_replace('/\s+/', '', (string) $request->input('card_expiry')),
        ]);

        $validated = $request->validate([
            'order_type' => ['required', 'in:delivery,cafe'],
            'payment_method' => ['required', 'in:cash,card'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'card_number' => ['nullable', 'regex:/^\d{16}$/'],
            'card_expiry' => ['nullable', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv' => ['nullable', 'regex:/^\d{3}$/'],
        ]);

        if ($validated['order_type'] === 'delivery') {
            $request->validate([
                'address' => ['required', 'string', 'max:500'],
            ]);
        }

        if ($validated['payment_method'] === 'card') {
            $request->validate([
                'card_number' => ['required', 'regex:/^\d{16}$/'],
                'card_expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'card_cvv' => ['required', 'regex:/^\d{3}$/'],
            ], [
                'card_number.required' => 'Введите номер карты.',
                'card_number.regex' => 'Номер карты должен содержать ровно 16 цифр.',
                'card_expiry.required' => 'Введите срок действия карты.',
                'card_expiry.regex' => 'Срок действия укажите в формате ММ/ГГ.',
                'card_cvv.required' => 'Введите CVV.',
                'card_cvv.regex' => 'CVV должен содержать ровно 3 цифры.',
            ]);
        }

        $cartTotal = collect($cartItems)->sum(fn (array $item) => $item['price'] * $item['quantity']);

        $order = Order::query()->create([
            'user_id' => Auth::id(),
            'status' => Order::STATUS_ACCEPTED,
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'payment_card_last4' => $validated['payment_method'] === 'card' ? substr($validated['card_number'], -4) : null,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'address' => $validated['order_type'] === 'delivery' ? $validated['address'] : null,
            'comment' => $validated['comment'] ?? null,
            'total' => $cartTotal,
        ]);

        foreach ($cartItems as $item) {
            $order->items()->create([
                'drink_id' => $item['drink_id'],
                'drink_name' => $item['name'],
                'size' => $item['size'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        CartController::clearCartForRequest($request);

        return redirect()->route('checkout.success', $order)->with('order_message', 'Заказ оформлен.');
    }

    public function success(Order $order): View
    {
        abort_if($order->user_id && Auth::id() !== $order->user_id, 403);

        return view('pages.checkout-success', [
            'order' => $order->load('items'),
        ]);
    }
}
