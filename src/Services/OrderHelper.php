<?php

namespace App\Services;

use App\Entity\CustomerAddress;
use App\Entity\Customer;
use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Discount;
use \DateTime;

class OrderHelper {
	public function generateOrders(array $ordersArray) {
		$orderObjectArray = array();
        foreach ($ordersArray as $orderItem) {
            $orderId = $orderItem['order_id'];

            $orderDate = $orderItem['order_date'];
            $orderDateISO = new DateTime($orderDate);
            $orderDateISO -> format(DateTime::ATOM);

            $customerId = $orderItem['customer']['customer_id'];
            $customerFirstName = $orderItem['customer']['first_name'];
            $customerLastName = $orderItem['customer']['last_name'];
            $customerEmail = $orderItem['customer']['email'];
            $customerPhone = $orderItem['customer']['phone'];
            $addressStreet = $orderItem['customer']['shipping_address']['street'];
            $addressPostcode = $orderItem['customer']['shipping_address']['postcode'];
            $addressSuburb = $orderItem['customer']['shipping_address']['suburb'];
            $addressState = $orderItem['customer']['shipping_address']['state'];

            $order = new Order();
            $order -> setOrderId($orderId);
            $order -> setDateISO($orderDateISO);

            $customerAddress = new CustomerAddress();
            $customerAddress -> setStreet($addressStreet);
            $customerAddress -> setPostcode($addressPostcode);
            $customerAddress -> setSuburb($addressSuburb);
            $customerAddress -> setState($addressState);

            $customer = new Customer();
            $customer -> setCustomerId($customerId);
            $customer -> setFirstName($customerFirstName);
            $customer -> setLastName($customerLastName);
            $customer -> setEmail($customerEmail);
            $customer -> setPhone($customerPhone);
            $customer -> setShippingAddress($customerAddress);

            $order -> setCustomer($customer);

            $items = $orderItem['items'];
            $itemsObjectArray = array();
            foreach ($items as $item) {
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $productId = $item['product']['product_id'];
                $productTitle = $item['product']['title'];
                $productSubtitle = $item['product']['subtitle'];
                $productImage = $item['product']['image'];
                $productCategory = $item['product']['category'];
                $productUrl = $item['product']['url'];
                $productGtin14 = $item['product']['gtin14'];
                $productCreatedAt = $item['product']['created_at'];
                $productBrandId = $item['product']['brand']['id'];
                $productBrandName = $item['product']['brand']['name'];

                $brand = new Brand();
                $brand -> setBrandId($productBrandId);
                $brand -> setName($productBrandName);

                $product = new Product();
                $product -> setProductId($productId);
                $product -> setTitle($productTitle);
                $product -> setSubtitle($productSubtitle);
                $product -> setImage($productImage);
                $product -> setCategory($productCategory);
                $product -> setUrl($productUrl);
                $product -> setGtin14($productGtin14);
                $product -> setCreatedAt($productCreatedAt);
                $product -> setBrand($brand);


                $item = new Item();
                $item -> setQuantity($quantity);
                $item -> setUnitPrice($unitPrice);
                $item -> setProduct($product);

                array_push($itemsObjectArray, $item);
            }

            $discountObjectArray = array();
            $discounts = $orderItem['discounts'];
            foreach ($discounts as $discount) {
            	$discountType = $discount['type'];
            	$discountValue = $discount['value'];
            	$discountPriority = $discount['priority'];

            	$discount = new Discount();
            	$discount -> setType($discountType);
            	$discount -> setValue($discountValue);
            	$discount -> setPriority($discountPriority);

            	array_push($discountObjectArray, $discount);
            }
            $shippingPrice = $orderItem['shipping_price'];

            $order -> setItems($itemsObjectArray);
            $order -> setDiscounts($discountObjectArray);
            $order -> setShippingPrice($shippingPrice);
            $totalValue = $order -> calculateTotalValue();
			$order -> setTotalValue($totalValue);

			$averagePrice = $order -> calculateAveragePrice();
			$order -> setAveragePrice($averagePrice);

			
			$distinctUnit = $order -> countDistinctUnit();
			$order -> setDistinctUnit($distinctUnit);

			$totalUnit = $order -> calculateTotalUnit();
			$order -> setTotalUnit($totalUnit);

			if($totalValue!=0) {
            	array_push($orderObjectArray, $order);
			}
        }

        return $orderObjectArray;
	}
}