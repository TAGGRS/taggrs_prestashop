<?php

namespace Websmid\Taggrs\Tracking\DataRetrievers;

use Context;
use Product;
use Category;

class CheckoutDataRetriever implements DataRetrieverInterface{

	public function getBaseData()
	{
		$context = Context::getContext();
		$baseCart = $context->smarty->tpl_vars['cart']->value;
		$currency = $context->smarty->tpl_vars['currency']->value;
		$renderedProductList = $baseCart['products'];
		$idLang = $context->language->id;

		$rawData = [
			'currency' => $currency['iso_code'],
			'value' => $baseCart['totals']['total_including_tax']['amount'],
			'items' => [],
		];

		foreach ($renderedProductList as $key => $product) {

			$newProduct = [
				'item_id' => $product->id,
				'item_name' => $product->name,
				'price' => (string)round($product->price_amount, 2),
				'quantity' => $product->quantity
			];

			foreach((new Product($product->id))->getCategories() as $key => $assocCat){

				$keyName = ( $key == 0 ) ? 'item_category' : 'item_category' . $key;
				$newProduct[$keyName] = (new Category((int)$assocCat))->getName($idLang);
			}

			$rawData['items'][] = $newProduct;
		}

		if( array_key_exists('vouchers', $baseCart) ){

			if( !empty($baseCart['vouchers']['added']) ){
				$coupon = current($baseCart['vouchers']['added']);
				$rawData['coupon'] = $coupon['name'];
			}
		}

		return $rawData;
	}
}