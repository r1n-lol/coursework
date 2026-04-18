<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Drink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = self::cartItemsForRequest($request);
        $total = collect($items)->sum(fn (array $item) => $item['price'] * $item['quantity']);

        return view('pages.cart', [
            'cartItems' => $items,
            'cartTotal' => $total,
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'drink_id' => ['required', 'integer', 'exists:drinks,id'],
            'size' => ['nullable', 'string'],
        ]);

        $drink = Drink::query()->findOrFail($validated['drink_id']);
        $sizeOption = $this->resolveSizeOption($drink, $validated['size'] ?? null);

        if (Auth::check()) {
            $item = Auth::user()->cartItems()->firstOrNew([
                'drink_id' => $drink->id,
                'size' => $sizeOption['label'],
            ]);

            $item->price = $sizeOption['price'];
            $item->quantity = ($item->exists ? $item->quantity : 0) + 1;
            $item->save();
        } else {
            $cart = $request->session()->get('cart', []);
            $itemKey = $drink->id.'_'.$sizeOption['label'];

            if (isset($cart[$itemKey])) {
                $cart[$itemKey]['quantity']++;
            } else {
                $cart[$itemKey] = [
                    'key' => $itemKey,
                    'drink_id' => $drink->id,
                    'slug' => $drink->slug,
                    'name' => $drink->name,
                    'category_name' => $drink->category_name,
                    'size' => $sizeOption['label'],
                    'price' => $sizeOption['price'],
                    'quantity' => 1,
                    'image_path' => $drink->image_path,
                ];
            }

            $request->session()->put('cart', $cart);
        }

        return back()->with('cart_message', 'Товар добавлен в корзину.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_key' => ['required', 'string'],
            'action' => ['nullable', 'in:increase,decrease'],
            'size' => ['nullable', 'string'],
        ]);

        if (Auth::check()) {
            $this->updateDatabaseCartItem($validated['item_key'], $validated['action'] ?? null, $validated['size'] ?? null);
        } else {
            $this->updateSessionCartItem($request, $validated['item_key'], $validated['action'] ?? null, $validated['size'] ?? null);
        }

        return back()->with('cart_message', 'Корзина обновлена.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_key' => ['required', 'string'],
        ]);

        if (Auth::check()) {
            CartItem::query()
                ->whereKey((int) $validated['item_key'])
                ->where('user_id', Auth::id())
                ->delete();
        } else {
            $cart = $request->session()->get('cart', []);
            unset($cart[$validated['item_key']]);
            $request->session()->put('cart', $cart);
        }

        return back()->with('cart_message', 'Товар удален из корзины.');
    }

    public function clear(Request $request): RedirectResponse
    {
        self::clearCartForRequest($request);

        return back()->with('cart_message', 'Корзина очищена.');
    }

    public static function cartItemsForRequest(Request $request): array
    {
        if (Auth::check()) {
            return Auth::user()
                ->cartItems()
                ->with('drink')
                ->get()
                ->map(function (CartItem $item) {
                    $drink = $item->drink;

                    return [
                        'key' => (string) $item->id,
                        'drink_id' => $item->drink_id,
                        'slug' => $drink?->slug ?? '',
                        'name' => $drink?->name ?? 'Напиток',
                        'category_name' => $drink?->category_name ?? '',
                        'size' => $item->size,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'image_path' => $drink?->image_path,
                        'size_options' => $drink?->normalizedSizeOptions() ?? [],
                    ];
                })
                ->all();
        }

        $cart = $request->session()->get('cart', []);

        return array_values(array_map(function (array $item) {
            $drink = Drink::query()->find($item['drink_id']);

            return [
                ...$item,
                'size_options' => $drink?->normalizedSizeOptions() ?? [],
            ];
        }, $cart));
    }

    public static function clearCartForRequest(Request $request): void
    {
        if (Auth::check()) {
            Auth::user()->cartItems()->delete();
            return;
        }

        $request->session()->forget('cart');
    }

    private function updateDatabaseCartItem(string $itemKey, ?string $action, ?string $selectedSize): void
    {
        $item = CartItem::query()
            ->whereKey((int) $itemKey)
            ->where('user_id', Auth::id())
            ->first();

        if (! $item) {
            return;
        }

        if ($action === 'increase') {
            $item->increment('quantity');
            $item->refresh();
        }

        if ($action === 'decrease') {
            if ($item->quantity <= 1) {
                $item->delete();
                return;
            }

            $item->decrement('quantity');
            $item->refresh();
        }

        if ($selectedSize !== null) {
            $drink = Drink::query()->find($item->drink_id);

            if (! $drink) {
                return;
            }

            $sizeOption = $this->resolveSizeOption($drink, $selectedSize);
            $existing = CartItem::query()
                ->where('user_id', Auth::id())
                ->where('drink_id', $drink->id)
                ->where('size', $sizeOption['label'])
                ->whereKeyNot($item->id)
                ->first();

            if ($existing) {
                $existing->quantity += $item->quantity;
                $existing->price = $sizeOption['price'];
                $existing->save();
                $item->delete();
                return;
            }

            $item->size = $sizeOption['label'];
            $item->price = $sizeOption['price'];
            $item->save();
        }
    }

    private function updateSessionCartItem(Request $request, string $itemKey, ?string $action, ?string $selectedSize): void
    {
        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$itemKey])) {
            return;
        }

        if ($action === 'increase') {
            $cart[$itemKey]['quantity']++;
        }

        if ($action === 'decrease') {
            $cart[$itemKey]['quantity']--;

            if ($cart[$itemKey]['quantity'] <= 0) {
                unset($cart[$itemKey]);
                $request->session()->put('cart', $cart);
                return;
            }
        }

        if ($selectedSize !== null && isset($cart[$itemKey])) {
            $drink = Drink::query()->find($cart[$itemKey]['drink_id']);

            if ($drink) {
                $sizeOption = $this->resolveSizeOption($drink, $selectedSize);
                $newKey = $drink->id.'_'.$sizeOption['label'];
                $updatedItem = $cart[$itemKey];
                unset($cart[$itemKey]);

                $updatedItem['key'] = $newKey;
                $updatedItem['size'] = $sizeOption['label'];
                $updatedItem['price'] = $sizeOption['price'];

                if (isset($cart[$newKey])) {
                    $cart[$newKey]['quantity'] += $updatedItem['quantity'];
                } else {
                    $cart[$newKey] = $updatedItem;
                }
            }
        }

        $request->session()->put('cart', $cart);
    }

    private function resolveSizeOption(Drink $drink, ?string $selectedSize): array
    {
        $options = $drink->normalizedSizeOptions();

        if ($options === []) {
            return [
                'label' => $drink->volume ?: 'Стандарт',
                'price' => $drink->price,
            ];
        }

        foreach ($options as $option) {
            if ($option['label'] === $selectedSize) {
                return $option;
            }
        }

        return $options[0];
    }
}
