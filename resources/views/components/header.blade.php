{{-- <main> --}}
<header>
    <nav>

        <div class="nav-item">

            <div class="nav-link"><a href="/">КОФЕ</a></div>

            <div class="dropdown">
                <a href="/">Меню</a>
                <a href="/">Домой</a>
            </div>

        </div>

        <div class="nav-link"><a href="/">КЭШБЭК</a></div>

        <a href="{{ route('index') }}" class="logo">
            <img src="{{ asset('media/images/nb.png') }}" alt="Ништяк Бодрит">
        </a>

        <div class="nav-item">
            <div class="nav-link"><a href="/">КТО МЫ</a></div>

            <div class="dropdown">
                <a href="/">Про нас</a>
                <a href="/">Контакты</a>
            </div>
        </div>
        <div class="nav-link"><a href="/">AКЦИИ</a></div>



    </nav>

    <div class="burger" id="burger">
        <span></span>
        <span></span>
        <span></span>
    </div>


    <!-- Дополнительное бургер меню -->
    <div class="side-menu" id="sideMenu">
        @auth
            <form action="{{ route('logout') }}" method="POST">
                <button type="submit" class="logout-btn">выйти</button>
            </form>

            <a href="/">профиль</a>
        @endauth
        @guest
            <a href="{{ route('login') }}">войти</a>
        @endguest
        <a href="/">корзина</a>
        <a href="/">контакты</a>
        <a href="/">меню</a>
        <a href="/">домой</a>
        <a href="/">кэшбек</a>
        <a href="/">акции</a>

    </div>
</header>
{{-- </main> --}}

<script src="{{ asset('js/burger.js') }}"></script>
