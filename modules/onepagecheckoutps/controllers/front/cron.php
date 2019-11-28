<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2019 PresTeamShop
 * @license   see file: LICENSE.txt
 * @category  PrestaShop
 * @category  Module
 */

class OnePageCheckoutPSCronModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $this->display_column_left  = false;
        $this->display_column_right = false;
        $this->display_header       = false;
        $this->display_footer       = false;
        
        if (!$this->module->core->isModuleActive($this->module->name)
            || !$this->module->core->isVisible()
            || !$this->module->core->checkModulePTS()
        ) {
            return false;
        }

        if (!Tools::isSubmit('token')
            || Tools::encrypt($this->module->name.'/index') != Tools::getValue('token')
            || !Module::isInstalled($this->module->name)
        ) {
            die('Bad token');
        }
        
        parent::initContent();
        
        $result = $this->module->deleteEmptyAddressesOPC();

        if (isset($result['message'])) {
            die($result['message']);
        }
    }
}
