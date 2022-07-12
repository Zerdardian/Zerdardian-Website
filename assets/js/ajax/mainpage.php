<?php
include_once "../../../vendor/autoload.php";
include_once "../../php/preload.php";
include_once "../../php/functions.php";
$base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
$url  = $_SERVER['HTTP_HOST'];
$http = $_SERVER['HTTP_ORIGIN'];
$data = [];

// Functions party

function changeSpaces(int $spaces, int $currentspace, int $typeadd)
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    $userid = $_SESSION['user']['acc']['userid'];

    $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();

    $current = $dicecheck['spaceid'];

    if ($current = $currentspace) {
        if ($typeadd == 1) {
            $final = $current + $spaces;

            $final = intval($final);
        }

        if ($typeadd == 0) {
            $final = $current - $spaces;

            if($final < 1) {
                $final = 1;
            }

            $final = intval($final);
        }

        $finalspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$final")->fetch();
        $newcapfinal = $finalspacecap[0] + 1;
        $newcapfinal = intval($newcapfinal);

        // Old space
        $lastspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$currentspace")->fetch();
        $lastcapfinal = $lastspacecap[0] - 1;
        $lastcapfinal = intval($lastcapfinal);

        $update = $conn->prepare("UPDATE `space-player` SET `spaceid`=$final WHERE `userid`='$userid'");
        if ($update->execute()) {
            $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$newcapfinal WHERE `id`='$final'")->execute();
            $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$lastcapfinal WHERE `id`='$currentspace'")->execute();

            $data['code'] = 200;
            $data['type'] = "SUCCHAN";
        }
        return $data;
    }
}

// Main area

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
            if (isset($_POST['spaceevent']) && $_POST['spaceevent'] == true || isset($_POST['event']) && $_POST['event'] == true || isset($_POST['eventgiven']) && $_POST['eventgiven'] == true) {
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
            // Dice throw in general
            if (isset($_POST['dicethrow']) && $_POST['dicethrow'] == true) {
                $throw = $_POST['dicenumber'];
                $current = $_POST['lastspace'];
                $data['code'] = 200;
                $a = date("Y-m-d H:i:s");

                $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();

                $number = $dicecheck['spaceid'];

                $update = $conn->prepare("UPDATE `space-player` SET `latestthrow`='$a' WHERE `userid`='$userid'");
                if ($update->execute()) {
                    $throw = changeSpaces($throw, $number, 1);
                    if ($throw['code'] == 200) {
                        $data['code'] = 200;
                    }
                }
            }
            // All spaces based on they events
            if (isset($_POST['spaceevent']) && $_POST['spaceevent'] == true) {
                $currentspace = $_POST['currentspace'];
                $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
                $space = $conn->query("SELECT * FROM `spaces` WHERE `id`='$currentspace'")->fetch();
                $spacevent = $conn->query("SELECT * FROM `spaceevents` WHERE `userid`='$userid' and `spaceid`=$currentspace")->fetch();

                if (empty($spacevent)) {
                    if ($space['id'] == $dicecheck['spaceid']) {
                        switch ($space['spacetype']) {
                            case 1:
                                $data['code'] = 200;
                                $data['type'] = 'BLUSPC';
                                break;
                            case 2:
                                $data['code'] = 200;
                                $data['type'] = 'REDSPC';
                                $data['return'] = rand(1, 3);
                                break;
                            case 3:
                                $questfiles = $files['party']['question'];
                                $data['files'] = $questfiles;
                                $randomfile = array_rand($questfiles, 1);
                                $data['code'] = 200;
                                $data['type'] = 'QSTSPC';
                                $data['file'] = $questfiles[$randomfile];
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
            // Getting events.
            if (isset($_POST['event']) && $_POST['event'] == true) {
                // Red spaces                
                if ($_POST['typespace'] == 2) {
                    $spaces = $_POST['spaces'];
                    $currentspace = $_POST['currentspace'];

                    $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
                    if ($dicecheck['spaceid'] == $currentspace) {
                        $final = $currentspace - $spaces;
                        $final = intval($final);
                        $update = $conn->prepare("UPDATE `space-player` SET `spaceid`=$final WHERE `userid`='$userid'");
                        if ($update->execute()) {
                            // Your new space
                            $finalspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$final")->fetch();
                            $newcapfinal = $finalspacecap[0] + 1;
                            $newcapfinal = intval($newcapfinal);

                            // Old space
                            $lastspacecap = $conn->query("SELECT `spacecapacity` FROM `spaces` WHERE `id`=$currentspace")->fetch();
                            $lastcapfinal = $lastspacecap[0] - 1;
                            $lastcapfinal = intval($lastcapfinal);

                            $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$newcapfinal WHERE `id`='$final'")->execute();
                            $conn->prepare("UPDATE `spaces` SET `spacecapacity`=$lastcapfinal WHERE `id`='$currentspace'")->execute();
                            $data['code'] = 200;
                            $data['type'] = "MVBRDSP";
                            $data['message'] = `You have been moved back ` . $spaces . ` space(s)!`;
                            $data['current'] = $currentspace;
                            $data['new'] = $final;
                            $data['move'] = $spaces;
                        }
                    }
                }
            }
            // And doing the events in genearl
            if (isset($_POST['eventgiven']) && $_POST['eventgiven'] == true) {
                $current = $_POST['currentspace'];
                $eventid = $_POST['eventid'];

                $dicecheck = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
                $event = $conn->query("SELECT * FROM `spacequestion` WHERE `questioneventid`='$eventid'")->fetch();

                if ($dicecheck['spaceid'] == $current) {
                    if (!empty($event)) {
                        $given = $event['timesgot'] + 1;
                        $conn->prepare("UPDATE `spacequestion` SET `timesgot`=$given WHERE `questioneventid`='$eventid'")->execute();
                        $currentspace = $dicecheck['spaceid'];
                        $updatespaces = changeSpaces($event['spaces'], $dicecheck['spaceid'], $event['type']);

                        if ($updatespaces['code'] == 200) {
                            $data['code'] = 200;
                            $data['type'] = "SUCEVNT";
                            $data['spaces'] = $event['spaces'];
                            $data['givetype'] = $event['type'];
                            $data['what'] = 'spaces';
                        }
                    } else {
                        $data['code'] = 404;
                        $data['type'] = "UNKWNEVMT";
                        $data['message'] = "Unknown error! If this keeps happening every time you land on a event space. let me know!";
                    }
                } else {
                    $data['code'] = 404;
                    $data['type'] = "NTONSPCE";
                    $data['message'] = "You are not on that given space for events! Try again while you are on that given space.";
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
