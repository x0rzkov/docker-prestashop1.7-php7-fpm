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

use PrestaShop\PrestaShop\Core\Foundation\Templating\RenderableProxy;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;

class OrderController extends OrderControllerCore
{
    public $checkoutDeliveryStep;

    public $opc;
    public $is_active_module;

    public function init()
    {
        $this->opc = Module::getInstanceByName('onepagecheckoutps');

        $this->opc->initBeforeControllerOPC($this);

        parent::init();

        if (Validate::isLoadedObject($this->opc)
            && $this->opc->core->isModuleActive($this->opc->name)
        ) {
            $this->is_active_module = true;
        } else {
            $this->is_active_module = false;
        }

        if (!$this->opc->core->checkModulePTS()) {
            $this->is_active_module = false;
        }

        if (!$this->opc->core->isVisible()) {
            $this->is_active_module = false;
        }

        if (!$this->is_active_module) {
            return;
        }

        $this->opc->initAfterControllerOPC($this);
    }

    public function initContent()
    {
        parent::initContent();

        if (!$this->is_active_module) {
            return;
        }

        $this->opc->initContentControllerOPC($this);
    }

    public function getCheckoutSession()
    {
        $deliveryOptionsFinder = new DeliveryOptionsFinder(
            $this->context,
            $this->getTranslator(),
            $this->objectPresenter,
            new PriceFormatter()
        );

        $session = new CheckoutSession(
            $this->context,
            $deliveryOptionsFinder
        );

        return $session;
    }

    protected function bootstrap()
    {
        $translator = $this->getTranslator();

        $session = $this->getCheckoutSession();

        $this->checkoutProcess = new CheckoutProcess(
            $this->context,
            $session
        );

        $this->checkoutDeliveryStep = new CheckoutDeliveryStep(
            $this->context,
            $translator
        );

        $this->checkoutDeliveryStep
            ->setRecyclablePackAllowed((bool) Configuration::get('PS_RECYCLABLE_PACK'))
            ->setGiftAllowed((bool) Configuration::get('PS_GIFT_WRAPPING'))
            ->setIncludeTaxes(
                !Product::getTaxCalculationMethod((int) $this->context->cart->id_customer)
                && (int) Configuration::get('PS_TAX')
            )
            ->setDisplayTaxesLabel((Configuration::get('PS_TAX') && !Configuration::get('AEUC_LABEL_TAX_INC_EXC')))
            ->setGiftCost(
                $this->context->cart->getGiftWrappingPrice(
                    $this->checkoutDeliveryStep->getIncludeTaxes()
                )
            );

        $this->checkoutProcess
            ->addStep(new CheckoutPersonalInformationStep(
                $this->context,
                $translator,
                $this->makeLoginForm(),
                $this->makeCustomerForm()
            ))
            ->addStep(new CheckoutAddressesStep(
                $this->context,
                $translator,
                $this->makeAddressForm()
            ))
            ->addStep($this->checkoutDeliveryStep)
            ->addStep(new CheckoutPaymentStep(
                $this->context,
                $translator,
                new PaymentOptionsFinder(),
                new ConditionsToApproveFinder(
                    $this->context,
                    $translator
                )
            ));

        if ($this->is_active_module) {
            foreach ($this->checkoutProcess->getSteps() as $step) {
                $step->setReachable(true)->setComplete(true)->setCurrent(true);
            }
        }

        //support module: quantitydiscountpro - v2.1.27 - idnovate
        if (Module::isEnabled('quantitydiscountpro') && Tools::getValue('action') == 'updateCarrier') {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            $quantityDiscount = new QuantityDiscountRule();
            $quantityDiscount->createAndRemoveRules();
        }
    }

    public function updateCarrier()
    {
        $this->checkoutDeliveryStep->handleRequest(Tools::getAllValues());

        $this->opc->updateCarrierControllerOPC($this);
    }

    public function postProcess()
    {
        parent::postProcess();

        $this->bootstrap();

        if (!$this->is_active_module) {
            return;
        }

        $this->opc->postProcessControllerOPC($this);
    }
}
