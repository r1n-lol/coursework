<?php
require_once __DIR__ . '/config/init.php';
$title = 'Ништяк Бодрит';
$pageCss = 'index';
require_once __DIR__ . '/partials/header.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>


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
                <img src="media/imgs/big-cart.webp" alt="">
                <div class="big-cart-caption">
                    <h1 class="cart-title">На любой вкус</h1>
                    <p class="cart-p big">Выбирайте что пить и где прямо сейчас.</p>
                </div>
            </div>
            <div class="lit-cartconteiner">
                <div class="lit-cont-top">
                    <img src="media/imgs/menu.jpg" alt="">
                    <div class="lit-cart-caption">
                        <h1 class="cart-title">Меню</h1>
                        <p class="cart-p">
                            Ознакомтесь с меню на сайте
                            или приходите в гости.
                        </p>
                        <a href="" class="cart-btn">Подробнее</a>
                    </div>
                </div>
                <div class="lit-cont-bottom">
                    <img src="media/imgs/home-coffee.jpg" alt="">
                    <div class="lit-cart-caption">
                        <h1 class="cart-title">Домой</h1>
                        <p class="cart-p">Кофе для домй — в зёрнах или молотый. 
                            Заваривайте свежий кофе у себя дома </p>
                        <a href="" class="cart-btn">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/partials/footer.php';?>

    </main>



    <script src="js/slider.js"></script>
</body>

</html>