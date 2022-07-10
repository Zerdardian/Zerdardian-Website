<?php
    use Dotenv\Dotenv;
    session_start();
    // Load base
    $base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
    $url  = $_SERVER['HTTP_HOST'];
    $dotenv = Dotenv::createImmutable($base);
    $dotenv->load();

    $page;
    if (!empty($_GET['one'])) {
        $page = $_GET['one'];
        if (str_contains($page, '.php')) {
            $page = str_replace('.php', '', $page);
        }
    }

    try {
        $conn = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    $pagetitle = "Zerdardian";

    if (!empty($_SESSION['user']['acc'])) {
        $username = $_SESSION['user']['acc']['username'];
        $userid = $_SESSION['user']['acc']['userid'];
        $email = $_SESSION['user']['acc']['email'];
        $profilepicture = "http://ddragon.leagueoflegends.com/cdn/12.12.1/img/profileicon/588.png";
    }
