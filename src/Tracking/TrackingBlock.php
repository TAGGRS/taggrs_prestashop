<?php

namespace Websmid\Taggrs\Tracking;

use Websmid\Taggrs\Tracking\Models\DataLayer;
use Websmid\Taggrs\Tracking\Factory\ModelFactory;
use Websmid\Taggrs\Tracking\Factory\PresenterFactory;
use Websmid\Taggrs\Tracking\DataRetrievers\ListingDataRetriever;
use Websmid\Taggrs\Tracking\DataRetrievers\ProductDataRetriever;
use Websmid\Taggrs\Tracking\DataRetrievers\CheckoutDataRetriever;
use Websmid\Taggrs\Tracking\DataRetrievers\OrderConfDataRetriever;
use OrderController;
use CheckoutPersonalInformationStep;
use CheckoutDeliveryStep;
use CheckoutPaymentStep;
use Context;

class TrackingBlock{

	private $presentableObjects = [];

	public $rawData = [];

	public $output;

	public function getAndRenderBlock()
	{
		$this->getDataByController();

		$this->getBlocksByController();

		$this->presentBlocks();

		return $this->output;
	}

	public function getDataByController()
	{
		$controller = Context::getContext()->controller;
		$phpSelf = $controller->php_self;

		if( in_array($phpSelf, ['category']) ){
			$this->rawData['view_item_list'] = (new ListingDataRetriever())->getBaseData();
		}

		if( in_array($phpSelf, ['product']) ){
			$this->rawData['view_item'] = (new ProductDataRetriever())->getBaseData();
		}

		if( in_array($phpSelf, ['cart']) ){
			$this->rawData['view_cart'] = (new CheckoutDataRetriever())->getBaseData();
		}

		if( in_array($phpSelf, ['order']) ){

			if( $controller instanceof OrderController ){

				$checkoutProcess = $controller->getCheckoutProcess();
				foreach($checkoutProcess->getSteps() as $step){

					if( $step instanceof CheckoutPersonalInformationStep && $step->isCurrent() ){
						$this->rawData['begin_checkout'] = (new CheckoutDataRetriever())->getBaseData();
					} elseif ( $step instanceof CheckoutDeliveryStep && $step->isCurrent() ) {
						$this->rawData['add_shipping_info'] = (new CheckoutDataRetriever())->getBaseData();
					} elseif ( $step instanceof CheckoutPaymentStep && $step->isCurrent() ) {
						$this->rawData['add_payment_info'] = (new CheckoutDataRetriever())->getBaseData();
					}
				}
			}
		}

		if( in_array($phpSelf, ['order-confirmation']) ){
			$this->rawData['purchase'] = (new OrderConfDataRetriever())->getBaseData();
		}

	}

	public function getBlocksByController()
	{
		$modelFactory = new ModelFactory();
		foreach($this->rawData as $key => $rawDataBlock){
			$this->presentableObjects[$key] = $modelFactory->createModelByData($this->rawData[$key], null, ['name' => $key]);
		}
	}

	public function presentBlocks()
	{
		$presenterFactory = new PresenterFactory();

		foreach ($this->presentableObjects as $presentableBlock) {
			$blockPresenter = $presenterFactory->getPresenterByEventName($presentableBlock->getName());
			$blockPresenter->setBlockVariables($presentableBlock);
			$this->output .= $blockPresenter->present();
		}
	}
}