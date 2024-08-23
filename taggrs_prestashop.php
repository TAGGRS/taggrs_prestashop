<?php
/**
 * 2007-2024 De Websmid BV
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author De Websmid BV <info@dewebsmid.nl>
 * @copyright  2007-2024 De Websmid BV
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of De Websmid BV
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use Websmid\Taggrs\Module\Hooks\Install;
use Websmid\Taggrs\Module\Hooks\Admin\AdminHooks;
use Websmid\Taggrs\Module\Hooks\Front\FrontHooks;

// Fix for frontend controllers
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class Taggrs_PrestaShop extends Module
{
    use Install;
    use AdminHooks;
    use FrontHooks;

	public function __construct()
    {
        $this->name = 'taggrs_prestashop';
        $this->version = '1.0.1';
        $this->author = 'Johan van der Klis | De Websmid BV';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Taggrs Connector');
        $this->description = $this->l('Vult datalayer voor Taggrs');
    }
}