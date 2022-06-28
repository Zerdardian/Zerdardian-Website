<?php
    use Dotenv\Dotenv;
    session_start();
    // Load base
    $base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
    $dotenv = Dotenv::createImmutable($base);
    $dotenv->load();
    
    if(empty($_GET['page'])) {
        $GETpage = 'index';
    } else {
        $GETpage = $_GET['page'];
    }
    try {
        $conn = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    // Check if logged in
    if(empty($_SESSION['user']['acc'])) {
        $log = false;
    } else {
        $username = $_SESSION['user']['acc']['username'];
        $email = $_SESSION['user']['acc']['email'];
        $userid = $_SESSION['user']['acc']['userid'];
        $logtoken = $_SESSION['user']['acc']['logtoken'];
        $log = true;
    }

    // Page title 
    $pagetitle = "";

    switch($GETpage) {
        case "blog":
            if(isset($_GET['blogname'])) {
                $pagetitle = "Blog pagina";
            } else {
                $pagetitle = "Blog pagina";
            }
            break;
        default:
            $pagetitle = "Welkom op Zerdardian";
            break;
    }
?>