@extends('layouts.main')

@php
    $title = 'Про нас';
    $pageCss = 'about';
@endphp

@section('content')
    <main class="about-page">
        <section class="about-hero">
            <div class="about-hero__content">
                <p class="about-kicker">Йошкар-Ола. Улица. Скейт.</p>
                <h1 class="about-title">Про нас</h1>
            </div>
        </section>

        <section class="about-grid">
            <article class="about-card about-card--accent">
                <span class="about-card__label">Откуда все началось</span>
                <h2>Из уличной культуры, а не из кабинета</h2>
                <p>
                    Сначала был Ништяк, браток — локальный бренд одежды для тех, кто живет доской, городом и своим
                    ритмом. Потом эта энергия перешла и в кофейню: такую же живую, простую и настоящую.
                </p>
            </article>

            <article class="about-card">
                <span class="about-card__label">Что для нас важно</span>
                <ul class="about-list">
                    <li>делать место для своих и для тех, кто любит городскую культуру</li>
                    <li>сохранять уличный характер, но без показной грубости</li>
                    <li>собирать вокруг бренда комьюнити, а не просто поток гостей</li>
                </ul>
            </article>

            <article class="about-card about-card--image">
                <span class="about-card__label">Настроение бренда</span>
                <h2>Скейтерский вайб, который бодрит</h2>
                <p>
                    Мы соединяем кофе, визуальный стиль и атмосферу улицы так, чтобы сюда хотелось возвращаться перед
                    каткой, после учебы, работы или просто ради своих.
                </p>
            </article>
        </section>

        <section class="about-banner">
            <div class="about-banner__body">
                <span class="about-card__label">Ништяк Бодрит</span>
                <h2>Это продолжение той же культуры, только в новом формате</h2>
                <p>
                    Не отдельный безликий проект, а естественное развитие бренда, выросшего из йошкар-олинской
                    скейт-среды. Мы хотим, чтобы в этом месте чувствовались локальные корни, характер улицы и энергия
                    людей, которые сами все это начали.
                </p>
            </div>
        </section>

        <section class="about-grid about-grid--bottom">
            <article class="about-card about-card--wide">
                <span class="about-card__label">Для кого мы</span>
                <h2>Для тех, кому близки движение, стиль и свои люди</h2>
                <p>
                    Для скейтеров, друзей бренда, любителей уличной эстетики и всех, кому нравится атмосфера без
                    фальши. Можно зайти за кофе на бегу, а можно остаться, встретиться с друзьями и поймать настроение
                    города.
                </p>
            </article>

            <article class="about-card">
                <span class="about-card__label">Город</span>
                <h2>Йошкар-Ола в основе</h2>
                <p>
                    Локальная история для нас не фон, а фундамент. Поэтому в Ништяк Бодрит остается ощущение места,
                    где бренд родился и вырос.
                </p>
            </article>
        </section>
    </main>
@endsection
