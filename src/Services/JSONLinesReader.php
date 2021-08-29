<?php

namespace App\Services;

use Rs\JsonLines\JsonLines;

class JSONLinesReader {
	public function readJSONLines(string $url) {
		$ordersJsonString = (new JsonLines())->delineFromFile($url);
        $ordersArray = json_decode($ordersJsonString, true);

        return $ordersArray;

	}
}