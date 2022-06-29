<?php
    if(isset($_POST['submit'])) {
        $number = 0;
        $postuser = $_POST['user'];
        $postpassword = $_POST['password'];

        if(empty($postuser)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'USR_NFILL';
            $error[$number]['message'] = 'The username has not been filled in!';
            $number++;
        }
        if(empty($postpassword)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'PSSW_NFILL';
            $error[$number]['message'] = 'The password has not been filled in!';
            $number++;
        }
        if(empty($error)) {
            $select = $conn->query("SELECT * FROM users WHERE `username` = '$postuser' OR `email` = '$postuser'")->fetch();
            
            if(empty($select)) {
                $error[$number]['code'] = 404;
                $error[$number]['type'] = 'UKWN_USR';
                $error[$number]['message'] = 'This email or Username is not known for me!';
                $number++;
            }
            if(empty($error)) {
                if(password_verify($postpassword, $select['password'])) {
                    $username = $_SESSION['user']['acc']['username'] = $select['username'];
                    $email = $_SESSION['user']['acc']['email'] = $select['email'];
                    $userid = $_SESSION['user']['acc']['userid'] = $select['userid'];
                    
                    header('location: /user/dashboard');
                } else {
                    $error[$number]['code'] = 404;
                    $error[$number]['type'] = 'WRNG_PSW';
                    $error[$number]['message'] = 'Wrong user/email or password, please try again!';
                    $number++;
                }
            }
        }
    }