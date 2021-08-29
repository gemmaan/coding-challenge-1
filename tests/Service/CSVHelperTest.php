<?php

namespace App\Tests\Service;

use App\Services\CSVHelper;
use \DateTime;

use PHPUnit\Framework\TestCase;

class CSVHelperTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    // public function testGenerateExportableOrderSuccess(): void {
    // 	$csvHelper = new CSVHelper();
    // 	$arraySample = array([123,new DateTime(), 600.0,50.0,5,'VICTORIA']);
    	
    // 	$csvHelper -> generateExportableOrder($arraySample);
    // 	$this->assertTrue(true);
    // }
}
