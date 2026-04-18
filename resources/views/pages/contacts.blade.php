@extends('layouts.main')

@php
    $title = 'Связь';
    $pageCss = 'contacts';
@endphp

@section('content')
    <main class="contacts-page">
        <section class="contacts-hero">
            <div class="contacts-hero__content">
                <p class="contacts-kicker">Всегда рядом</p>
                <h1 class="contacts-title">Связь</h1>
            </div>
        </section>

        <section class="contacts-grid">
            <article class="contact-card contact-card--accent">
                <span class="contact-card__label">Адрес</span>
                <h2>Ждем вас каждый день</h2>
                <p>г. Москва, ул. Примерная, 18</p>
                <p>Вход с улицы, рядом с парковкой и летней зоной.</p>
            </article>

            <article class="contact-card">
                <span class="contact-card__label">Режим работы</span>
                <h2>Открыты без длинных пауз</h2>
                <ul class="contact-list">
                    <li>Пн–Пт: 08:00–22:00</li>
                    <li>Сб–Вс: 09:00–23:00</li>
                    <li>Кухня и бар работают весь день</li>
                </ul>
            </article>

            <article class="contact-card">
                <span class="contact-card__label">Связь</span>
                <h2>Отвечаем быстро</h2>
                <ul class="contact-list">
                    <li><a href="tel:+79990000000">+7 (999) 000-00-00</a></li>
                    <li><a href="mailto:hello@n-bodrit.ru">hello@n-bodrit.ru</a></li>
                    <li><a href="https://t.me" target="_blank" rel="noreferrer">Telegram</a></li>
                </ul>
            </article>
        </section>

        <section class="contacts-banner">
            <div class="contacts-banner__body">
                <span class="contact-card__label">Как нас найти</span>
                <h2>Загляните на чашку кофе по пути на работу, учебу или домой</h2>
                <p>
                    Если хотите забрать заказ без ожидания, позвоните заранее. Подскажем по меню, акциям и поможем
                    выбрать зерно домой.
                </p>
            </div>
        </section>

        <section class="contacts-grid contacts-grid--bottom">
            <article class="contact-card contact-card--wide">
                <span class="contact-card__label">Для предложений</span>
                <h2>Сотрудничество и мероприятия</h2>
                <p>
                    Пишите, если хотите обсудить партнерство, корпоративные заказы или камерное событие в нашей
                    кофейне.
                </p>
                <a href="mailto:partners@n-bodrit.ru" class="contact-inline-link">partners@n-bodrit.ru</a>
            </article>

            <article class="contact-card contact-card--social">
                <span class="contact-card__label">Соцсети</span>
                <h2>Там новости и новинки</h2>
                <div class="contact-socials">
                    <a href="/" aria-label="Telegram">
                        <img src="{{ asset('media/images/tg.svg') }}" alt="Telegram">
                    </a>
                    <a href="/" aria-label="VK">
                        <img src="{{ asset('media/images/vk.svg') }}" alt="VK">
                    </a>
                    <a href="/" aria-label="Instagram">
                        <img src="{{ asset('media/images/inst.svg') }}" alt="Instagram">
                    </a>
                </div>
            </article>
        </section>
    </main>
@endsection
