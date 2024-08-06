<?php

namespace Websmid\Taggrs\Module\Hooks\Admin;

use Websmid\Taggrs\SimpleForm\FormController;
use Websmid\Taggrs\Config\Config;

trait AdminHooks
{
    public function getContent()
    {
        $form = new FormController(Config::ADMIN_FORM_FIELDS, $this);
        $form->checkSaveAction();
        return $form->generateFormWithFields();
    }
}