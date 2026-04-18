@extends('layouts.main')

@php
    $title = 'Заказ оформлен';
    $pageCss = 'checkout';
@endphp

@section('content')
    <main class="checkout-page">
        <section class="checkout-hero">
            <div class="checkout-hero__content">
                <p class="checkout-kicker">Заказ принят</p>
                <h1 class="checkout-title">Спасибо за заказ</h1>
            </div>
        </section>

        <section class="checkout-success">
            <div class="checkout-success__head">
                <h2>Заказ №{{ $order->id }}</h2>
                <p class="checkout-success__lead">Мы свяжемся с вами по телефону {{ $order->customer_phone }}.</p>
            </div>

            <div class="checkout-success__details">
                <div class="checkout-success__detail">
                    <span class="checkout-success__label">Способ получения</span>
                    <strong>{{ $order->order_type === 'delivery' ? 'Доставка' : 'В кафе' }}</strong>
                </div>
                <div class="checkout-success__detail">
                    <span class="checkout-success__label">Способ оплаты</span>
                    <strong>{{ $order->payment_method === 'cash' ? 'Наличными' : 'По карте' }}</strong>
                </div>
                <div class="checkout-success__detail">
                    <span class="checkout-success__label">Статус</span>
                    <strong>{{ $order->statusLabel() }}</strong>
                </div>
                @if ($order->payment_method === 'card' && $order->payment_card_last4)
                    <div class="checkout-success__detail">
                        <span class="checkout-success__label">Карта</span>
                        <strong>**** {{ $order->payment_card_last4 }}</strong>
                    </div>
                @endif
            </div>

            <div class="checkout-success__section">
                <div class="checkout-success__section-head">
                    <h3>Состав заказа</h3>
                </div>

                <div class="checkout-summary__list">
                @foreach ($order->items as $item)
                    <div class="checkout-summary__item">
                        <div>
                            <strong>{{ $item->drink_name }}</strong>
                            <p>{{ $item->size }} · {{ $item->quantity }} шт.</p>
                        </div>
                        <span>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</span>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="checkout-summary__total">
                <span>Итого</span>
                <strong>{{ number_format($order->total, 0, ',', ' ') }} ₽</strong>
            </div>

            <a href="{{ route('menu') }}" class="checkout-submit checkout-submit--link">Вернуться в меню</a>
        </section>
    </main>
@endsection
