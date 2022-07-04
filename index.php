<?php
    require_once realpath(__DIR__ . '/vendor/autoload.php');
    include_once "./assets/php/preload.php";
    include_once "./assets/php/functions.php";
    if (!empty($_SESSION['user']['acc'])) {
        $username = $_SESSION['user']['acc']['username'];
        $userid = $_SESSION['user']['acc']['userid'];
        $email = $_SESSION['user']['acc']['email'];
    }
    include_once "./assets/php/header.php";
    include_once "./assets/php/main.php";
    include_once "./assets/php/footer.php";
