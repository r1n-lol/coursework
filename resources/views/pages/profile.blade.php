@extends('layouts.main')

@php
    $title = 'Профиль';
    $pageCss = 'profile';
@endphp

@section('content')
    <main class="profile-page">
        <section class="profile-hero">
            <div class="profile-hero__content">
                <p class="profile-kicker">Личный кабинет</p>
                <h1 class="profile-title">Профиль</h1>
            </div>
        </section>

        @if (session('profile_message'))
            <div class="profile-notice">{{ session('profile_message') }}</div>
        @endif

        <section class="profile-layout">
            <section class="profile-card">
                <h2>Данные пользователя</h2>

                <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                    @csrf

                    <label class="profile-field">
                        <span>Имя</span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>

                    <label class="profile-field">
                        <span>Email</span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>

                    <label class="profile-field">
                        <span>Новый пароль</span>
                        <input type="password" name="password" placeholder="Оставьте пустым, если менять не нужно">
                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>

                    <label class="profile-field">
                        <span>Повторите пароль</span>
                        <input type="password" name="password_confirmation" placeholder="Повторите новый пароль">
                    </label>

                    <button type="submit" class="profile-submit">Сохранить изменения</button>
                </form>
            </section>

            <section class="profile-card">
                <h2>Ваши заказы</h2>

                @if ($user->orders->isEmpty())
                    <div class="profile-empty">
                        <p>Заказов пока нет. Как только вы оформите первый заказ, он появится здесь.</p>
                    </div>
                @else
                    <div class="profile-orders">
                        @foreach ($user->orders as $order)
                            <article class="profile-order">
                                <div class="profile-order__head">
                                    <div>
                                        <h3>Заказ №{{ $order->id }}</h3>
                                        <p>
                                            {{ $order->order_type === 'delivery' ? 'Доставка' : 'В кафе' }}
                                            ·
                                            {{ $order->payment_method === 'cash' ? 'Наличными' : 'По карте' }}
                                        </p>
                                    </div>
                                    <span class="profile-order__status status-{{ $order->status }}">
                                        {{ $statusLabels[$order->status] ?? 'В обработке' }}
                                    </span>
                                </div>

                                <div class="profile-order__items">
                                    @foreach ($order->items as $item)
                                        <div class="profile-order__item">
                                            <span>{{ $item->drink_name }} · {{ $item->size }} · {{ $item->quantity }} шт.</span>
                                            <strong>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="profile-order__footer">
                                    <span>Итого</span>
                                    <strong>{{ number_format($order->total, 0, ',', ' ') }} ₽</strong>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </section>
    </main>
@endsection
