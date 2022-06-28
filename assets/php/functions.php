<?php
function getHttpCode($http_response_header)
{
    if (is_array($http_response_header)) {
        $parts = explode(' ', $http_response_header[0]);
        if (count($parts) > 1) //HTTP/1.0 <code> <text>
            return intval($parts[1]); //Get code
    }
    return 0;
}

// General functions
function insertPageName(string $page, string $type)
{
    if (empty($page)) return;
    if (empty($type)) return;
}

// League of Legends

/**
 * Function InsertLeagueUsername.
 * The ability to add a user to the database with they username to have the ability to not touch the api that often. Only when required.
 * @param string $username 'Username of your league account!'
 * @return 'Succesful on adding the username or return a false statement'
 */
function InsertLeagueUsername(string $username) # Finished, Adding user to database or updating the user if requested!
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    $region = 'euw1';
    if (empty($username)) $username = $_ENV['LEAGUEUSER'];
    $apikey = "?api_key=" . $_ENV['RIOT_API_KEY'];
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/summoner/v4/summoners/by-name/$username" . $apikey, false, $context));

    $code = getHttpCode($http_response_header);
    $data['code'] = $code;

    if ($data['code'] == 200) {
        $data = [];
        $data['id'] = $response->id;
        $data['accountid'] = $response->accountId;
        $data['puuid'] = $response->puuid;
        $data['name'] = $response->name;
        $data['profileiconid'] = $response->profileIconId;
        $data['summonerlevel'] = $response->summonerLevel;
        $check = $conn->query("SELECT * FROM leagueprofile WHERE `leagueId` = '" . $data['id'] . "'")->fetch();
        if (empty($check)) {
            $insert = $conn->prepare("INSERT INTO `leagueprofile` (`leagueId`, `accountId`, `profileIconId`, `name`, `puuId`, `summonerLevel`) VALUES (:leagueid,:accountid,:profileiconid,:name,:puuid,:summonerlevel)");
            $insert->bindParam(':leagueid', $data['id'], PDO::PARAM_STR);
            $insert->bindParam(':accountid', $data['accountid'], PDO::PARAM_STR);
            $insert->bindParam(':profileiconid', $data['profileiconid'], PDO::PARAM_STR);
            $insert->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $insert->bindParam(':puuid', $data['puuid'], PDO::PARAM_STR);
            $insert->bindParam(':summonerlevel', $data['summonerlevel'], PDO::PARAM_STR);
            $insert->execute();
        } else {
            $update = $conn->prepare("UPDATE `leagueprofile` SET `profileIconId`=:profileiconid,`name`=:name,`summonerLevel`=:summonerlevel WHERE `leagueId` = :leagueid");
            $update->bindParam(':leagueid', $data['id'], PDO::PARAM_STR);
            $update->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $update->bindParam(':summonerlevel', $data['summonerlevel'], PDO::PARAM_STR);
            $update->bindParam(':profileiconid', $data['profileiconid'], PDO::PARAM_STR);
            $update->execute();
        }
        $data['message'] = "League account succesfully updated!";
        return $data;
    }

    if ($data['code'] == 404) {
        $data['message'] = "There is no League account assosiated with this region, try again!";
        return $data;
    }
}

/**
 * Function InsertLeaguePuuid.
 * The ability to add a user to the database with they puuid to insure you don't have to use the api that often.
 * @param string $puuid 'Users Puuid! Can be recieved with the function above or with matches'
 * @return 'Succesful adding the user to the database'
 */
function InsertLeaguePuuId(string $puuid) # Finished and mostly copied over from Username. Same deal as before
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    $region = 'euw1';
    $apikey = "?api_key=" . $_ENV['RIOT_API_KEY'];
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid" . $apikey, false, $context));
    $code = getHttpCode($http_response_header);
    $data['code'] = $code;

    if ($data['code'] == 200) {
        $data = [];
        $data['id'] = $response->id;
        $data['accountid'] = $response->accountId;
        $data['puuid'] = $response->puuid;
        $data['name'] = $response->name;
        $data['profileiconid'] = $response->profileIconId;
        $data['summonerlevel'] = $response->summonerLevel;
        $check = $conn->query("SELECT * FROM leagueprofile WHERE `leagueId` = '" . $data['id'] . "'")->fetch();
        if (empty($check)) {
            $insert = $conn->prepare("INSERT INTO `leagueprofile` (`leagueId`, `accountId`, `profileIconId`, `name`, `puuId`, `summonerLevel`) VALUES (:leagueid,:accountid,:profileiconid,:name,:puuid,:summonerlevel)");
            $insert->bindParam(':leagueid', $data['id'], PDO::PARAM_STR);
            $insert->bindParam(':accountid', $data['accountid'], PDO::PARAM_STR);
            $insert->bindParam(':profileiconid', $data['profileiconid'], PDO::PARAM_STR);
            $insert->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $insert->bindParam(':puuid', $data['puuid'], PDO::PARAM_STR);
            $insert->bindParam(':summonerlevel', $data['summonerlevel'], PDO::PARAM_STR);
            $insert->execute();
        } else {
            $update = $conn->prepare("UPDATE `leagueprofile` SET `profileIconId`=:profileiconid,`name`=:name,`summonerLevel`=:summonerlevel WHERE `leagueId` = :leagueid");
            $update->bindParam(':leagueid', $data['id'], PDO::PARAM_STR);
            $update->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $update->bindParam(':summonerlevel', $data['summonerlevel'], PDO::PARAM_STR);
            $update->bindParam(':profileiconid', $data['profileiconid'], PDO::PARAM_STR);
            $update->execute();
        }
        $data['message'] = "League account succesfully updated!";
        return $data;
    }

    if ($data['code'] == 404) {
        $data['message'] = "There is no League account assosiated with this region, try again!";
        return $data;
    }
}

/**
 * Time to insert your matches baby!
 * @param string $puuid "Use your puuid to get the matches"
 * @param int $max "Load up to 20 matches in the database!"
 * @return "Return the data in to the database so you can inspect them and use them if required for the site."
 */

function insertLeagueMatch(string $puuid, int $max) # Finished, loading Player info and match info if required!
{
    // Db connection
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }
    // Version check
    $versions = json_decode(file_get_contents("https://ddragon.leagueoflegends.com/api/versions.json"));
    $version = $versions[0];

    // Region
    $region = 'europe';
    $number = 0;

    if (empty($puuid)) return;
    if (empty($max)) return;
    if ($max < 1) $max = 1;
    if ($max > 20) $max = 20;

    $apikey = "api_key=" . $_ENV['RIOT_API_KEY'];
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    $matches = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/match/v5/matches/by-puuid/$puuid/ids?start=0&count=$max&" . $apikey, false, $context));
    $code = getHttpCode($http_response_header);
    $data['code'] = $code;
    // If the code is 200 than continue
    if ($data['code'] == 200) {
        // Check for each match
        foreach ($matches as $match) {
            $checkmatch = $conn->query("SELECT * FROM leaguegame WHERE matchid = '$match'")->fetchAll();
            if (empty($checkmatch)) {
                $pnumber = 0;
                $played[$number]['full'] = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/match/v5/matches/$match?" . $apikey, false, $context));
                $code = getHttpCode($http_response_header);
                $data['code'] = $code;

                if ($data['code'] == 200) {
                    foreach ($played[$number]['full']->metadata->participants as $user) {
                        $player = getLeaguePuuid($user);
                        $game[$number]['users'][$pnumber]['info'] = $player;
                        $game[$number]['users'][$pnumber]['stats'] = $played[$number]['full']->info->participants[$pnumber];

                        // Inserting
                        $matchid = $match;
                        $playerid = $user;
                        $matchcheck = $conn->query("SELECT * FROM leagueusergame WHERE `matchid`='$matchid' AND `puuid`='$playerid'")->fetch();
                        if (empty($matchcheck)) {
                            $team = $game[$number]['users'][$pnumber]['stats']->teamId;
                            $championid = $game[$number]['users'][$pnumber]['stats']->championId;
                            $role = $game[$number]['users'][$pnumber]['stats']->role;
                            $typerole = $game[$number]['users'][$pnumber]['stats']->lane;
                            $kills = $game[$number]['users'][$pnumber]['stats']->kills;
                            $assists = $game[$number]['users'][$pnumber]['stats']->assists;
                            $deaths = $game[$number]['users'][$pnumber]['stats']->deaths;
                            $solokills = $game[$number]['users'][$pnumber]['stats']->challenges->soloKills;
                            $checkwin = $game[$number]['users'][$pnumber]['stats']->win;
                            if (!empty($checkwin)) {
                                $win = 1;
                            } else {
                                $win = 0;
                            }
                            $physical = $game[$number]['users'][$pnumber]['stats']->physicalDamageDealtToChampions;
                            $magic = $game[$number]['users'][$pnumber]['stats']->magicDamageDealtToChampions;
                            $true = $game[$number]['users'][$pnumber]['stats']->trueDamageDealtToChampions;
                            $gold = $game[$number]['users'][$pnumber]['stats']->goldEarned;

                            $insert = $conn->prepare("INSERT INTO `leagueusergame`(`patch`, `matchid`, `puuid`, `team`, `championid`, `role`, `typerole`, `kills`, `assists`, `deaths`, `solokills`, `win`, `physicaldamage`, `magicdamage`, `truedamage`, `gold`)
                                                 VALUES ('$version','$matchid','$playerid',$team,$championid,'$role','$typerole',$kills,$assists,$deaths,$solokills,$win,$physical,$magic,$true,$gold)");
                            $insert->execute();
                        }
                        $pnumber += 1;
                    }

                    $game[$number]['teams'] = $played[$number]['full']->info->teams;
                    foreach ($game[$number]['teams'] as $team) {
                        $teammember = 0;
                        $teamid = $team->teamId;
                        $select = $conn->query("SELECT * FROM `leagueban` WHERE `matchid`='$match' AND `team`=$teamid")->fetch();
                        if (empty($select)) {
                            $conn->prepare("INSERT INTO `leagueban`(`matchid`, `team`) VALUES ('$match',$teamid)")->execute();
                            foreach ($team->bans as $ban) {
                                $member = $teammember + 1;
                                $typeplayer = 'player' . $member;
                                $teamplayerban = $ban->championId;
                                $conn->prepare("UPDATE leagueban SET `$typeplayer`=$teamplayerban WHERE `matchid`='$match' AND `team`=$teamid")->execute();

                                $teammember++;
                            }
                        }
                        $select = $conn->query("SELECT * FROM `leaguegame` WHERE `matchid`='$match' AND `team`=$teamid")->fetch();
                        if (empty($select)) {
                            $teambaronkills = $team->objectives->baron->kills;
                            $teamchampionkills = $team->objectives->champion->kills;
                            $teamobjectivekills = $team->objectives->dragon->kills;
                            $teamtowerkills = $team->objectives->tower->kills;
                            $teamriftkills = $team->objectives->riftHerald->kills;

                            $checkwin = $team->win;
                            if (!empty($checkwin)) {
                                $win = 1;
                            } else {
                                $win = 0;
                            }

                            $insert = $conn->prepare("INSERT INTO `leaguegame`(`matchid`, `team`, `win`, `baronkills`, `dragonkills`, `championkills`, `riftheraldkills`, `towerkills`) VALUES ('$match',$teamid,$win,$teambaronkills,$teamobjectivekills,$teamchampionkills,$teamriftkills,$teamtowerkills)")->execute();
                        }
                    }
                }
                $number++;
            }
        }
    }
}
/**
 * Get all your mastery without a problem to a database
 * @param string $encSumid "Required to load all your mastery points, returns an error if not an ENCRYPTED userid (id for me in this case)"
 * @return "All data is inserted in to the database!"
 */
function insertMasteryChampions(string $encSumid)
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }
    if (empty($encSumid)) return;
    $region = 'euw1';
    $apikey = "?api_key=" . $_ENV['RIOT_API_KEY'];
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/$encSumid" . $apikey, false, $context));
    $code = getHttpCode($http_response_header);
    $data['code'] = $code;
    if ($data['code'] == 200) {
        foreach ($response as $champion) {
            // return $champion;
            $championid = $champion->championId;
            $championlevel = $champion->championLevel;
            $championpoints = $champion->championPoints;
            $select = $conn->query("SELECT * FROM leaguemastery WHERE `championid`=$championid AND `encuserid`='$encSumid'")->fetch();
            if (empty($select)) {
                $insert = $conn->prepare("INSERT INTO `leaguemastery`(`encuserid`, `championid`, `championlevel`, `points`) VALUES ('$encSumid',$championid,$championlevel,$championpoints)");
                $insert->execute();
            } else {
                $update = $conn->prepare("UPDATE `leaguemastery` SET `championlevel`=:championlevel,`points`=:points WHERE `championid`=:championid AND `encuserid`=:userid");
                $update->bindParam(":userid", $encSumid, PDO::PARAM_STR);
                $update->bindParam(":championid", $championid, PDO::PARAM_INT);
                $update->bindParam(":championlevel", $championlevel, PDO::PARAM_INT);
                $update->bindParam(":points", $championpoints, PDO::PARAM_INT);
                $update->execute();
            }
        }
    }
}

/**
 * Function Get League User.
 * The ability to get the user out of the database of what they have right now.
 * @param string $username 'Use a username that you want to find, returns a error if empty'
 * @return 'You got yourself a user! Returns a error if not avalable'
 */
function getLeagueUser(string $username) # Finished, Select user from Database.
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }
    if (empty($username)) $username = $_ENV['LEAGUEUSER'];

    $data = [];

    $user = $conn->query("SELECT * FROM leagueprofile WHERE `name`='$username' LIMIT 1")->fetch();
    if (empty($user)) {
        $check = InsertLeagueUsername($username);
        if ($check['code'] = 200) {
            $user = $conn->query("SELECT * FROM leagueprofile WHERE `name`='$username' LIMIT 1")->fetch();
            $data['id'] = $user['leagueId'];
            $data['accountid'] = $user['accountId'];
            $data['profileiconid'] = $user['profileIconId'];
            $data['name'] = $user['name'];
            $data['puuid'] = $user['puuId'];
            $data['summonerlevel'] = $user['summonerLevel'];
        }

        if ($check['code'] = 404) {
            $data['code'] = 404;
            $data['type'] = "NOUSR";
        }
    } else {
        $data['id'] = $user['leagueId'];
        $data['accountid'] = $user['accountId'];
        $data['profileiconid'] = $user['profileIconId'];
        $data['name'] = $user['name'];
        $data['puuid'] = $user['puuId'];
        $data['summonerlevel'] = $user['summonerLevel'];
    }
    return $data;
}

/**
 * Function Get League User with Puuid.
 * The ability to get the user out of the database of what they have right now. if they are not known, create them;
 * @param string $puuid 'Use a puuid to get yourself started, returns an error if it is not an puuid or nonexistant.'
 * @return 'You got yourself a user! Returns an error if not avalable.'
 */
function getLeaguePuuid(string $puuid) # Finished, Select user from Database otherwise, create
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    $data = [];

    $user = $conn->query("SELECT * FROM leagueprofile WHERE `puuid`='$puuid' LIMIT 1")->fetch();
    if (empty($user)) {
        $check = InsertLeaguePuuId($puuid);

        if ($check['code'] = 200) {
            $user = $conn->query("SELECT * FROM leagueprofile WHERE `puuid`='$puuid' LIMIT 1")->fetch();
            $data['id'] = $user['leagueId'];
            $data['accountid'] = $user['accountId'];
            $data['profileiconid'] = $user['profileIconId'];
            $data['name'] = $user['name'];
            $data['puuid'] = $user['puuId'];
            $data['summonerlevel'] = $user['summonerLevel'];
        }

        if ($check['code'] = 404) {
            $data['code'] = 404;
            $data['type'] = "NOUSR";
        }
    } else {
        $data['id'] = $user['leagueId'];
        $data['accountid'] = $user['accountId'];
        $data['profileiconid'] = $user['profileIconId'];
        $data['name'] = $user['name'];
        $data['puuid'] = $user['puuId'];
        $data['summonerlevel'] = $user['summonerLevel'];
    }
    return $data;
}

/**
 * Get all the mastery of the champion you want or just all.
 * @param string $encSumid 'Get the encSumid to get started. Returns an error if it isn't. (use GetLeagueUser to get the encuserid)'
 * @return 'Return all champions you've played!'
 */
function getMasteryChampions(string $encSumid)
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    if (empty($encSumid)) return;
    $data = [];
    $number = 1;
    $champion = selectAllChampions();

    $select = $conn->query("SELECT championid, championlevel, points FROM `leaguemastery` WHERE `encuserid` = '$encSumid' ORDER BY `points` DESC")->fetchAll();
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    foreach ($select as $item) {
        $data[$item['championid']]['champion'] = $champion[$item['championid']];
        $data[$item['championid']]['mastery'] = $item['championlevel'];
        $data[$item['championid']]['points'] = $item['points'];
    }
    return $data;
}

// Select all the champions that League has and future champions as well!
function selectAllChampions()
{
    $versions = json_decode(file_get_contents("https://ddragon.leagueoflegends.com/api/versions.json"));
    $version = $versions[0];
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);

    $championsjson = json_decode(file_get_contents("http://ddragon.leagueoflegends.com/cdn/$version/data/en_US/champion.json", false, $context));
    $code = getHttpCode($http_response_header);
    $data['code'] = $code;
    foreach ($championsjson->data as $item) {
        $champion[$item->key]['id'] = $item->id;
        $champion[$item->key]['name'] = $item->name;
        $champion[$item->key]['title'] = $item->title;
        $champion[$item->key]['blurb'] = $item->blurb;
        $champion[$item->key]['images']['full'] = $item->image->full;
        $champion[$item->key]['images']['sprite'] = $item->image->sprite;
    }
    return $champion;
}

/**
 * Get all the mastery of the champion you want or just all.
 * @param string $encSumid 'Get the encSumid to get started. Returns an error if it isn't. (use GetLeagueUser to get the encuserid)'
 * @param int $championid 'Gets the mastery of a certain champion'
 * @return 'Return a champion to look at!'
 */
function getMasteryChampion(string $encSumid, int $championid)
{
    try {
        $conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error['code'] = 404;
        $error['type'] = "UNKWN_USR";
        $error['msg'] = $e->getMessage();
    }

    if (empty($encSumid)) return;
    if (empty($championid)) return;
    $data = [];
    $number = 1;
    $champion = selectAllChampions();

    $select = $conn->query("SELECT championid, championlevel, points FROM `leaguemastery` WHERE `encuserid` = '$encSumid' AND `championid` = $championid ORDER BY `points` DESC")->fetch();
    $data[$select['championid']]['champion'] = $champion[$select['championid']];
    $data[$select['championid']]['mastery'] = $select['championlevel'];
    $data[$select['championid']]['points'] = $select['points'];
    return $data;
}

function getRankUser($encSumid)
{
    $region = 'euw1';
    if (empty($encSumid)) return;

    $apikey = "?api_key=" . $_ENV['RIOT_API_KEY'];
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/league/v4/entries/by-summoner/$encSumid" . $apikey));
    return $response;
}
