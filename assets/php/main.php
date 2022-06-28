<?php
if(empty($error)) {
    if(isset($_GET['page'])) {
        if(isset($_POST)) {
            $file = "$base"."assets/post/$GETpage.php";
            if(file_exists($file)) {
                include_once $file;
            }
        }
        $file = "$base"."assets/pages/$GETpage.php";
        if(file_exists($file)) {
            include_once $file;
        } else {
            $error['code'] = 404;
            $error['type'] = "UNKWN_PGE";
            $error['msg'] = "Deze pagina is niet gevonden! Probeer het opnieuw of keer terug naar de hoofdpagina!";
        }
    } else {
        include_once "./assets/pages/mainpage.php";
    } 
} else {
    include_once "$base"."assets/pages/error.php";
}