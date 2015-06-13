<?php

require "../app/vendor/autoload.php";
require "../app/config.php";

$redis = new Predis\Client();

$url = "http://steamcommunity.com/id/mikethepwnstar";

$key = REDIS_KEY .'_'. urlencode($url);

if(!$redis->smembers($key) || !$redis->get($key . "_count")) {
	$url = rtrim($url, '/');
	$url = substr($url, strrpos($url, '/') + 1);
	$url = "http://steamid.co/ajax/steamid.php?ddd=" . $url;
	
	$user = json_decode(geturl($url))->steamID64;

	$games = json_decode(geturl("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=". STEAM_API_KEY ."&steamid=". $user ."&format=json"));

	$owned = array();

	foreach($games->response->games as $game)
		$owned[] = $game->appid;

	$redis->set($key . "_count", $games->response->game_count);
	$redis->expire($key . "_count", 60 * 60 * 12);

	$redis->sadd($key, $owned);
	$redis->expire($key, 60 * 60 * 12);
}

$games = $redis->sinter(REDIS_KEY, $key);
$count = $redis->get($key . "_count");

echo json_encode(array(
	"count"      => count($games),
	"percentage" => round((count($games) / $count) * 100)
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
