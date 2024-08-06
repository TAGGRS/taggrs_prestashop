<?php

namespace Websmid\Taggrs\Module\Hooks;

use Websmid\Taggrs\Config\Config;
use Hook;

trait Install
{
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHooks()) {
            return false;
        }

        if (!$this->setCorrectPositions()) {
            return false;
        }

        return true;
    }

    public function setCorrectPositions()
    {
        $this->updatePosition(Hook::getIdByName('displayHeader'), false, 1);
        
        $this->updatePosition(Hook::getIdByName('header'), false, 1);

        return true;
    }

    public function registerHooks()
    {
        foreach (Config::MOD_HOOKS as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }

        return true;
    }
}