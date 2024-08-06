<?php

namespace Websmid\Taggrs\Tracking\Presenter;

use Websmid\Taggrs\Tracking\Model\DataLayerModel;
use Context;

class DefaultPresenter extends Presenter{

	private $templateFile = '';

	public function setBlockVariables(DataLayerModel $presentableBlock)
	{
		$currVarPrecision = ini_get('precision');
		ini_set('serialize_precision','-1');

		Context::getContext()->smarty->assign([
            'event_name' => $presentableBlock->getName(),
            'encode_event' => json_encode($presentableBlock->getArrayObject())
        ]);

		ini_set('serialize_precision', $currVarPrecision);
	}
}