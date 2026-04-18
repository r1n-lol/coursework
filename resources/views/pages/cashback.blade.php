@extends('layouts.main')

@php
    $title = 'Кэшбек';
    $pageCss = 'cashback';
@endphp

@section('content')
    <main class="cashback-page">
        <section class="cashback-hero">
            <div class="cashback-hero__content">
                <p class="cashback-kicker">Программа лояльности</p>
                <h1 class="cashback-title">Кэшбек, который бодрит</h1>
                <p class="cashback-lead">
                    Копите бонусы с каждого заказа, получайте подарки и возвращайтесь за любимым кофе с еще большей
                    выгодой. Все просто, быстро и без лишних карточек в кошельке.
                </p>
                <div class="cashback-hero__stats">
                    <div class="cashback-stat">
                        <strong>до 10%</strong>
                        <span>бонусами с заказа</span>
                    </div>
                    <div class="cashback-stat">
                        <strong>до 50%</strong>
                        <span>можно оплатить бонусами</span>
                    </div>
                    <div class="cashback-stat">
                        <strong>+300</strong>
                        <span>бонусов в день рождения</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="cashback-grid">
            <article class="cashback-card cashback-card--accent">
                <span class="cashback-card__label">Как это работает</span>
                <h2>Покупаете кофе, получаете бонусы, тратите на следующий заказ</h2>
                <p>
                    1 бонус = 1 ₽. Начисление идет автоматически после оплаты заказа, а списать бонусы можно на напитки,
                    десерты и другие позиции в рамках программы.
                </p>
            </article>

            <article class="cashback-card">
                <span class="cashback-card__label">Что дает программа</span>
                <ul class="cashback-list">
                    <li>кэшбек бонусами за каждый заказ</li>
                    <li>подарок на день рождения</li>
                    <li>доступ к акциям и закрытым предложениям</li>
                    <li>удобный учет бонусов по номеру телефона</li>
                </ul>
            </article>

            <article class="cashback-card cashback-card--dark">
                <span class="cashback-card__label">Для своих</span>
                <h2>Больше заходите, больше получаете</h2>
                <p>
                    Программа лояльности создана для тех, кто заскакивает к нам не случайно, а делает это частью
                    маршрута, ритуала и городского дня.
                </p>
            </article>
        </section>

        <section class="cashback-steps">
            <div class="cashback-steps__intro">
                <p class="cashback-kicker cashback-kicker--soft">Как стать участником</p>
                <h2>Три простых шага</h2>
            </div>

            <div class="cashback-steps__grid">
                <article class="step-card">
                    <span class="step-number">01</span>
                    <h3>Зарегистрируйтесь</h3>
                    <p>Оставьте номер телефона у бариста или в форме на сайте, чтобы привязать свой профиль.</p>
                </article>

                <article class="step-card">
                    <span class="step-number">02</span>
                    <h3>Совершайте заказы</h3>
                    <p>С каждого оплаченного заказа бонусы начисляются автоматически на ваш баланс.</p>
                </article>

                <article class="step-card">
                    <span class="step-number">03</span>
                    <h3>Используйте бонусы</h3>
                    <p>Оплачивайте ими часть следующего заказа и следите за персональными предложениями.</p>
                </article>
            </div>
        </section>

        <section class="cashback-levels">
            <div class="cashback-levels__head">
                <span class="cashback-card__label">Уровни</span>
                <h2>Чем чаще вы с нами, тем приятнее условия</h2>
            </div>

            <div class="cashback-levels__grid">
                <article class="level-card">
                    <h3>Старт</h3>
                    <p class="level-card__value">5%</p>
                    <p>для новых участников</p>
                </article>

                <article class="level-card">
                    <h3>Свой</h3>
                    <p class="level-card__value">7%</p>
                    <p>для тех, кто регулярно заходит за кофе</p>
                </article>

                <article class="level-card">
                    <h3>На стиле</h3>
                    <p class="level-card__value">10%</p>
                    <p>максимальный кэшбек для активных гостей</p>
                </article>
            </div>
        </section>

        <section class="cashback-banner">
            <div class="cashback-banner__body">
                <span class="cashback-card__label">Бонусы ко дню рождения</span>
                <h2>Дарим 300 бонусов, чтобы праздник был еще вкуснее</h2>
                <p>
                    Укажите дату рождения в профиле заранее, и бонусы автоматически придут в праздничный период. Их
                    можно потратить на любимый напиток, десерт или приятное дополнение к заказу.
                </p>
            </div>
        </section>

        <section class="cashback-faq">
            <div class="cashback-levels__head">
                <span class="cashback-card__label">FAQ</span>
                <h2>Частые вопросы</h2>
            </div>

            <div class="cashback-faq__grid">
                <article class="faq-card">
                    <h3>Как начисляются бонусы?</h3>
                    <p>После оплаты заказа по номеру телефона или привязанному профилю участника программы.</p>
                </article>

                <article class="faq-card">
                    <h3>На что можно потратить?</h3>
                    <p>На часть следующего заказа. 1 бонус = 1 ₽, а точный процент списания зависит от уровня.</p>
                </article>

                <article class="faq-card">
                    <h3>Если бонусы не пришли?</h3>
                    <p>Напишите нам через контакты на сайте или скажите бариста, и мы быстро проверим заказ.</p>
                </article>
            </div>
        </section>
    </main>
@endsection
