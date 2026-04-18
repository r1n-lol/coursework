<footer>
    <div class="footer-main">
        <div class="footer-cart">
            <h2>КОФЕ</h2>
            <a href="{{ route('menu') }}">меню</a>
            <a href="{{ route('home') }}">домой</a>
        </div>
        <div class="footer-cart">
            <a href="{{ route('cashback') }}">
                <h2 class="link-f">КЭШБЕК</h2>
            </a>
            <a href="{{ route('cashback') }}">программа лояльности</a>
        </div>
        <div class="footer-cart">
            <h2>КТО МЫ</h2>
            <a href="{{ route('about') }}">про нас</a>
            <a href="{{ route('contacts') }}">связь</a>
        </div>

        <div class="footer-cart soc">
            <h2>СОЦСЕТИ</h2>
            <div class="soc-a">
                <a href="/"><img src="{{ asset('media/images/tg.svg') }}" alt="тг"></a>
                <a href="/"><img src="{{ asset('media/images/vk.svg') }}" alt="вк"></a>
                <a href="/"><img src="{{ asset('media/images/inst.svg') }}" alt="инста"></a>
            </div>
        </div>
    </div>

    <div class="footer-img"><img src="{{ asset('media/images/logo-footer.jpg') }}" alt=""></div>
</footer>
