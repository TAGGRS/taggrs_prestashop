<?php

namespace Websmid\Taggrs\Tracking\Presenter;

use Websmid\Taggrs\Tracking\Model\DataLayerModel;
use Module;
use Context;

abstract class Presenter{

	private $templateFile = '';

	public function setTemplateFile(string $fileName)
	{
		$this->templateFile = $fileName;
	}

	abstract public function setBlockVariables(DataLayerModel $presentableBlock);

	public function setCustomVariables(array $templateVars)
	{
		return Context::getContext()->smarty->assign($templateVars);
	}

	public function present()
	{
		if( empty($moduleClass = Module::getInstanceByname('taggrs_prestashop')) ){
			return '';
		}

        return $moduleClass->display($moduleClass->getLocalPath(), $this->templateFile);
	}
}