<?php

namespace Websmid\Taggrs\Module\Hooks\Front;

use Websmid\Taggrs\Tracking\TrackingBlock;
use Websmid\Taggrs\Tracking\Presenter\DefaultPresenter;
use Websmid\Taggrs\Config\Config;
use Media;
use Configuration;

trait FrontHooks
{

    public function hookActionFrontControllerSetMedia($params)
    {
        Media::addJsDef([
            'currCode' => $this->context->currency->iso_code
        ]);

        $this->context->controller->registerJavascript(
            'module-taggrs-prestashop-_dynamic_events',
            'modules/taggrs_prestashop/views/js/taggrs_dynamic_events.js',
            [
              'priority' => 200,
              'attributes' => 'async',
            ]
        );
    }

    public function hookDisplayHeader()
    {
        if( empty($gtm_code = Configuration::get(Config::GTM_CODE)) || empty($gtm_url = Configuration::get(Config::GTM_URL)) ){
            return '';
        }

        $presenter = new DefaultPresenter();
        $presenter->setTemplateFile('gtm_main.tpl');
        $presenter->setCustomVariables(['tg_gtm_code' => $gtm_code, 'tg_gtm_url' => $gtm_url]);

        return $presenter->present();
    }

    public function hookDisplayAfterBodyOpeningTag()
    {
        if( empty($gtm_code = Configuration::get(Config::GTM_CODE)) || empty($gtm_url = Configuration::get(Config::GTM_URL))  ){
            return '';
        }

        $presenter = new DefaultPresenter();
        $presenter->setTemplateFile('gtm_body.tpl');
        $presenter->setCustomVariables(['tg_gtm_code' => $gtm_code, 'tg_gtm_url' => $gtm_url]);

        return $presenter->present();
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        $dataLayer = new TrackingBlock();
        return $dataLayer->getAndRenderBlock();
    }
}