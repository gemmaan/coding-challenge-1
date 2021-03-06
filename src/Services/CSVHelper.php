<?php

namespace App\Services;

class CSVHelper
{
	public function generateExportableOrder(array $orderArray)
	{
		$exportableArray = array(['order_id', 'order_datetime', 'total_order_value', 'average_unit_price', 'distinct_unit_count', 'total_units_count', 'customer_state']);

		foreach ($orderArray as $order) {
			$orderId = $order->getOrderId();
			$orderDatetime = $order->getDateISO()->format('Y-m-d H:i:s');
			$totalValue = $order->getTotalValue();
			$averagePrice = $order->getAveragePrice();
			$totalUnit = $order->getTotalUnit();
			$distinctUnit = $order->getDistinctUnit();
			$customerState = $order->getCustomer()->getShippingAddress()->getState();

			if ($totalValue != 0) {
				array_push($exportableArray, [$orderId, $orderDatetime, $totalValue, $averagePrice, $distinctUnit, $totalUnit, $customerState]);
			}
		}

		return $exportableArray;
	}
}
