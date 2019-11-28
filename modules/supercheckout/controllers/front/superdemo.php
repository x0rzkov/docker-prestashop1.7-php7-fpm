<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

require_once _PS_MODULE_DIR_ . 'supercheckout/classes/free_order.php';

class SupercheckoutSuperdemoModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function init()
    {
        parent::init();
    }
    
    public function postProcess()
    {
        if (Tools::getValue('action') =='view') {
            $type = Tools::getValue('type');
            if (!empty($type) && $type) {
                $settings = array();
                $supercheckout_config = new SupercheckoutConfiguration();
                if (!Configuration::get('VELOCITY_SUPERCHECKOUT') || Configuration::get('VELOCITY_SUPERCHECKOUT') == '') {
                    $settings = $supercheckout_config->getDefaultSettings();
                } else {
                    $settings = Tools::unserialize(Configuration::get('VELOCITY_SUPERCHECKOUT'));
                }
                if (!empty($settings) && isset($settings['layout'])) {
                    $settings['layout'] = $type;
                    
                    if (!$this->context->cart->id) {
                        if (Context::getContext()->cookie->id_guest) {
                            $guest = new Guest(Context::getContext()->cookie->id_guest);
                            $this->context->cart->mobile_theme = $guest->mobile_theme;
                        }
                        $this->context->cart->add();
                        if ($this->context->cart->id) {
                            $this->context->cookie->id_cart = (int) $this->context->cart->id;
                        }
                    }
                    
                    $products = $this->context->cart->getProducts(true);
                    if (empty($products)) {
                        $updQTy = $this->context->cart->updateQty(1, 1);
                        if ($updQTy < 0) {
                            Tools::redirect($this->context->link->getPageLink('index'));
                        } elseif (!$updQTy) {
                            Tools::redirect($this->context->link->getPageLink('index'));
                        }
                    }
                    $this->context->cookie->__set('kb_supercheckout_demo', $type);
                    Tools::redirect($this->context->link->getModuleLink($this->module->name, 'supercheckout'));
                }
            } else {
                Tools::redirect($this->context->link->getPageLink('index'));
            }
        }
        parent::postProcess();
    }
}
