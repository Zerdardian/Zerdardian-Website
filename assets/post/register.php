<?php
    if(isset($_POST['submit'])) {
        $_SESSION['errornumber'] = 0;
        $postusername = strtolower($_POST['username']);
        $postemail = strtolower($_POST['email']);
        $postpassword = $_POST['password'];
        $postrepassword = $_POST['repassword'];

        if(empty($postusername)) {
            $error[$_SESSION['errornumber']]['code'] = 404;
            $error[$_SESSION['errornumber']]['type'] = 'USR_NFILL';
            $error[$_SESSION['errornumber']]['message'] = 'The username has not been filled in!';
            $_SESSION['errornumber']++;
        }
        if(empty($postemail)) {
            $error[$_SESSION['errornumber']]['code'] = 404;
            $error[$_SESSION['errornumber']]['type'] = 'EMAIL_NFILL';
            $error[$_SESSION['errornumber']]['message'] = 'The email has not been filled in!';
            $_SESSION['errornumber']++;
        }
        if(empty($postpassword)) {
            $error[$_SESSION['errornumber']]['code'] = 404;
            $error[$_SESSION['errornumber']]['type'] = 'PSSW_NFILL';
            $error[$_SESSION['errornumber']]['message'] = 'The password has not been filled in!';
            $_SESSION['errornumber']++;
        }
        if(empty($postrepassword)) {
            $error[$_SESSION['errornumber']]['code'] = 404;
            $error[$_SESSION['errornumber']]['type'] = 'PSSW_NFILL';
            $error[$_SESSION['errornumber']]['message'] = 'Please re enter your password';
            $_SESSION['errornumber']++;
        }
        if(empty($error)) {
            $uppercase      = preg_match('@[A-Z]@', $postpassword);
            $lowercase      = preg_match('@[a-z]@', $postpassword);
            $number         = preg_match('@[0-9]@', $postpassword);
            $specialChars   = preg_match('@[^\w]@', $postpassword);

            $selectusername = $conn->query("SELECT username FROM users WHERE `username`='$postusername'")->fetch();
            $selectemail = $conn->query("SELECT email FROM users WHERE `username`='$postemail'")->fetch();
            $forbidden = $conn->query("SELECT * FROM forbiddenusernames INNER JOIN forbiddenreason WHERE forbiddenreason.id = forbiddenusernames.reason")->fetchAll();
            foreach($forbidden as $item) {
                $all[] = $item['username'];
                if($item['reason'] == 1) {
                    $forbidden[] = $item['username'];
                }
                if($item['reason'] == 2) {
                    $bad[] = $item['username'];
                }
                if($item['reason'] == 3) {
                    $celeb[] = $item['username'];
                }
            }
            foreach($bad as $check) {
                if(str_contains($postusername, $check)) {
                    $error[$_SESSION['errornumber']]['code'] = 404;
                    $error[$_SESSION['errornumber']]['type'] = 'USRN_FBDN';
                    $error[$_SESSION['errornumber']]['message'] = 'This username contains something that is not allowed in general!';
                    $_SESSION['errornumber']++; 
                }
            }
            if(in_array($postusername, $all)) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'USRN_FBDN';
                $error[$_SESSION['errornumber']]['message'] = 'Its not allowed to take this name in gerneral!!';
                $_SESSION['errornumber']++;
            }
            if(in_array($postusername, $celeb)) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'USRN_FBDN';
                $error[$_SESSION['errornumber']]['message'] = 'Its not allowed to take celebrities as usernames in case of faking. If you want a celebrity account and you are the real deal, please contact me at <a href="mailto:info@zerdardian.com">info@zerdardian.com</a> to get it sorted out!';
                $_SESSION['errornumber']++;
            }
            if(in_array($postusername, $forbidden)) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'USRN_FBDN';
                $error[$_SESSION['errornumber']]['message'] = 'This username has been removed from being used in general.';
                $_SESSION['errornumber']++;
            }

            if(!empty($selectusername)) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'USRN_TAKEN';
                $error[$_SESSION['errornumber']]['message'] = 'This username has already been taken!';
                $_SESSION['errornumber']++;
            }

            if(!empty($selectemail)) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'EML_TAKEN';
                $error[$_SESSION['errornumber']]['message'] = 'This email has already been taken and verified!';
                $_SESSION['errornumber']++;
            }

            if($postpassword != $postrepassword) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NOT_SAME';
                $error[$_SESSION['errornumber']]['message'] = 'The re entered password does not match the original password!';
                $_SESSION['errornumber']++;
            }

            if(strlen($postpassword) < 8) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NOT_SHORT';
                $error[$_SESSION['errornumber']]['message'] = 'The entered password is to short, make it at least 8 digets long to continue!';
                $_SESSION['errornumber']++;
            }

            if(!$number) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NO_NMBR';
                $error[$_SESSION['errornumber']]['message'] = 'Please make sure the entered password has one number inside!';
                $_SESSION['errornumber']++; 
            }

            if(!$uppercase) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NO_UPPC';
                $error[$_SESSION['errornumber']]['message'] = 'Please make sure your password contains a uppercase letter';
                $_SESSION['errornumber']++; 
            }

            if(!$lowercase) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NO_UPPC';
                $error[$_SESSION['errornumber']]['message'] = 'Please make sure your password contains a lowercase letter like';
                $_SESSION['errornumber']++; 
            }

            if(!$specialChars) {
                $error[$_SESSION['errornumber']]['code'] = 404;
                $error[$_SESSION['errornumber']]['type'] = 'PSSW_NO_SPCLCH';
                $error[$_SESSION['errornumber']]['message'] = 'Please make sure your password contains a special character like # or $ on your keyboard.';
                $_SESSION['errornumber']++; 
            }

            if(empty($error)) {
                $finalusername = $postusername;
                $finalemail = $postemail;
                $finalpassword = password_hash($postpassword, PASSWORD_DEFAULT);
                $userid = uniqid('zer', true);
                $verify = uniqid('ver', true);
                $link = $url . "/verify/$finalemail/$verify";
                $change = [
                    'context' => "Accountregistratie op Zerdardian",
                    'title' => "Welkom bij de club, $finalusername",
                    'username' => $finalusername,
                    'email' => $finalemail,
                    'verifylink' => $link
                ];
                $insert = $conn->prepare("INSERT INTO `users`(`userid`, `username`, `email`, `password`) VALUES ('$userid','$finalusername','$finalemail','$finalpassword')");
                if($insert->execute()) {
                    $conn->prepare("INSERT INTO `settings`(`userid`) VALUES ('$userid')")->execute();
                    $conn->prepare("INSERT INTO `userinfo`(`userid`, `displayname`) VALUES ('$userid', '$finalusername')")->execute();
                    $conn->prepare("INSERT INTO `verify`(`userid`, `email`, `verifiedcode`) VALUES ('$userid','$finalemail','$verify')")->execute();
                    sendMail($finalemail, $finalusername, "Verify Mailer", 'Zerdardian Verify Mailer', 'register', $change);
                    header('location: /login/succes/');
                } else {
                    $error[$_SESSION['errornumber']]['code'] = 404;
                    $error[$_SESSION['errornumber']]['type'] = 'DB_ERR';
                    $error[$_SESSION['errornumber']]['message'] = 'Something went wrong when adding your account! Please try again later';
                    $error[$_SESSION['errornumber']]['db'] = $insert;
                    $_SESSION['errornumber']++; 
                }
            }
        }
    }
    $_SESSION['errornumber'] = 0;