<?php
include_once "../../../vendor/autoload.php";
include_once "../../php/preload.php";
include_once "../../php/functions.php";
$base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
$url  = $_SERVER['HTTP_HOST'];
$http = $_SERVER['HTTP_ORIGIN'];

$data = [];
if (isset($_POST)) {
    $setting = $_POST['setting'];
} else {
    $setting = $_GET['setting'];
}

if (!empty($_SESSION['user']['acc'])) {
    if (isset($_POST)) {

        if ($_POST['typedata'] == 'checkmark') {
            // Settings, mailing...
            if ($setting = 'settings') {
                $id = $_POST['id'];
                $type = $_POST['type'];
                $name = $_POST['name'];
                $value = $_POST['value'];

                $select = $conn->query("SELECT * FROM settings WHERE userid = '$userid'")->fetch();
                if (!empty($select)) {
                    if ($id == 'allmail') {
                        $insert = $conn->prepare("UPDATE settings SET `mail_blogposts`=$value, `mail_messages`=$value, `mail_marketing`=$value WHERE `userid`='$userid'");
                        if ($insert->execute()) {
                            $bool = "$value";
                            if ($bool == 'false') {
                                $data['code'] = 200;
                                $data['type'] = "succes";
                                $data['message'] = "You are now succesfully unsubscribed to all mail!";
                                $data['id'] = $id;
                            }

                            if ($bool == 'true') {
                                $data['code'] = 200;
                                $data['type'] = "succes";
                                $data['message'] = "You are now succesfully subscribed to all mail!";
                                $data['id'] = $id;
                            }
                        }
                    } else {
                        $insert = $conn->prepare("UPDATE settings SET `$id`=$value WHERE `userid`='$userid'");
                        if ($insert->execute()) {
                            $mail = str_replace("_", " ", $id);
                            $bool = "$value";
                            if ($bool == 'false') {
                                $data['code'] = 200;
                                $data['type'] = "succes";
                                $data['message'] = "You have succesfully unsubscribed to $mail!";
                                $data['id'] = $id;
                            }

                            if ($bool == 'true') {
                                $data['code'] = 200;
                                $data['type'] = "succes";
                                $data['message'] = "You have succesfully subscribed to $mail! You will now recieve all notifications!";
                                $data['id'] = $id;
                            }
                        }
                    }
                }
            }
        }

        if ($_POST['typedata'] == 'input') {
            // Settings, base data
            if ($setting == 'settings') {
                $id = $_POST['id'];
                $type = $_POST['type'];
                $name = $_POST['name'];
                $value = $_POST['value'];

                $select = $conn->query("SELECT * FROM userinfo WHERE userid = '$userid'")->fetch();
                if (!empty($select)) {
                    $forbidden = $conn->query("SELECT * FROM forbiddenwords")->fetchAll();
                    $number = 0;
                    foreach ($forbidden as $item) {
                        $words[$number] = $item['word'];
                        $number++;
                    }
                    // Check Displayname
                    if ($id == 'displayname') {
                        if ($username != strtolower($_POST['value'])) {
                            $error['code'] = 402;
                            $error['type'] = 'USRN_NOT_SAME';
                            $error['message'] = "Username is not the same as your current one, please try again!";
                            $data = $error;
                        }
                    }
                    // Check day
                    if ($id == 'bday') {
                        if ($value < 1) {
                            $error['code'] = 402;
                            $error['type'] = 'UKWN_TIME';
                            $error['message'] = "Please enter a day that is known not a random on day 33 of march.!";
                            $data = $error;
                        }

                        if($value > 32) {
                            $error['code'] = 402;
                            $error['type'] = 'UKWN_TIME';
                            $error['message'] = "Please enter a day that is known not a random on day 33 of march.!";
                            $data = $error;
                        }
                    }

                    // Check month
                    if ($id == 'bmonth') {
                        if ($value < 1 || $value > 12) {
                            $error['code'] = 402;
                            $error['type'] = 'UKWN_TIME';
                            $error['message'] = "Please enter a valid month that is known. We sadly do not have Zelptember.";
                            $data = $error;
                        }
                    }
                    // Check year
                    if ($id == 'byear') {
                        if ($value < 1900 || $value > date("Y")) {
                            $error['code'] = 402;
                            $error['type'] = 'UKWN_TIME';
                            $error['message'] = "Please enter an age that is known to people. You are not born in the future, nor in 1800.";
                            $data = $error;
                        }
                    }

                    // 
                    foreach ($words as $item) {
                        if (str_contains(strtolower($value), $item)) {
                            $error['code'] = 403;
                            $error['type'] = 'FORB_WRD';
                            $error['message'] = "There is an forbidden word in the message, please try again";
                            $data = $error;
                        }
                    }

                    if (in_array(strtolower($value), $words, true)) {
                        $error['code'] = 403;
                        $error['type'] = 'FORB_WRD';
                        $error['message'] = "There is an forbidden word in the message, please try again";
                        $data = $error;
                    }

                    if (empty($error)) {
                        $insert = $conn->prepare("UPDATE userinfo SET `$id`='$value' WHERE `userid`='$userid'");
                        if ($insert->execute()) {
                            $data['code'] = 200;
                            $data['type'] = "succes";
                            $data['message'] = "Succesfully changed '$id' to '$value'";
                            $data['id'] = $id;
                        }
                    }
                }
            }
        }
    }
} else {
    $data['code'] = 401;
    $data['type'] = 'NT_LOG_IN';
    $data['message'] = "Please log in before attempting to change your userinfo";
}
echo json_encode($data);
return;
