<?php
include_once "../../../vendor/autoload.php";
include_once "../../php/preload.php";
include_once "../../php/functions.php";
$base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
$url  = $_SERVER['HTTP_HOST'];
$http = $_SERVER['HTTP_ORIGIN'];
$data = [];

if ($_POST['typedata'] == 'getleagueuser') {
    $leagueuser = $_POST['leagueuser'];
    $leagueserver = $_POST['leagueserver'];

    InsertLeagueUsername($leagueuser, $leagueserver);
    $user = getLeagueUser($leagueuser, $leagueserver);

    if (isset($user['type']) && $user['type'] == 'NOUSR') {
        $data['error'] = true;
        $data['type'] = "NOUSR";
        $data['message'] = "No user has been found!";
    } else {
        $profileicon = $user['profileiconid'];


        $data['error'] = false;
        $data['type'] = "USRFND";
        $data['server'] = $_POST['leagueserver'];
        $data['leagueid'] = $user['id'];
        $data['username'] = $user['name'];
        $data['profileicon'] = "http://ddragon.leagueoflegends.com/cdn/12.12.1/img/profileicon/$profileicon.png";
        $data['level'] = $user['summonerlevel'];
    }
}

if($_POST['typedata'] == 'insertleaguedata') {
    $server = $_POST['leagueserver'];
    $user = $_POST['leagueuser'];

    $insert = $conn->prepare("UPDATE connections SET `cleagueuser`=:leagueuser,`cleagueserver`=:leagueserver WHERE `userid`=:userid");
    $insert->bindParam(":leagueuser", $user);
    $insert->bindParam(":leagueserver", $server);
    $insert->bindParam(":userid", $userid);
    if($insert->execute()) {
        $data['succes'] = true;
    } else {
        $data['succes'] = false;
    }
}

echo json_encode($data);
return;
