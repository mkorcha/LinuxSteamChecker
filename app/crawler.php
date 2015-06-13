<?php

require "vendor/autoload.php";
require "config.php";

use Sunra\PhpSimple\HtmlDomParser;

$catalog = "http://store.steampowered.com/search/results?sort_by=_ASC&os=linux&page=";

$redis = new Predis\Client();

$dom = HtmlDomParser::file_get_html($catalog . "1");

$end = $dom->find("a", -2)->getAttribute("href");
$end = substr($end, strpos($end, "page=") + 5);

store_ids($dom, $redis);

for($i = 2; $i <= $end; $i++) {
	store_ids(HtmlDomParser::file_get_html($catalog . $i), $redis);
}

function store_ids($dom, $redis) {
	foreach($dom->find("a") as $link) {
		$id = $link->getAttribute("data-ds-appid");

		if(!empty($id)) {
			if(!strstr($id, ",")) {
				$redis->sadd(REDIS_KEY, $id);

				continue;
			}

			$redis->sadd(REDIS_KEY, explode($id, ","));
		}
	}
}
