@extends('layouts.main')

@php
    $title = 'Ништяк Бодрит';
    $pageCss = 'index';
@endphp

@section('content')
    <div class="slider">
        <div class="slides">
            <div class="slide active"></div>
            <div class="slide two"></div>
            <div class="slide tree"></div>
        </div>

        <div class="dots"></div>
    </div>

    <main>
        <div class="container-cart">
            <div class="big-cart-conteiner">
                <img src="{{ asset('media/images/big-cart.webp') }}" alt="">
                <div class="big-cart-caption">
                    <h1 class="cart-title">На любой вкус</h1>
                    <p class="cart-p big">Выбирайте что пить и где прямо сейчас.</p>
                </div>
            </div>
            <div class="lit-cartconteiner">
                <div class="lit-cont-top">
                    <img src="{{ asset('media/images/menu.jpg') }}" alt="">
                    <div class="lit-cart-caption">
                        <h1 class="cart-title">Меню</h1>
                        <p class="cart-p">
                            Ознакомтесь с меню на сайте
                            или приходите в гости.
                        </p>
                        <a href="/" class="cart-btn">Подробнее</a>
                    </div>
                </div>
                <div class="lit-cont-bottom">
                    <img src="{{ asset('media/images/home-coffee.jpg') }}" alt="">
                    <div class="lit-cart-caption">
                        <h1 class="cart-title">Домой</h1>
                        <p class="cart-p">Кофе для домй — в зёрнах или молотый.
                            Заваривайте свежий кофе у себя дома </p>
                        <a href="/" class="cart-btn">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
    </main>



    <script src="{{ asset('js/slider.js') }}"></script>
@endsection
