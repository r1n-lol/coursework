@extends('layouts.main')

@php
    $title = 'Меню';
    $pageCss = 'menu';
@endphp

@section('content')
    <main class="menu-page">
        <section class="menu-hero">
            <div class="menu-hero__content">
                <p class="menu-kicker">{{ $catalogKicker }}</p>
                <h1 class="menu-title">{{ $catalogTitle }}</h1>
            </div>
        </section>

        @if (session('cart_message'))
            <div class="menu-notice">{{ session('cart_message') }}</div>
        @endif

        <section class="menu-filters">
            <a href="{{ route($catalogRoute) }}" class="menu-filter {{ $selectedCategory === '' ? 'is-active' : '' }}">
                {{ $allLabel }}
            </a>

            @foreach ($categories as $category)
                <a href="{{ route($catalogRoute, ['category' => $category->category_key]) }}"
                    class="menu-filter {{ $selectedCategory === $category->category_key ? 'is-active' : '' }}">
                    {{ $category->category_name }}
                </a>
            @endforeach
        </section>

        <section class="menu-grid">
            @forelse ($drinks as $drink)
                @php($defaultSize = $drink->defaultSizeOption())
                <article class="drink-card">
                    <div class="drink-card__image-wrap">
                        <a href="{{ route($catalogRoute . '.show', $drink) }}" class="drink-card__image-link" aria-label="{{ $drink->name }}">
                            <img src="{{ asset($drink->image_path ?: 'media/images/menu.jpg') }}" alt="{{ $drink->name }}"
                                class="drink-card__image">
                        </a>
                    </div>

                    <div class="drink-card__body">
                        <span class="drink-card__category">{{ $drink->category_name }}</span>

                        <div class="drink-card__head">
                            <h2><a href="{{ route($catalogRoute . '.show', $drink) }}">{{ $drink->name }}</a></h2>
                            <span class="drink-card__volume">{{ $drink->sizeSummary() }}</span>
                        </div>

                        <div class="drink-card__footer">
                            <span class="drink-card__price">от {{ number_format($drink->startingPrice(), 0, ',', ' ') }} ₽</span>
                            <form action="{{ route('cart.add') }}" method="POST" class="drink-card__cart-form">
                                @csrf
                                <input type="hidden" name="drink_id" value="{{ $drink->id }}">
                                <input type="hidden" name="size" value="{{ $defaultSize['label'] }}">
                                <button type="submit" class="drink-card__button">В корзину</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="menu-empty">
                    <h2>{{ $emptyTitle }}</h2>
                    <p>{{ $emptyText }}</p>
                </div>
            @endforelse
        </section>
    </main>
@endsection
