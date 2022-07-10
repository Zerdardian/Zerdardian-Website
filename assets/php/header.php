<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest">
    <script src="/assets/js/scripts/require.js"></script>
    <link rel="stylesheet" type='text/css' href="/assets/css/app.css">
    <link rel="stylesheet" type='text/css' href="/node_modules/@claviska/jquery-minicolors/jquery.minicolors.css">
    <!-- <script src="/node_modules/@claviska/jquery-minicolors/jquery.minicolors.min.js"></script> -->
    <title><?= $pagetitle ?></title>
</head>

<body>
    <div id="main">
        <header id="header">
            <div id="headermenu">
                <div id="headermenubutton">
                    <div class="strokes first"></div>
                    <div class="strokes second"></div>
                    <div class="strokes third"></div>
                </div>
                <div id="headermenuitems">
                    <a href="/">
                        <div class="item" id="hhomeitem">
                            Home
                        </div>
                    </a>
                    <a href="/aboutme">
                        <div class="item" id="haboutmeitem">
                            About me
                        </div>
                    </a>
                    <a href="/portofolio">
                        <div class="item" id="hportofolioitem">
                            Portofolio
                        </div>
                    </a>
                    <a href="/blog">
                        <div class="item" id="hblogitem">
                            Blog
                        </div>
                    </a>
                    <div class="item" id="haccountitem">
                        <?php
                        if (empty($_SESSION['user']['acc'])) {
                        ?>
                            <a href="/login">
                                <div class="hlogin item">
                                    Login
                                </div>
                            </a>
                            <a href="/register">
                                <div class="hregister item">
                                    Register
                                </div>
                            </a>
                        <?php
                        }

                        if (!empty($_SESSION['user']['acc'])) {
                        ?>
                            <a href="/user/<?= $username ?>/">
                                <div class="image">
                                    <img src="<?=$profilepicture?>" alt="Profile Icon">
                                </div>
                            </a>

                            <a href="/user/<?= $username ?>/">
                                <div class="husername text">
                                    <?= ucfirst($username) ?>
                                </div>
                            </a>
                            <a href="/logout">
                                <div class="hlogout text">
                                    Log out
                                </div>
                            </a>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </header>
        <div id="container">