{{-- <main> --}}
<header>
    @php
        $cartCount = auth()->check()
            ? (int) auth()->user()->cartItems()->sum('quantity')
            : collect(session('cart', []))->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
    @endphp

    <nav>
        <div class="nav-item">
            <div class="nav-link"><a href="#" onclick="return false;">КОФЕ</a></div>

            <div class="dropdown">
                <a href="{{ route('menu') }}">Меню</a>
                <a href="{{ route('home') }}">Домой</a>
            </div>
        </div>

        <div class="nav-link"><a href="{{ route('cashback') }}">КЭШБЕК</a></div>

        <a href="{{ route('index') }}" class="logo">
            <img src="{{ asset('media/images/nb.png') }}" alt="Ништяк Бодрит">
        </a>

        <div class="nav-link"><a href="{{ route('about') }}">ПРО НАС</a></div>
        <div class="nav-link"><a href="{{ route('contacts') }}">СВЯЗЬ</a></div>
    </nav>

    <div class="header-shortcuts">
        <a href="{{ route('cart.index') }}" class="cart-shortcut" aria-label="Корзина">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path
                    d="M7 6h14l-1.5 7.5a2 2 0 0 1-2 1.6H10.1a2 2 0 0 1-2-1.6L6.4 4.8A1 1 0 0 0 5.4 4H3" />
                <circle cx="10" cy="19" r="1.7" />
                <circle cx="18" cy="19" r="1.7" />
            </svg>
            <span class="cart-shortcut__count">{{ $cartCount }}</span>
        </a>

        @auth
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.drinks.index') }}" class="admin-panel-shortcut">Админ</a>
            @endif
        @endauth
    </div>

    <div class="burger" id="burger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="side-menu" id="sideMenu">
        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">выйти</button>
            </form>

            <a href="{{ route('profile.show') }}">профиль</a>
        @endauth
        @guest
            <a href="{{ route('login') }}">войти</a>
        @endguest
        <a href="{{ route('menu') }}">меню</a>
        <a href="{{ route('home') }}">домой</a>
        <a href="{{ route('cashback') }}">кэшбек</a>
        <a href="{{ route('about') }}">про нас</a>
        <a href="{{ route('contacts') }}">связь</a>
    </div>
</header>
{{-- </main> --}}

<script src="{{ asset('js/burger.js') }}"></script>
