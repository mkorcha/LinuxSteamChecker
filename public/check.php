<?php

require "../app/vendor/autoload.php";
require "../app/config.php";

$redis = new Predis\Client();

$url = trim($_GET['url']);

$key = REDIS_KEY .'_'. urlencode($url);

if(!$redis->smembers($key) || !$redis->get($key . "_count")) {
	$url = rtrim($url, '/');
	$url = substr($url, strrpos($url, '/') + 1);

	if(is_numeric($url)) {
		$url = "http://steamid.co/ajax/steamid64.php?ddd=" . $url;
	}
	else {
		$url = "http://steamid.co/ajax/steamid.php?ddd=" . $url;
	}

	$user = json_decode(geturl($url))->steamID64;

	$games = json_decode(geturl("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . STEAM_API_KEY . "&steamid=" . $user . "&format=json&include_appinfo=1&include_played_free_games=1"));

	$owned = array();

	foreach($games->response->games as $game) {
		$appid = $game->appid;
		$owned[] = $appid;

		$redis->rpush(REDIS_KEY . "_game_" . $appid, $game->name);
		$redis->rpush(REDIS_KEY . "_game_" . $appid, $game->img_logo_url);
	}

	$redis->set($key . "_count", $games->response->game_count);
	$redis->expire($key . "_count", 60 * 60 * 12);

	$redis->sadd($key, $owned);
	$redis->expire($key, 60 * 60 * 12);
}

$games = $redis->sinter(REDIS_KEY, $key);
$count = $redis->get($key ."_count");

$info = array();

foreach($games as $game) {
	$lookup = $redis->lrange(REDIS_KEY . "_game_" . $game, 0, -1);

	$info[] = array(
		"id"   => $game,
		// arbitrary numbers to break long strings...yay?
		"name" => strpos($lookup[0], ' ') > 16 ? (substr($lookup[0], 0, 16) . ' ' . substr($lookup[0], 17)) : $lookup[0],
		"hash" => $lookup[1]
	);
}

echo json_encode(array(
	"count"      => count($games),
	"percentage" => round((count($games) / $count) * 100),
	"games"      => $info
));

function geturl($url) {
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
		CURLOPT_URL            => $url,
		CURLOPT_ENCODING       => "gzip",
		CURLOPT_RETURNTRANSFER => 1
	));

	return curl_exec($curl);
}
