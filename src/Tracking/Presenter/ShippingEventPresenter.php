<?php

namespace Websmid\Taggrs\Tracking\Presenter;

use Websmid\Taggrs\Tracking\Model\DataLayerModel;
use Context;

class ShippingEventPresenter extends Presenter{

	private $templateFile = '';

	public function setBlockVariables(DataLayerModel $presentableBlock)
	{
		$currVarPrecision = ini_get('precision');
		ini_set('serialize_precision','-1');

		Context::getContext()->smarty->assign([
            'event_name' => $presentableBlock->getName(),
            'encode_event' => json_encode($presentableBlock->getArrayObject()),
            'carrier_details' => json_encode($this->getCarrierDetailsByCurrentOptions())
        ]);

		ini_set('serialize_precision', $currVarPrecision);
	}

	public function getCarrierDetailsByCurrentOptions()
	{
		$carrierRawList = Context::getContext()->cart->getDeliveryOptionList();
		if( empty($carrierRawList) ){
			return [];
		}
		$carrierRawList = current($carrierRawList);

		$formattedList = [];
		foreach ($carrierRawList as $carrierCombinationkey => $carrierCombination) {
			$carrierCombinationPriceTotal = array_sum(array_column($carrierCombination['carrier_list'], 'price_with_tax'));
			$carrierCombName = implode("|", array_column(array_column($carrierCombination['carrier_list'], 'instance'), 'name'));

			$formattedList[$carrierCombinationkey] = [
				'optionExtraValue' => $carrierCombinationPriceTotal,
				'optionEventName' => $carrierCombName,
			];
		}

		return $formattedList;
	}
}