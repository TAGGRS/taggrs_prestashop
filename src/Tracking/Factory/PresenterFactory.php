<?php

namespace Websmid\Taggrs\Tracking\Factory;

use Websmid\Taggrs\Tracking\Presenter\DefaultPresenter;
use Websmid\Taggrs\Tracking\Presenter\ShippingEventPresenter;

class PresenterFactory{

	public function getPresenterByEventName(string $eventName)
	{
		$blockPresenter = new DefaultPresenter();

		switch ($eventName) {
			case 'add_shipping_info':

				$blockPresenter = new ShippingEventPresenter();
				$blockPresenter->setTemplateFile('add_shipping_info_event.tpl');
				break;

			case 'add_payment_info':
				$blockPresenter->setTemplateFile('add_payment_info_event.tpl');
				break;
			
			default:
				$blockPresenter->setTemplateFile('default_block.tpl');
				break;
		}
		
		return $blockPresenter;
	}
}