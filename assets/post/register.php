<?php
    if(isset($_POST['submit'])) {
        $number = 0;
        $postusername = $_POST['username'];
        $postemail = $_POST['email'];
        $postpassword = $_POST['password'];
        $postrepassword = $_POST['repassword'];

        if(empty($postusername)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'USR_NFILL';
            $error[$number]['message'] = 'The username has not been filled in!';
            $number++;
        }
        if(empty($postemail)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'EMAIL_NFILL';
            $error[$number]['message'] = 'The email has not been filled in!';
            $number++;
        }
        if(empty($postpassword)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'PSSW_NFILL';
            $error[$number]['message'] = 'The password has not been filled in!';
            $number++;
        }
        if(empty($postrepassword)) {
            $error[$number]['code'] = 404;
            $error[$number]['type'] = 'PSSW_NFILL';
            $error[$number]['message'] = 'Please re enter your password';
            $number++;
        }
        if(empty($error)) {
            $selectusername = $conn->query("SELECT username FROM users WHERE `username`='$postusername'")->fetch();
            $selectemail = $conn->query("SELECT email FROM users WHERE `username`='$postemail'")->fetch();
            if(!empty($selectusername)) {
                $error[$number]['code'] = 404;
                $error[$number]['type'] = 'USRN_TAKEN';
                $error[$number]['message'] = 'This username has already been taken!';
                $number++;
            }
            if(!empty($selectemail)) {
                $error[$number]['code'] = 404;
                $error[$number]['type'] = 'EML_TAKEN';
                $error[$number]['message'] = 'This email has already been taken and verified!';
                $number++;
            }

            if($postpassword != $postrepassword) {
                $error[$number]['code'] = 404;
                $error[$number]['type'] = 'PSSW_NOT_SAME';
                $error[$number]['message'] = 'The re entered password does not match the original password!';
                $number++;
            }

            if(empty($error)) {
                $finalusername = $postusername;
                $finalemail = $postemail;
                $finalpassword = password_hash($postpassword, PASSWORD_DEFAULT);
                $userid = uniqid('zer', true);

                $insert = $conn->prepare("INSERT INTO `users`(`userid`, `username`, `email`, `password`) VALUES ('$userid','$finalusername','$finalemail','$finalpassword')");
                if($insert->execute()) {
                    header('location: /login/succes/');
                } else {
                    $error[$number]['code'] = 404;
                    $error[$number]['type'] = 'DB_ERR';
                    $error[$number]['message'] = 'Something went wrong when adding your account! Please try again later';
                    $error[$number]['db'] = $insert;
                    $number++; 
                }
            }
        }
    }