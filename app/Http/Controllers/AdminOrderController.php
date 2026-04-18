<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['items', 'user'])
                ->latest()
                ->get(),
            'statusLabels' => Order::statusLabels(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(Order::availableStatusesForType($order->order_type)),
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with('admin_message', 'Статус заказа обновлен.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        if (! $order->canBeDeleted()) {
            return back()->with('admin_message', 'Удалить можно только завершенный или отмененный заказ.');
        }

        $order->delete();

        return back()->with('admin_message', 'Заказ удален.');
    }
}
