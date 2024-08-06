<?php

namespace Websmid\Taggrs\SimpleForm;

use HelperForm;
use Configuration;
use Context;
use Tools;
use AdminController;

class FormController
{	
	public $fields = [];

	public $module = '';

	public function __construct(
		$fields,
		$module
	){
		$this->fields = $fields;
		$this->module = $module;
	}

	public function checkSaveAction()
	{
		if( Tools::getValue('submitWSForm') ){
			foreach ($this->fields as $field) {
				( !is_array($field) ) ? Configuration::updateValue($field, pSQL(Tools::getValue($field))) : Configuration::updateValue($field['name'], Tools::getValue($field['name']));
	       	}
		}
	}

	public function generateFormWithFields()
	{
		// Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fieldsForm = [];

        // Base form info
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => Context::getContext()->getTranslator()->trans('Instellingen', [], 'Modules.Ws_Taggrs.Admin'),
            ],
            'input' => [],
            'submit' => [
                'title' => Context::getContext()->getTranslator()->trans('Opslaan', [], 'Modules.Ws_Taggrs.Admin'),
                'class' => 'btn btn-default pull-right'
            ]
       	];	

       	// Add fields
       	foreach ($this->fields as $field) {
       		if( !is_array($field) ){
       			$fieldsForm[0]['form']['input'][] = [
       				'type' => 'text',
                    'label' => $field,
                    'name' => $field,
                    'size' => 20,
                    'required' => true
       			];
       		} else{

       			$fieldsForm[0]['form']['input'][] = [
       				'type' => $field['type'],
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'size' => 20,
                    'required' => true,
       			];
       		}
       	}

        $helper = new HelperForm();

        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->module->name;

        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        $helper->title = $this->module->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submitWSForm';
        $helper->toolbar_btn = [
            'save' => [
                'desc' => Context::getContext()->getTranslator()->trans('Opslaan', [], 'Modules.Ws_Taggrs.Admin'),
                'href' => AdminController::$currentIndex.'&configure='.$this->module->name.'&save'.$this->module->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => Context::getContext()->getTranslator()->trans('Terug', [], 'Modules.Ws_Taggrs.Admin')
            ]
        ];

        // Load current value
        foreach ($this->fields as $field) {
        	if( !is_array($field) ){
        		$helper->fields_value[$field] = Configuration::get($field);
        	} else{
        		$helper->fields_value[$field['name']] = Configuration::get($field['name']);
        	}
        }

        return $helper->generateForm($fieldsForm);
	}	
}