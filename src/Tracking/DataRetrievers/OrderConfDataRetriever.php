<?php

namespace Websmid\Taggrs\Tracking\DataRetrievers;

use Context;
use Order;
use Address;
use Product;
use Country;
use State;
use Category;

class OrderConfDataRetriever implements DataRetrieverInterface{

	public function getBaseData()
	{
		$context = Context::getContext();

		$baseOrder = new Order($context->controller->id_order);
		$orderLazy = $context->smarty->tpl_vars['order']->value;
		$baseAddress = new Address($baseOrder->id_address_delivery);
		$baseCountry = new Country($baseAddress->id_country);
		$currency = $context->smarty->tpl_vars['currency']->value;
		$idLang = $context->language->id;

		$totalTax = (float)$baseOrder->total_paid_tax_incl - (float)$baseOrder->total_paid_tax_excl;
		$orderCustomer = ( empty($context->smarty->tpl_vars['order_customer']) ) ? $context->smarty->tpl_vars['customer']->value : $context->smarty->tpl_vars['order_customer']->value;

    	$hashedEmail = hash('sha256', strtolower(trim($orderCustomer['email'])));
    	$hashedPhone = hash('sha256', strtolower(trim($baseAddress->phone)));

    	$fullStreet = $baseAddress->address1;
    	$fullStreet = ( empty($baseAddress->address2) ) ? $fullStreet : $fullStreet . ' ' . $baseAddress->address2;

    	$region = '';
    	if( !empty($baseAddress->id_state) ){
    		$region = (new State($baseAddress->id_state))->name[$baseOrder->id_lang];
    	}

		$rawData = [
			'currency' => $currency['iso_code'],
			'transaction_id' => $baseOrder->reference,
			'value' => (float)round((float)$baseOrder->total_paid, 2),
			'tax' => (float)round($totalTax, 2),
			'shipping' => (float)round($baseOrder->total_shipping_tax_incl, 2),
			'items' => [],
			'user_data' => [
				'first_name' => $orderCustomer['firstname'],
				'last_name' => $orderCustomer['lastname'],
				'email' => $orderCustomer['email'],
				'email_hashed' => $hashedEmail,
				'phone' => $baseAddress->phone,
				'phone_hashed' => $hashedPhone,
				'address' => [
					'street' => $fullStreet,
					'city' => $baseAddress->city,
					'region' => $region,
					'country' => $baseCountry->iso_code,
					'postal_code' => $baseAddress->postcode,
				]
			],
		];

		foreach ($orderLazy->getProducts() as $key => $product) {

			$newProduct = [
				'item_name' => $product['product_name'],
				'item_id' => $product['id_product'],
				'price' => (string)round($product['total_wt'], 2),
				'quantity' => $product['quantity'],
			];

			foreach((new Product($product['id_product']))->getCategories() as $key => $assocCat){

				$keyName = ( $key == 0 ) ? 'item_category' : 'item_category' . $key;
				$newProduct[$keyName] = (new Category((int)$assocCat))->getName($idLang);
			}

			$rawData['items'][] = $newProduct;
		}

		$cartRules = $baseOrder->getCartRules();
		if( !empty($cartRules) ){

			$cartRule = current($cartRules);
			$rawData['coupon'] = $cartRule['name'];
		}

		return $rawData;
	}
}
