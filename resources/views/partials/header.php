<?php
require_once __DIR__ . '/../config/init.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/common.css">

    <title><?= isset($title) ? $title : 'Ошибка' ?></title>

    <?php if (!empty($pageCss)) : ?>
        <link rel="stylesheet" href="/css/<?= $pageCss ?>.css">
    <?php endif; ?>
</head>



<body>

    <main>
        <header>
            <nav>

                <div class="nav-item">

                    <div class="nav-link"><a href="#">КОФЕ</a></div>

                    <div class="dropdown">
                        <a href="menu.php">Меню</a>
                        <a href="home.php">Домой</a>
                    </div>

                </div>

                <div class="nav-link"><a href="about-us.php">КЭШБЭК</a></div>
                <a href="index.php" class="logo"><img src="../media/imgs/nb.png" alt="Ништяк Бодрит"></a>

                <div class="nav-item">
                    <div class="nav-link"><a href="#">КТО МЫ</a></div>

                    <div class="dropdown">
                        <a href="about-us.php">Про нас</a>
                        <a href="contacts.php">Контакты</a>
                    </div>
                </div>
                <div class="nav-link"><a href="seil.php">AКЦИИ</a></div>



            </nav>

            <div class="burger" id="burger">
                <span></span>
                <span></span>
                <span></span>
            </div>


            <!-- Дополнительное правое меню -->
            <div class="side-menu" id="sideMenu">
                <a href="profile.php">Профиль</a>
                <a href="basket.php">Корзина</a>
                <a href="contacts.php">Контакты</a>
                <a href="menu.php">Меню</a>
                <a href="home.php">Домой</a>
                <a href="about-us.php">Кэшбек</a>
                <a href="seil.php">Акции</a>
                
                
                
            </div>
        </header>
    </main>

    <script src="../js/burger.js"></script>
</body>

</html>