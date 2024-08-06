<?php

namespace Websmid\Taggrs\Tracking\DataRetrievers;

use Context;
use Product;
use Category;

class ProductDataRetriever implements DataRetrieverInterface{

	public function getBaseData()
	{
		$context = Context::getContext();
		if( !array_key_exists('product', $context->smarty->tpl_vars) ){
			return false;
		}

		$baseProduct = $context->smarty->tpl_vars['product']->value;
		$currency = $context->smarty->tpl_vars['currency']->value;

		$rawData = [
			'currency' => $currency['iso_code'],
			'value' => (float)round($baseProduct->price_amount, 2),
			'items' => [
				'item_id' => $baseProduct->id,
				'item_name' => $baseProduct->name,
				'price' => (float)round($baseProduct->price_amount, 2),
				'item_category' => $baseProduct->category_name,
				'item_brand' => $baseProduct->manufacturer_name,
			]
		];

		return $rawData;
	}
}