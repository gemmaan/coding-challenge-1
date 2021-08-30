<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Services\JSONLinesReader;

class JSONLinesReaderTest extends TestCase
{
    public function testReadJSONLinesSuccess(): void {
		$url = 'sample.jsonl';
		$jsonLinesReader = new JSONLinesReader();
		$expectedOrderId = 1001;
    	
    	$actualResult = $jsonLinesReader -> readJSONLines($url);

    	$this->assertEquals($expectedOrderId, $actualResult[0]['order_id'], 'order id mismatch');
    }
}
