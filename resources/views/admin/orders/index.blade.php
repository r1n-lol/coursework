<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <main class="admin-page">
        <section class="admin-topbar">
            <div>
                <p class="admin-kicker">Ништяк Бодрит</p>
                <h1>Заказы</h1>
            </div>

            <div class="admin-topbar__actions">
                <a href="{{ route('admin.drinks.index') }}" class="admin-button admin-button--ghost">Товары</a>
                <a href="{{ route('index') }}" class="admin-button admin-button--ghost">На сайт</a>
            </div>
        </section>

        @if (session('admin_message'))
            <div class="admin-notice">{{ session('admin_message') }}</div>
        @endif

        <section class="admin-orders">
            @forelse ($orders as $order)
                <article class="admin-card admin-order">
                    <div class="admin-order__head">
                        <div class="admin-order__summary">
                            <h2>Заказ №{{ $order->id }}</h2>
                            <div class="admin-order__badges">
                                <span class="admin-order__badge">
                                    {{ $order->order_type === 'delivery' ? 'Доставка' : 'В кафе' }}
                                </span>
                                <span class="admin-order__badge">
                                    {{ $order->payment_method === 'cash' ? 'Наличными' : 'По карте' }}
                                </span>
                            </div>
                        </div>

                        <div class="admin-status-panel">
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="admin-status-form">
                                @csrf
                                @method('PATCH')
                                <label class="admin-field">
                                    <span>Статус</span>
                                    <select name="status">
                                        @foreach ($order::availableStatusesForType($order->order_type) as $status)
                                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                                {{ $statusLabels[$status] ?? $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                                <span class="admin-order__status status-{{ $order->status }}">{{ $order->statusLabel() }}</span>
                                <button type="submit" class="admin-button">Обновить статус</button>
                            </form>

                            @if ($order->canBeDeleted())
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                    class="admin-order__delete-form"
                                    onsubmit="return confirm('Удалить заказ №{{ $order->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-button admin-button--danger">Удалить заказ</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="admin-order__details">
                        <div class="admin-order__detail-card">
                            <span class="admin-order__detail-label">Клиент</span>
                            <strong>{{ $order->customer_name }}</strong>
                            <p class="admin-order__meta">{{ $order->customer_phone }}</p>
                        </div>

                        @if ($order->address)
                            <div class="admin-order__detail-card">
                                <span class="admin-order__detail-label">Адрес</span>
                                <p class="admin-order__meta">{{ $order->address }}</p>
                            </div>
                        @endif

                        @if ($order->comment)
                            <div class="admin-order__detail-card">
                                <span class="admin-order__detail-label">Комментарий</span>
                                <p class="admin-order__meta">{{ $order->comment }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="admin-order__items">
                        <h3 class="admin-order__section-title">Состав заказа</h3>
                        @foreach ($order->items as $item)
                            <div class="admin-order__item">
                                <div class="admin-order__item-info">
                                    <strong>{{ $item->drink_name }}</strong>
                                    <p class="admin-order__meta">{{ $item->size }} · {{ $item->quantity }} шт.</p>
                                </div>
                                <strong class="admin-order__item-price">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
                            </div>
                        @endforeach
                    </div>

                    <div class="admin-order__footer">
                        <span>Итого</span>
                        <strong>{{ number_format($order->total, 0, ',', ' ') }} ₽</strong>
                    </div>
                </article>
            @empty
                <section class="admin-card">
                    <p class="admin-order__meta">Заказов пока нет.</p>
                </section>
            @endforelse
        </section>
    </main>
</body>

</html>
