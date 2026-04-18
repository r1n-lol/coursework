@extends('layouts.main')

@php
    $title = 'Оформление заказа';
    $pageCss = 'checkout';
@endphp

@section('content')
    <main class="checkout-page">
        <section class="checkout-hero">
            <div class="checkout-hero__content">
                <p class="checkout-kicker">Последний шаг</p>
                <h1 class="checkout-title">Оформление заказа</h1>
            </div>
        </section>

        <section class="checkout-layout">
            <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form" id="checkoutForm">
                @csrf

                <div class="checkout-block">
                    <h2>Как получить заказ</h2>

                    <div class="checkout-types">
                        <label class="checkout-type">
                            <input type="radio" name="order_type" value="delivery"
                                {{ old('order_type', 'delivery') === 'delivery' ? 'checked' : '' }}>
                            <span>Доставка</span>
                        </label>

                        <label class="checkout-type">
                            <input type="radio" name="order_type" value="cafe"
                                {{ old('order_type') === 'cafe' ? 'checked' : '' }}>
                            <span>В кафе</span>
                        </label>
                    </div>
                </div>

                <div class="checkout-block">
                    <h2>Контакты</h2>

                    <div class="checkout-fields">
                        <label class="checkout-field">
                            <span>Имя</span>
                            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                required>
                            @error('customer_name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="checkout-field">
                            <span>Телефон</span>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required>
                            @error('customer_phone')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                    </div>
                </div>

                <div class="checkout-block">
                    <h2>Способ оплаты</h2>

                    <div class="checkout-types">
                        <label class="checkout-type">
                            <input type="radio" name="payment_method" value="card"
                                {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }}>
                            <span>По карте</span>
                        </label>

                        <label class="checkout-type">
                            <input type="radio" name="payment_method" value="cash"
                                {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                            <span>Наличными</span>
                        </label>
                    </div>
                </div>

                <div class="checkout-block" id="cardBlock">
                    <h2>Реквизиты карты</h2>

                    <div class="checkout-fields">
                        <label class="checkout-field checkout-field--full">
                            <span>Номер карты</span>
                            <input type="text" name="card_number" id="cardNumberInput" inputmode="numeric"
                                maxlength="19" placeholder="0000 0000 0000 0000" value="{{ old('card_number') }}">
                            @error('card_number')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="checkout-field">
                            <span>Срок действия</span>
                            <input type="text" name="card_expiry" id="cardExpiryInput" inputmode="numeric"
                                maxlength="5" placeholder="MM/YY" value="{{ old('card_expiry') }}">
                            @error('card_expiry')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="checkout-field">
                            <span>CVV</span>
                            <input type="text" name="card_cvv" id="cardCvvInput" inputmode="numeric"
                                maxlength="3" placeholder="000" value="{{ old('card_cvv') }}">
                            @error('card_cvv')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>
                    </div>
                </div>

                <div class="checkout-block" id="addressBlock">
                    <h2>Адрес доставки</h2>

                    <label class="checkout-field">
                        <span>Адрес</span>
                        <input type="text" name="address" value="{{ old('address') }}">
                        @error('address')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>
                </div>

                <div class="checkout-block">
                    <h2>Комментарий</h2>

                    <label class="checkout-field">
                        <span>Комментарий к заказу</span>
                        <textarea name="comment" rows="4">{{ old('comment') }}</textarea>
                        @error('comment')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>
                </div>

                <button type="submit" class="checkout-submit">Подтвердить заказ</button>
            </form>

            <aside class="checkout-summary">
                <h2>Ваш заказ</h2>

                <div class="checkout-summary__list">
                    @foreach ($cartItems as $item)
                        <div class="checkout-summary__item">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <p>{{ $item['size'] }} · {{ $item['quantity'] }} шт.</p>
                            </div>
                            <span>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} ₽</span>
                        </div>
                    @endforeach
                </div>

                <div class="checkout-summary__total">
                    <span>Итого</span>
                    <strong>{{ number_format($cartTotal, 0, ',', ' ') }} ₽</strong>
                </div>
            </aside>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addressBlock = document.getElementById('addressBlock');
            const cardBlock = document.getElementById('cardBlock');
            const typeInputs = document.querySelectorAll('input[name="order_type"]');
            const paymentInputs = document.querySelectorAll('input[name="payment_method"]');
            const cardNumberInput = document.getElementById('cardNumberInput');
            const cardExpiryInput = document.getElementById('cardExpiryInput');
            const cardCvvInput = document.getElementById('cardCvvInput');

            const syncAddressVisibility = () => {
                const selected = document.querySelector('input[name="order_type"]:checked')?.value;
                addressBlock.style.display = selected === 'delivery' ? 'block' : 'none';
            };

            const syncCardVisibility = () => {
                const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
                cardBlock.style.display = selected === 'card' ? 'block' : 'none';
            };

            const digitsOnly = (value, maxLength) => value.replace(/\D+/g, '').slice(0, maxLength);

            cardNumberInput?.addEventListener('input', () => {
                const digits = digitsOnly(cardNumberInput.value, 16);
                cardNumberInput.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
            });

            cardExpiryInput?.addEventListener('input', () => {
                const digits = digitsOnly(cardExpiryInput.value, 4);
                cardExpiryInput.value = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
            });

            cardCvvInput?.addEventListener('input', () => {
                cardCvvInput.value = digitsOnly(cardCvvInput.value, 3);
            });

            typeInputs.forEach((input) => {
                input.addEventListener('change', syncAddressVisibility);
            });

            paymentInputs.forEach((input) => {
                input.addEventListener('change', syncCardVisibility);
            });

            syncAddressVisibility();
            syncCardVisibility();
        });
    </script>
@endsection
