<?php

namespace Websmid\Taggrs\Tracking\DataRetrievers;

use Context;
use Product;
use Category;

class ListingDataRetriever implements DataRetrieverInterface{

	public function getBaseData()
	{
		$context = Context::getContext();
		if( !array_key_exists('listing', $context->smarty->tpl_vars) ){
			return false;
		}

		$baseListing = $context->smarty->tpl_vars['listing'];
		$renderedProductList = $baseListing->value['products'];

		$rawData = [
			'item_list_id' => self::getListId($context->controller),
			'item_list_name' => self::getListName($context->controller),
			'items' => []
		];
		$idLang = $context->language->id;

		foreach ($renderedProductList as $key => $product) {

			$newProduct = [
				'item_name' => $product->name,
				'item_id' => $product->id,
				'price' => round((float)$product->price_amount, 2),
				'item_brand' => $product->manufacturer_name,
				'index' => ($key + 1),
			];

			foreach((new Product($product->id))->getCategories() as $key => $assocCat){

				$keyName = ( $key == 0 ) ? 'item_category' : 'item_category' . $key;
				$newProduct[$keyName] = (new Category((int)$assocCat))->getName($idLang);
			}

			$rawData['items'][] = $newProduct;
		}

		return $rawData;
	}

	private function getListId($controller)
	{
		if( $controller->php_self == 'category' ){
			$cat = Context::getContext()->smarty->tpl_vars['category'];
			return $cat->value['id'];
		}

		return 0;
	}

	private function getListName($controller)
	{
		if( $controller->php_self == 'category' ){
			$cat = Context::getContext()->smarty->tpl_vars['category'];
			return $cat->value['name'];
		}  elseif( $controller->php_self == 'search' ){
			return 'search';
		}
	}
}