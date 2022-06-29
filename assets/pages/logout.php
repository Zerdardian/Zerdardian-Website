<?php
// if you read this, Simple script ever...
    if(!empty($_SESSION['user']['acc'])) {
        $_SESSION['user']['acc'] = [];
        header('location: /');
    }
?>