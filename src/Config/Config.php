<?php

namespace Websmid\Taggrs\Config;

class Config
{	
	const MOD_HOOKS = [
		'header',
		'displayHeader',
		'displayAfterBodyOpeningTag',
		'displayBeforeBodyClosingTag',
		'actionFrontControllerSetMedia'
	];

	const GTM_CODE = 'TG_GTM_CODE';

	const GTM_URL = 'TG_GTM_URL';

	const ADMIN_FORM_FIELDS = [
        self::GTM_CODE => [
        	'type' => 'text',
        	'label' => 'GTM CODE',
        	'name' => self::GTM_CODE,
        ],
        self::GTM_URL => [
        	'type' => 'text',
        	'label' => 'TAGGRS URL',
        	'name' => self::GTM_URL,
        ]
    ];
}