<?php

require "../app/vendor/autoload.php";

use Sunra\PhpSimple\HtmlDomParser;

define("BASE_URL", "http://store.steampowered.com/search/results?sort_by=_ASC&os=linux&page=");

$gameids = array();

$dom = HtmlDomParser::file_get_html(BASE_URL . "1");
$lastpage = $dom->find("a", -2)->getAttribute("href");

$last = substr($lastpage, strpos($lastpage, "page=") + 5);

$gameids = get_ids($dom);

for($i = 2; $i <= $last; $i++) {
	$gameids = array_merge($gameids, get_ids(HtmlDomParser::file_get_html(BASE_URL . $i)));
}

$owned = json_decode(file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=". API_KEY ."&steamid=76561198032788584&format=json"));

$count = 0; $linux = array();
foreach($owned->response->games as $game) {
	if(in_array($game->appid, $gameids)) {
		$count++;
		$linux[] = $game->appid;
	}
}

echo $count . " of your games run natively in Linux\n";
echo "This is ". round(($count / $owned->response->game_count) * 100) ." of your library!\n";

function get_ids($dom) {
	$ids = array();

	foreach($dom->find("a") as $link) {
		$id = $link->getAttribute("data-ds-appid");

		if(!empty($id)) {
			$ids[] = $id;
		}
	}

	return $ids;
}
