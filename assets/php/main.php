<?php
if (empty($error)) {
    if(!empty($_SESSION['user']['acc'])) {
        $username = $_SESSION['user']['acc']['username'];
        $userid = $_SESSION['user']['acc']['userid'];
        $email = $_SESSION['user']['acc']['email'];    
    }

    if (isset($page)) {
        if (isset($_POST)) {
            $file = "$base" . "assets/post/$page.php";
            if (file_exists($file)) {
                include_once $file;
            }
        }
        $file = "$base" . "assets/pages/$page.php";
        if (file_exists($file)) {
            include_once $file;
        } else {
            $file = "$base" . "assets/pages/error.php";
            $error['code'] = 404;
            $error['type'] = "UNKWN_PGE";
            $error['msg'] = "Deze pagina is niet gevonden! Probeer het opnieuw of keer terug naar de hoofdpagina!";
            if (file_exists($file)) {
                include_once $file;
            }
        }
    } else {
        include_once "./assets/pages/mainpage.php";
    }
} else {
    include_once "$base" . "assets/pages/error.php";
}
