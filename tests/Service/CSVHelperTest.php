<?php

namespace App\Tests\Service;

use App\Services\CSVHelper;
use \DateTime;
use App\Entity\CustomerAddress;
use App\Entity\Customer;
use App\Entity\Order;

use PHPUnit\Framework\TestCase;

class CSVHelperTest extends TestCase
{
    public function testGenerateExportableOrderSuccess(): void {
		$customer = new Customer();
		$address = new CustomerAddress();
		$order = new Order();
		$address -> setState('VICTORIA');
		$customer -> setShippingAddress($address);
		$order -> setOrderId(123);
		$order -> setDateISO(new Datetime());
		$order -> setTotalValue(600.0);
		$order -> setAveragePrice(50.0);
		$order -> setDistinctUnit(5);
		$order -> setCustomer($customer);
    	$csvHelper = new CSVHelper();
    	$arraySample = array($order);
    	$expectedArrayCount = 2;
    	
    	$actualExportableArray = $csvHelper -> generateExportableOrder($arraySample);

    	$this->assertEquals($expectedArrayCount, count($actualExportableArray), 'number of rows mismatch the expected (2)');
    }

     public function testGenerateExportableOrderSuccessExclude0TotalValue(): void {
		$customer = new Customer();
		$address = new CustomerAddress();
		$order = new Order();
		$address -> setState('VICTORIA');
		$customer -> setShippingAddress($address);
		$order -> setOrderId(123);
		$order -> setDateISO(new Datetime());
		$order -> setTotalValue(0.0);
		$order -> setAveragePrice(50.0);
		$order -> setDistinctUnit(5);
		$order -> setCustomer($customer);
    	$csvHelper = new CSVHelper();
    	$arraySample = array($order);
    	$expectedArrayCount = 1;
    	
    	$actualExportableArray = $csvHelper -> generateExportableOrder($arraySample);

    	$this->assertEquals($expectedArrayCount, count($actualExportableArray), 'number of rows mismatch the expected (1)');
    }
}
