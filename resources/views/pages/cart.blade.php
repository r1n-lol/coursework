@extends('layouts.main')

@php
    $title = 'Корзина';
    $pageCss = 'cart';
@endphp

@section('content')
    <main class="cart-page">
        <section class="cart-hero">
            <div class="cart-hero__content">
                <p class="cart-kicker">Ваш заказ</p>
                <h1 class="cart-title">Корзина</h1>
            </div>
        </section>

        @if (session('cart_message'))
            <div class="cart-notice">{{ session('cart_message') }}</div>
        @endif

        @guest
            <section class="cart-guest">
                <div>
                    <h2>Войдите в аккаунт, чтобы не потерять заказ</h2>
                    <p>Корзина уже работает, но после входа будет удобнее оформить заказ и сохранить ваши данные.</p>
                </div>
                <a href="{{ route('login') }}" class="cart-guest__button">Войти</a>
            </section>
        @endguest

        @if ($cartItems === [])
            <section class="cart-empty">
                <h2>Корзина пока пустая</h2>
                <p>Добавьте напитки из меню, и они появятся здесь.</p>
                <a href="{{ route('menu') }}" class="cart-empty__button">Перейти в меню</a>
            </section>
        @else
            <section class="cart-layout">
                <div class="cart-list">
                    @foreach ($cartItems as $item)
                        <article class="cart-item">
                            <img src="{{ asset($item['image_path'] ?: 'media/images/menu.jpg') }}" alt="{{ $item['name'] }}">

                            <div class="cart-item__body">
                                <p class="cart-item__category">{{ $item['category_name'] }}</p>
                                <h2>{{ $item['name'] }}</h2>

                                @if ($item['size_options'] !== [])
                                    <form action="{{ route('cart.update') }}" method="POST" class="cart-item__size-form">
                                        @csrf
                                        <input type="hidden" name="item_key" value="{{ $item['key'] }}">
                                        <label class="cart-item__label" for="size-{{ md5($item['key']) }}">Размер</label>
                                        <select id="size-{{ md5($item['key']) }}" name="size" class="cart-item__select"
                                            onchange="this.form.submit()">
                                            @foreach ($item['size_options'] as $size)
                                                <option value="{{ $size['label'] }}"
                                                    {{ $item['size'] === $size['label'] ? 'selected' : '' }}>
                                                    {{ $size['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    <p class="cart-item__meta">Размер: {{ $item['size'] }}</p>
                                @endif

                                <div class="cart-item__quantity">
                                    <span class="cart-item__label">Количество</span>

                                    <div class="cart-item__quantity-controls">
                                        <form action="{{ route('cart.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $item['key'] }}">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="cart-item__quantity-btn">−</button>
                                        </form>

                                        <span class="cart-item__quantity-value">{{ $item['quantity'] }}</span>

                                        <form action="{{ route('cart.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $item['key'] }}">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="cart-item__quantity-btn">+</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="cart-item__side">
                                <span class="cart-item__price">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} ₽
                                </span>

                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="item_key" value="{{ $item['key'] }}">
                                    <button type="submit" class="cart-item__remove">Удалить</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="cart-summary">
                    <h2>Итого</h2>
                    <p class="cart-summary__total">{{ number_format($cartTotal, 0, ',', ' ') }} ₽</p>
                    <p class="cart-summary__text">Выберите способ получения на следующем шаге: доставка или в кафе.</p>

                    @auth
                        <a href="{{ route('checkout.create') }}" class="cart-summary__button">Оформить заказ</a>
                    @else
                        <a href="{{ route('login', ['redirect' => route('checkout.create', [], false)]) }}" class="cart-summary__button">
                            Войти для оформления
                        </a>
                    @endauth

                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="cart-summary__clear">Очистить корзину</button>
                    </form>
                </aside>
            </section>
        @endif
    </main>
@endsection
