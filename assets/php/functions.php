<?php
function getLeagueUser(string $username) {
    $region = 'euw1';
    if(empty($username)) $username = "Zerdardian";
    $apikey = "?api_key=".$_ENV['RIOT_API_KEY'];
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/summoner/v4/summoners/by-name/$username".$apikey));
    
    $data = [];
    $data['id'] = $response->id;
    $data['accountid'] = $response->accountId;
    $data['puuid'] = $response->puuid;
    $data['name'] = $response->name;
    $data['profileiconid'] = $response->profileIconId;
    $data['summonerlevel'] = $response->summonerLevel;
    return $data;
}

function getLeagueUserByPuuId($puuid) {
    $region = 'euw1';
    if(empty($username)) $username = "Zerdardian";
    $apikey = "?api_key=".$_ENV['RIOT_API_KEY'];
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid".$apikey));
    
    $data = [];
    $data['id'] = $response->id;
    $data['accountid'] = $response->accountId;
    $data['puuid'] = $response->puuid;
    $data['name'] = $response->name;
    $data['profileiconid'] = $response->profileIconId;
    $data['summonerlevel'] = $response->summonerLevel;
    return $data;
}

function getMasteryChampions($encSumid) {
    $region = 'euw1';
    if(empty($encSumid)) return;

    $apikey = "?api_key=".$_ENV['RIOT_API_KEY'];
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/$encSumid".$apikey));
    return $response;
}

function getRankUser($encSumid) {
    $region = 'euw1';
    if(empty($encSumid)) return;

    $apikey = "?api_key=".$_ENV['RIOT_API_KEY'];
    $response = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/league/v4/entries/by-summoner/$encSumid".$apikey));
    return $response;
}

function getMatches($puuid, int $max) {
    $region = 'europe';
    $number = 0;

    if(empty($puuid)) return;
    if($max < 1) $max = 1;
    if($max > 20) $max = 20;

    $apikey = "api_key=".$_ENV['RIOT_API_KEY'];
    $matches = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/match/v5/matches/by-puuid/$puuid/ids?start=0&count=$max&".$apikey));
    foreach($matches as $playedmatch) {
        $pnumber = 0;
        $match[$number]['full'] = json_decode(file_get_contents("https://$region.api.riotgames.com/lol/match/v5/matches/$playedmatch?".$apikey));
        foreach($match[$number]['full']->metadata->participants as $user) {
            $player = getLeagueUserByPuuId($user);
            $game[$number]['users'][$pnumber]['info'] = $player;
            $game[$number]['users'][$pnumber]['stats'] = $match[$number]['full']->info->participants[$pnumber];
            $pnumber += 1;
        }
        $game[$number]['teams'] = $match[$number]['full']->info->teams;
        $number++;
    }
    return $game;
}