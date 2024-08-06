<?php

namespace Websmid\Taggrs\Tracking\Factory;

use Websmid\Taggrs\Tracking\Model\DataLayerModel;

class ModelFactory{

	public function createModelByData(array $rawData, $baseModel = null, array $extraParams = [])
	{
		$model = ( is_null($baseModel) ) ? (new DataLayerModel()) : $baseModel;
		if( array_key_exists('name', $extraParams) ){
			$model->setName($extraParams['name']);
		}

		$model->setEcommerceBody($rawData);
		
		return $model;
	}
}