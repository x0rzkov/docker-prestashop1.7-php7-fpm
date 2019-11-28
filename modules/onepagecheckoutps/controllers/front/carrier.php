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

class OnePageCheckoutPSCarrierModuleFrontController extends ModuleFrontControllerCore
{
    public function init()
    {
        parent::init();

        $this->context->controller = new OrderController();
    }

    public function initContent()
    {
        parent::initContent();

        $this->module->loadCarrier($this->context->controller);

        $html = '<script>var orderOpcCarrier = "'.$this->context->link->getPageLink('order').'"</script>';
        $html .= '<div id="opc_payment_methods">';
        $html .= $this->context->smarty->fetch(_PS_THEME_DIR_.'templates/checkout/_partials/steps/shipping.tpl');
        $html .= '</div>';

        echo $html;
    }

    public function postProcess()
    {
        $this->context->smarty->assign('page_name', 'order');
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->addCSS($this->module->getPathUri().'views/css/front/carrier.css', 'all');
        $this->addCSS($this->module->getPathUri().'views/css/front/override.css', 'all');

        $this->addJS($this->module->getPathUri().'views/js/lib/pts/tools.js');
        $this->addJS($this->module->getPathUri().'views/js/front/carrier.js');
    }
}
