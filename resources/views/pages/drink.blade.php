@extends('layouts.main')

@php
    $title = $drink->name;
    $pageCss = 'drink';
@endphp

@section('content')
    <main class="drink-page">
        @if (session('cart_message'))
            <div class="drink-notice">{{ session('cart_message') }}</div>
        @endif

        <section class="drink-detail {{ $drink->catalog === 'home' ? 'drink-detail--home' : '' }}">
            <div class="drink-detail__media">
                <img src="{{ asset($drink->image_path ?: 'media/images/menu.jpg') }}" alt="{{ $drink->name }}">
            </div>

            <div class="drink-detail__content">
                <p class="drink-detail__kicker">{{ $drink->category_name }}</p>
                <h1 class="drink-detail__title">{{ $drink->name }}</h1>
                <p class="drink-detail__description">{{ $drink->description }}</p>

                <div class="drink-detail__block">
                    <h2>Состав</h2>
                    <p>{{ $drink->ingredients }}</p>
                </div>

                <div class="drink-detail__block">
                    <h2>Размер</h2>
                    <div class="drink-sizes" id="drinkSizes">
                        @foreach ($sizeOptions as $index => $size)
                            <label class="drink-size-option">
                                <input type="radio" name="size" value="{{ $size['label'] }}" data-price="{{ $size['price'] }}"
                                    {{ $index === 0 ? 'checked' : '' }}>
                                <span>{{ $size['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="drink-detail__footer" id="drinkCartForm">
                    @csrf
                    <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                    <input type="hidden" name="size" id="selectedSizeInput" value="{{ $sizeOptions[0]['label'] ?? $drink->volume }}">
                    <span class="drink-detail__price" id="drinkPrice">
                        {{ number_format($sizeOptions[0]['price'] ?? $drink->price, 0, ',', ' ') }} ₽
                    </span>
                    <button type="submit" class="drink-detail__button">В корзину</button>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sizeInputs = document.querySelectorAll('.drink-size-option input[name="size"]');
            const priceElement = document.getElementById('drinkPrice');
            const selectedSizeInput = document.getElementById('selectedSizeInput');

            sizeInputs.forEach((input) => {
                input.addEventListener('change', () => {
                    const price = Number(input.dataset.price || 0);
                    priceElement.textContent = `${price.toLocaleString('ru-RU')} ₽`;
                    selectedSizeInput.value = input.value;
                });
            });
        });
    </script>
@endsection
