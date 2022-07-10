<?php
include_once "../../../vendor/autoload.php";
include_once "../../php/preload.php";
include_once "../../php/functions.php";
$base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
$url  = $_SERVER['HTTP_HOST'];
$http = $_SERVER['HTTP_ORIGIN'];
$data = [];
$data['post'] = $_POST;

if (!empty($_SESSION['user']['acc'])) {
    $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
    $date = $dicecheck['latestthrow'];
    $timedate = strtotime($date);
    $timedateyesterday = strtotime('-1 day');
    $timedatetoday = strtotime('now');

    if ($date != null) {
        if ($timedateyesterday > $timedate) {
            $data['code'] = 200;
            $data['type'] = 'THROWSUCC';
            $data['throw'] = true;
        } else {
            if (isset($_POST['spaceevent']) && $_POST['spaceevent'] == true) {
                $data['code'] = 200;
                $data['type'] = 'EXECEVT';
            } else {
                $data['code'] = 404;
                $data['type'] = 'THROWUNSUC';
                $data['message'] = "You have already thrown today! Come back tomorrow!";
                $data['throw'] = false;
            }
        }
    } else {
        $data['code'] = 200;
        $data['type'] = 'THROWSUCC';
        $data['throw'] = true;
    }

    if ($data['code'] == 200) {
        if ($_POST['function'] == 'do') {
            if (isset($_POST['dicethrow']) && $_POST['dicethrow'] == true) {
                $throw = $_POST['dicenumber'];
                $current = $_POST['lastspace'];
                $data['code'] = 200;
                $a = date("Y-m-d H:i:s");

                $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();

                $number = $dicecheck['spaceid'];

                // Your new space
                $final = $number + $throw;
                $finalspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$final")->fetch();
                $newcapfinal = $finalspacecap[0] + 1;
                $newcapfinal = intval($newcapfinal);

                // Old space
                $lastspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$current")->fetch();
                $lastcapfinal = $lastspacecap[0] - 1;
                $lastcapfinal = intval($lastcapfinal);

                $update = $conn->prepare("UPDATE `space-player` SET `spaceid`=$final, `latestthrow`='$a' WHERE `userid`='$userid'");
                if ($update->execute()) {
                    $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$newcapfinal WHERE `id`='$final'")->execute();
                    $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$lastcapfinal WHERE `id`='$current'")->execute();
                }
            }

            if (isset($_POST['spaceevent']) && $_POST['spaceevent'] == true) {
                $currentspace = $_POST['currentspace'];
                $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
                $space = $conn->query("SELECT * FROM `spaces` WHERE `id`='$currentspace'")->fetch();
                $spacevent = $conn->query("SELECT * FROM `spaceevents` WHERE `userid`='$userid' and `spaceid`=$currentspace")->fetch();
                
                if(empty($spacevent)) {
                    if($space['id'] == $dicecheck['spaceid']) {
                        switch($space['spacetype']) {
                            case 1: 
                                $data['code'] = 200;
                                $data['type'] = 'BLUSPC';
                                break;
                            case 2:
                                $data['code'] = 200;
                                $data['type'] = 'REDSPC';
                                break;
                            case 3:
                                $data['code'] = 200;
                                $data['type'] = 'QSTSPC';
                                break;
                            case 4:
                                $data['code'] = 200;
                                $data['type'] = 'LCKSPC';
                                break;
                            case 5:
                                $data['code'] = 200;
                                $data['type'] = 'UNLCKSPC';
                                break;
                            default: 
                                break;
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
