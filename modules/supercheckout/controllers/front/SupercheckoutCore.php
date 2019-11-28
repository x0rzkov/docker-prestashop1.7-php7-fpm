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

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;

class SupercheckoutCore extends ModuleFrontController
{
    public $ssl = true;
    public $name = 'supercheckout';
    protected $supercheckout_settings = '';
    protected $module_dir = '';
    protected $is_logged;
    protected $json = array();
    protected $nb_products;
    protected $selected_payment_method = 0;
    protected $social_login_type = '';
    protected $error = array();
    protected $shipping_error = array();
    protected $password_length = 5;
    protected $image_extensions = array('.gif', '.png', '.jpg', '.jpeg');
    // Variable for 1.7
    protected $checkout_session = null;

    public function init()
    {
        parent::init();
        // Added below code to close popup when user click cancel while login with social buttons
        if (Tools::getValue('error') == 'access_denied'
            && ((Tools::getValue('login_type') == 'fb')
            || (Tools::getValue('login_type') == 'google'))
        ) {
            echo '<script>window.close();</script>';
            die;
        }

        if (Tools::getValue('isPaymentStep')) {
            Tools::redirect(
                $this->context->link->getModuleLink(
                    'supercheckout',
                    'supercheckout',
                    array(),
                    (bool) Configuration::get('PS_SSL_ENABLED')
                )
            );
        }

        $deliveryOptionsFinder = new DeliveryOptionsFinder(
            $this->context,
            $this->getTranslator(),
            $this->objectPresenter,
            new PriceFormatter()
        );

        $this->checkout_session = new CheckoutSession(
            $this->context,
            $deliveryOptionsFinder
        );

        $this->supercheckout_settings = unserialize(Configuration::get('VELOCITY_SUPERCHECKOUT'));

        if ($this->context->cart->isVirtualCart() && $this->supercheckout_settings['hide_delivery_for_virtual'] == 0) {
            foreach (array_keys($this->supercheckout_settings['shipping_address']) as $key) {
                $this->supercheckout_settings['shipping_address'][$key]['guest']['require'] = 0;
                $this->supercheckout_settings['shipping_address'][$key]['logged']['require'] = 0;
            }
        }
        $this->context->smarty->assign(array('show_delivery_add_for_virtualcart' => false));
        // 0 means that admin don't want to show delivery address
        if ($this->context->cart->isVirtualCart() && $this->supercheckout_settings['hide_delivery_for_virtual'] == 0) {
            $this->context->smarty->assign(array('show_delivery_add_for_virtualcart' => true));
        }

        //Decode Extra Html
        $this->supercheckout_settings['html_value']['header'] = html_entity_decode(
            $this->supercheckout_settings['html_value']['header']
        );
        $this->supercheckout_settings['html_value']['footer'] = html_entity_decode(
            $this->supercheckout_settings['html_value']['footer']
        );

        foreach ($this->supercheckout_settings['design']['html'] as $key => $value) {
            $tmp = $value;
            $html_value = $this->supercheckout_settings['design']['html'][$key]['value'];
            $this->supercheckout_settings['design']['html'][$key]['value'] = html_entity_decode($html_value);
            unset($tmp);
        }

        //Check for plugin is enable or disable
        if ($this->supercheckout_settings['enable'] == 0) {
            Tools::redirect($this->checkout_session->getCheckoutUrl());
        }

        $this->module_dir = __PS_BASE_URI__ . 'modules/' . $this->module->name . '/';

        if ($this->context->customer->isLogged()) {
            $this->is_logged = true;
        } else {
            $this->is_logged = false;
        }

        $this->nb_products = $this->context->cart->nbProducts();

        $this->default_payment_selected = $this->supercheckout_settings['payment_method']['default'];
        $this->default_shipping_selected = $this->supercheckout_settings['shipping_method']['default'];
    }

    public function setMedia()
    {
        parent::setMedia();
        $stripe_official = '';
        if (Module::isInstalled('stripe_official') && Module::isEnabled('stripe_official')) {
            $stripe_official = $this->module_dir . 'views/js/front/payments.js';
        }
        $lang_iso_code = $this->context->language->iso_code;
        //add css
        $css = array(
//            $this->module_dir . 'views/css/front/reset_supercheckout.css',
//            $this->module_dir . 'views/css/front/supercheckout.css',     //commented by Anshul Mittal for design change Aug 2019
            $this->module_dir . 'views/css/front/notifications/jquery.notyfy.css',
//            $this->module_dir . 'views/css/front/notifications/default.css',
            $this->module_dir . 'views/css/front/notifications/jquery.gritter.css',
//            $this->module_dir . 'views/css/front/colorbox.css',
//            $this->module_dir . 'views/css/supercheckout_16.css',
            /* Start: Added by Anshul Mittal for design change Aug 2019*/
            $this->module_dir . 'views/css/font-awesome-new-design/css/all.css',
            $this->module_dir . 'views/css/front/style.css',
            $this->module_dir . 'views/css/front/Bootstrap/bootstrap.min.css',
            /* End: Added by Anshul Mittal for design change Aug 2019*/
            
        );
        $js = array(
            __PS_BASE_URI__ . 'js/jquery/jquery-1.11.0.min.js',
            $this->module_dir . 'views/js/front/jquery.tinysort.min.js',
            $this->module_dir . 'views/js/front/bootstrap.min.js',
//            $this->module_dir . 'views/js/front/bootstrap.js',
//            $this->module_dir . 'views/js/front/jquery.colorbox.js',
            $this->module_dir . 'views/js/front/notifications/jquery.gritter.min.js',
            $this->module_dir . 'views/js/front/notifications/jquery.notyfy.js',
            $this->module_dir . 'views/js/front/supercheckout_notifications.js',
            $this->module_dir . 'views/js/front/supercheckout.js',
            $this->module_dir . 'views/js/front/supercheckout_common.js',
            $stripe_official
        );
        $this->addJqueryPlugin('fancybox');
        
        // Changes by Anshul Mittal
//        $this->context->controller->addJqueryUI('ui.datepicker');

        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }
       
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $css[] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'js/jquery/plugins/fancybox/jquery.fancybox.css';
            $js[] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'js/jquery/plugins/fancybox/jquery.fancybox.js';
        } else {
            $css[] = _PS_BASE_URL_ . __PS_BASE_URI__ . 'js/jquery/plugins/fancybox/jquery.fancybox.css';
            $js[] = _PS_BASE_URL_ . __PS_BASE_URI__ . 'js/jquery/plugins/fancybox/jquery.fancybox.js';
        }

        foreach ($css as $css_uri) {
            if ($uri = $this->getasseturi($css_uri)) {
                $this->registerStylesheet(sha1($uri), $uri, array('media' => 'all', 'priority' => 0));
            }
        }

        foreach ($js as $js_uri) {
            if ($uri = $this->getasseturi($js_uri)) {
                $this->registerJavascript(sha1($uri), $uri, array('position' => 'bottom', 'priority' => 80));
            }
        }
    }
    /* Function added by rishabh jain on 31st July 2018 to
     *  replace this function getAssetUriFromLegacyDeprecatedMethod as this function ids deprecated now
     */
    public function getasseturi($legacy_uri)
    {
        $success = preg_match('/modules\/.*/', $legacy_uri, $matches);
        if (!$success) {
            return false;
        } else {
            return $matches[0];
        }
    }
    /* Changes over */
    public function getTemplateVarPage()
    {
        // changes by rishabh jain to fix the meta title issue
        $page = parent::getTemplateVarPage();
//        print_r($page);
//        die;
//        $meta_title = Meta::getMetaByPage('module---supercheckout-supercheckout', $this->context->language->id);
//        if ($meta_title && !empty($meta_title)) {
//            $page['meta']['title'] = $meta_title;
//        } else {
//            $page = parent::getTemplateVarPage();
        if (!isset($page['meta']['title'])) {
            $page['meta']['title'] = sprintf(
                $this->module->l('Supercheckout', 'SupercheckoutCore').' | %s',
                Configuration::get('PS_SHOP_NAME')
            );
        }
//        }
        return $page;
    }

    protected function initCheckoutAddresses($id_country = 0, $id_state = 0, $postcode = '', $id_address_delivery = 0)
    {
        if (empty($id_country)) {
            $id_country = Configuration::get('PS_COUNTRY_DEFAULT');
        }
        $delivery_address = null;
        $country = new Country($id_country);

        if ($this->context->cart->isVirtualCart()) {
            if (!$this->checkout_session->getIdAddressInvoice()) {
                if (isset($this->context->cookie->supercheckout_temp_address_invoice)
                    && $this->context->cookie->supercheckout_temp_address_invoice > 0) {
                    $this->checkout_session->setIdAddressInvoice(
                        $this->context->cookie->supercheckout_temp_address_invoice
                    );
                } else {
                    $this->checkout_session->setIdAddressInvoice(0);
                }
                $invoice_address = new Address($this->checkout_session->getIdAddressInvoice());
                $invoice_address->firstname = ' ';
                $invoice_address->lastname = ' ';
                $invoice_address->company = ' ';
                $invoice_address->address1 = ' ';
                $invoice_address->address2 = ' ';
                $invoice_address->phone_mobile = ' ';
                $invoice_address->vat_number = ' ';
                $invoice_address->city = ' ';
                $invoice_address->id_country = Configuration::get('PS_COUNTRY_DEFAULT');
                $invoice_address->id_state = 0;
                $invoice_address->postcode = 0;
                $invoice_address->other = ' ';
                $invoice_address->alias = $this->module->l('Address Alias', 'SupercheckoutCore') . ' - ' . date('s') . rand(0, 9);
                if ($invoice_address->save()) {
                    $this->checkout_session->setIdAddressInvoice($invoice_address->id);
                }
                $id_address_invoice = $this->checkout_session->getIdAddressInvoice();
                $this->context->cookie->supercheckout_temp_address_invoice = $id_address_invoice;
            }
        } else {
            if ((int) $id_address_delivery > 0) {
                $this->checkout_session->setIdAddressDelivery($id_address_delivery);
                $delivery_address = new Address($this->checkout_session->getIdAddressDelivery());
                if (!Validate::isLoadedObject($delivery_address)) {
                    $this->checkout_session->setIdAddressDelivery(0);
                    $delivery_address = new Address($this->checkout_session->getIdAddressDelivery());
                }
                if ($this->checkout_session->getIdAddressDelivery() == 0) {
                    $delivery_address->firstname = ' ';
                    $delivery_address->lastname = ' ';
                    $delivery_address->company = ' ';
                    $delivery_address->address1 = ' ';
                    $delivery_address->address2 = ' ';
                    $delivery_address->phone_mobile = ' ';
                    /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Vat Number is Required Error' issue */
//                    $delivery_address->vat_number = ' ';
                    /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Vat Number is Required Error' issue */
                    $delivery_address->city = ' ';
                    $delivery_address->postcode = 0;
                    $delivery_address->phone = ' ';
                    $delivery_address->alias = $this->module->l('Address Alias', 'SupercheckoutCore')
                        . ' - ' . date('s') . rand(0, 9);
                    $delivery_address->other = ' ';
                    $delivery_address->vat_number = Tools::getValue('vat_number', ' ');
                    $delivery_address->id_country = (int) $id_country;
                    $delivery_address->id_state = (int) $id_state;
                    if (!empty($postcode)) {
                        $delivery_address->postcode = $postcode;
                    }
                    if ($delivery_address->dni == '' && in_array('dni', AddressFormat::getFieldsRequired())) {
                        $delivery_address->dni = '-';
                    }
                    if ($delivery_address->save()) {
                        $this->checkout_session->setIdAddressDelivery($delivery_address->id);
                        $id_delivery_address = $this->checkout_session->getIdAddressDelivery();
                        $this->context->cookie->supercheckout_temp_address_delivery = $id_delivery_address;
                    } else {
                        $this->shipping_error[] = $this->module->l('Error occurred while creating new address', 'SupercheckoutCore');
                    }
                }
            } else {
                if (isset($this->context->cookie->supercheckout_temp_address_delivery)
                    && $this->context->cookie->supercheckout_temp_address_delivery > 0) {
                    $this->checkout_session->setIdAddressDelivery(
                        $this->context->cookie->supercheckout_temp_address_delivery
                    );
                } else {
                    $this->checkout_session->setIdAddressDelivery((int) $id_address_delivery);
                }
                $id_address_delivery = $this->checkout_session->getIdAddressDelivery();
                $delivery_address = new Address($id_address_delivery);
                if (!Validate::isLoadedObject($delivery_address)) {
                    $delivery_address->firstname = ' ';
                    $delivery_address->lastname = ' ';
                    $delivery_address->company = ' ';
                    $delivery_address->address1 = ' ';
                    $delivery_address->address2 = ' ';
                    $delivery_address->phone_mobile = ' ';
                    /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Vat Number is Required Error' issue */
//                    $delivery_address->vat_number = ' ';
                    /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Vat Number is Required Error' issue */
                    $delivery_address->city = ' ';
                    $delivery_address->postcode = 0;
                    $delivery_address->phone = ' ';
                    $delivery_address->alias = $this->module->l('Address Alias', 'SupercheckoutCore')
                        . ' - ' . date('s') . rand(0, 9);
                    $delivery_address->other = ' ';
                    $delivery_address->vat_number = Tools::getValue('vat_number', ' ');
                }
                $delivery_address->id_country = (int) $id_country;
                $delivery_address->id_state = (int) $id_state;
                if (!empty($postcode)) {
                    $delivery_address->postcode = $postcode;
                }

                if ($delivery_address->dni == '' && in_array('dni', AddressFormat::getFieldsRequired())) {
                    $delivery_address->dni = '-';
                }
                if ($delivery_address->save()) {
                    $this->checkout_session->setIdAddressDelivery($delivery_address->id);
                    $id_delivery_address = $this->checkout_session->getIdAddressDelivery();
                    $this->context->cookie->supercheckout_temp_address_delivery = $id_delivery_address;
                } else {
                    $this->shipping_error[] = $this->module->l('Error occurred while creating new address', 'SupercheckoutCore');
                }
            }
        }
        if (Validate::isLoadedObject($delivery_address) && count($this->shipping_error) == 0) {
            if ($this->context->cookie->isSameInvoiceAddress) {
                $this->checkout_session->setIdAddressInvoice($this->checkout_session->getIdAddressDelivery());
            }
        }
    }

    protected function updateCartDeliveryAddress($old_id_address_delivery, $new_id_address_delivery)
    {
        if ($new_id_address_delivery == $old_id_address_delivery) {
            return;
        }

        $id_cart = $this->checkout_session->getCart()->id;
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'cart_product`
        SET `id_address_delivery` = ' . (int) $new_id_address_delivery . '
        WHERE  `id_cart` = ' . (int) $id_cart . '
            AND `id_address_delivery` = ' . (int) $old_id_address_delivery;
        Db::getInstance()->execute($sql);

        $sql = 'UPDATE `' . _DB_PREFIX_ . 'customization`
            SET `id_address_delivery` = ' . (int) $new_id_address_delivery . '
            WHERE  `id_cart` = ' . (int) $id_cart . '
                AND `id_address_delivery` = ' . (int) $old_id_address_delivery;
        Db::getInstance()->execute($sql);
    }

    protected function loadCart()
    {
        $presenter = new CartPresenter();
        $presented_cart = $presenter->present($this->context->cart);
        if (!isset($presented_cart['products']) || empty($presented_cart['products'])) {
            return array('redirect' => true);
        }

        $already_added_vouchers = array();

        if (isset($presented_cart['vouchers']['allowed'])) {
            $already_added_vouchers = $presented_cart['vouchers']['added'];
        }
        $presented_cart['other_available_vouchers'] = $this->getOtherCartRules($already_added_vouchers);

        $presented_cart['settings'] = $this->supercheckout_settings;
        $presented_cart['logged'] = $this->is_logged;
        $presented_cart['user_type'] = ($this->is_logged) ? 'logged' : 'guest';
        $presented_cart['link'] = $this->context->link;
        $this->context->smarty->assign($presented_cart);
        $this->context->smarty->assign('priceDisplay', Product::getTaxCalculationMethod((int)$this->context->cookie->id_customer));
        // Assigning Custon Fields Variables into the tpl
        $id_lang_current = $this->context->language->id;
        $array_fields = $this->getCustomFieldsDetails($id_lang_current);
        $this->context->smarty->assign('array_fields', $array_fields);
        $this->context->smarty->assign('PS_STOCK_MANAGEMENT', Configuration::get('PS_STOCK_MANAGEMENT'));
        $this->context->smarty->assign('module_image_path', _PS_BASE_URL_SSL_ . _MODULE_DIR_ . 'supercheckout/views/img/front/');

        //Start: Changes added by Anshul
        if (isset($this->supercheckout_settings['free_shipping_amount']) && !empty($this->supercheckout_settings['free_shipping_amount'])) {
            $this->showFreeShippingBannerCalculations();
        }
        //End: Changes added by Anshul

        $temp_vars = array(
            'redirect' => false,
            'html' => $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . 'supercheckout/views/templates/front/cart_summary.tpl'
            )
        );
        return $temp_vars;
    }

    /*
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     */
    private function getCustomFieldsDetails($id_lang_current)
    {
        //$query_all_fields = 'SELECT * FROM '._DB_PREFIX_.'velsof_supercheckout_custom_fields WHERE active = 1';

        $id_lang = $this->context->cookie->id_lang;
        // Each field value
        //$query = 'SELECT id_velsof_supercheckout_custom_fields FROM '._DB_PREFIX_.'velsof_supercheckout_custom_fields WHERE active = 1';
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query = $query . 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        $query = $query . 'WHERE active = 1 AND cfl.id_lang = '.(int)$id_lang;

        $result_fields = Db::getInstance()->executeS($query);
        $array_fields = array();
        foreach ($result_fields as $field) {
            $id_velsof_supercheckout_custom_fields = $field['id_velsof_supercheckout_custom_fields'];
            if ($field['type'] == 'textbox' || $field['type'] == 'textarea' || $field['type'] == 'date' || $field['type'] == 'file') { //Modified by Anshul
                $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
                $query .= 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
                $query .= 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
                $query .= 'WHERE cf.id_velsof_supercheckout_custom_fields = '.(int)$id_velsof_supercheckout_custom_fields.'
					AND cfl.id_lang = '.(int)$id_lang_current.' AND cf.active = 1';
                $result_custom_fields_details = Db::getInstance()->executeS($query);
                $array_fields[$id_velsof_supercheckout_custom_fields] = $result_custom_fields_details[0];
            } else {
                $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
                $query .= 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
                $query .= 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
                $query .= 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_field_options_lang cfol ';
                $query .= 'ON cf.id_velsof_supercheckout_custom_fields = cfol.id_velsof_supercheckout_custom_fields ';
                $query .= 'WHERE cf.id_velsof_supercheckout_custom_fields = '.(int)$id_velsof_supercheckout_custom_fields.'
					AND cfl.id_lang = '.(int)$id_lang_current.' AND cfol.id_lang = '. (int)$id_lang_current.' AND cf.active = 1';
                $result_custom_fields_details = Db::getInstance()->executeS($query);
                // Setting required variables
                $array_fields[$id_velsof_supercheckout_custom_fields]['options'] = $result_custom_fields_details;
                $array_fields[$id_velsof_supercheckout_custom_fields]['id_velsof_supercheckout_custom_fields'] = $id_velsof_supercheckout_custom_fields;
                $array_fields[$id_velsof_supercheckout_custom_fields]['type'] = $result_custom_fields_details[0]['type'];
                $array_fields[$id_velsof_supercheckout_custom_fields]['position'] = $result_custom_fields_details[0]['position'];
                $array_fields[$id_velsof_supercheckout_custom_fields]['required'] = $result_custom_fields_details[0]['required'];
                $array_fields[$id_velsof_supercheckout_custom_fields]['field_label'] = $result_custom_fields_details[0]['field_label'];
                $array_fields[$id_velsof_supercheckout_custom_fields]['field_help_text'] = $result_custom_fields_details[0]['field_help_text'];
            }
        }
        return $array_fields;
    }

    protected function checkForDniandVat($id_country = 0)
    {
        $vars = array();
        $vars['is_need_dni'] = Country::isNeedDniByCountryId($id_country);
        $vars['is_need_vat'] = $this->isNeedVat();
        $vars['is_need_states'] = Country::containsStates($id_country);
        $vars['is_need_zip_code'] = Country::getNeedZipCode($id_country);
        return $vars;
    }

    protected function isValidDni($dni)
    {
        $response = array();
        if ($dni == '' || !Validate::isDniLite($dni)) {
            $response['error'] = $this->module->l('DNI Error', 'SupercheckoutCore');
        } else {
            $response['success'] = true;
        }
        return $response;
    }

    protected function isNeedVat()
    {
        /* Changes done by rishabh jain on 31st July i.e. removed condition(Module::isInstalled('vatnumber')&& Module::getInstanceByName('vatnumber')->active&& Configuration::get('VATNUMBER_MANAGEMENT')) from vat number as
         * European VAT number module does not work on 1.7
         */
        if (in_array('vat_number', AddressFormat::getFieldsRequired())) {
            return true;
        }
        return false;
    }

    protected function isValidVatNumber($vat_number)
    {
        if (empty($vat_number)) {
            return false;
        }

        $response = array();
        if (Module::isInstalled('vatnumber')
            && Module::getInstanceByName('vatnumber')->active
            && Configuration::get('VATNUMBER_CHECKING')
        ) {
            include_once(_PS_MODULE_DIR_ . 'vatnumber/vatnumber.php');

            $service_response = VatNumber::WebServiceCheck($vat_number);
            if (count($service_response) > 0) {
                $response['error'] = $service_response;
            } else {
                $response['success'] = true;
            }
        } else {
            $response['success'] = true;
        }

        return $response;
    }

    protected function sendConfirmationMail($customer, $passd)
    {
        if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }

        return Mail::Send(
            $this->context->language->id,
            'account',
            Mail::l('Welcome!'),
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{passwd}' => $passd
            ),
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }

    public function checkZipCode($id_country, $postcode)
    {
        $arr = array();
        $zip_code_format = Country::getZipCodeFormat((int) $id_country);
        if (Country::getNeedZipCode((int) $id_country)) {
            if ($zip_code_format) {
                $zip_regexp = '/^' . $zip_code_format . '$/ui';
                $zip_regexp = str_replace(' ', '( |)', $zip_regexp);
                $zip_regexp = str_replace('-', '(-|)', $zip_regexp);
                $zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
                $zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
                $zip_regexp = str_replace('C', Country::getIsoById((int) $id_country), $zip_regexp);

                if (!preg_match($zip_regexp, $postcode)) {
                    $arr['error'] = $this->module->l('Invalid Zip Code', 'SupercheckoutCore') . '<br />'
                        . $this->module->l('Must be typed as follows:', 'SupercheckoutCore') . ' '
                        . str_replace(
                            'C',
                            Country::getIsoById((int) $id_country),
                            str_replace('N', '0', str_replace('L', 'A', $zip_code_format))
                        );
                } else {
                    $arr['success'] = true;
                }
            } elseif ($zip_code_format) {
                $arr['error'] = $this->module->l('Required Field', 'SupercheckoutCore');
            } elseif ($postcode && !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode)) {
                $arr['error'] = $this->module->l('Invalid Zip Code', 'SupercheckoutCore');
            } else {
                $arr['success'] = true;
            }
        } else {
            $arr['success'] = true;
        }

        return $arr;
    }

    public function createFreeOrder()
    {
//        if (!class_exists('KbMailChimp')) {
        include_once _PS_MODULE_DIR_ . 'supercheckout/controllers/front/FreeOrder.php';
//        }
        $order = new FreeOrder();
        $order->free_order_class = true;
        $free_order_error = 'Free order';
        $order->validateOrder(
            $this->context->cart->id,
            Configuration::get('PS_OS_PAYMENT'),
            0,
            $free_order_error,
            null,
            array(),
            null,
            false,
            $this->context->cart->secure_key
        );
        $order_id = (int) Order::getOrderByCartId($this->context->cart->id);

        $order1 = new Order((int) $order_id);
        $email = $this->context->customer->email;
        if ($this->context->customer->is_guest) {
            $this->context->customer->logout();
        }

        return array('order_reference' => $order1->reference, 'email' => $email);
    }

    public function checkZipForCountry(Country $country, $postcode, $isshippingzipcode = false)
    {
        if ($this->context->cart->isVirtualCart()
            && $isshippingzipcode == true
            && $this->supercheckout_settings['hide_delivery_for_virtual'] == 0
        ) {
            return false;
        }
        if ($country->zip_code_format && !$country->checkZipCode($postcode)) {
            return array('key' => 'postcode',
                'error' => $this->module->l('Invalid Zip Code', 'SupercheckoutCore') . '<br>'
                . $this->module->l('Must be typed as follows:', 'SupercheckoutCore')
                . str_replace(
                    'C',
                    $country->iso_code,
                    str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format))
                )
            );
        } elseif (empty($postcode) && $country->need_zip_code) {
            return array('key' => 'postcode', 'error' => $this->module->l('Required Field', 'SupercheckoutCore'));
        } elseif ($postcode && !Validate::isPostCode($postcode)) {
            return array('key' => 'postcode', 'error' => $this->module->l('Invalid Zip Code', 'SupercheckoutCore'));
        } else {
            return false;
        }
    }

    protected function processCustomerNewsletter(&$customer)
    {
        if (Tools::getValue('newsletter')) {
            $customer->ip_registration_newsletter = pSQL(Tools::getRemoteAddr());
            $customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));

            if ($module_newsletter = Module::getInstanceByName('blocknewsletter')) {
                $module_newsletter->confirmSubscription(Tools::getValue('email'));
            }
        }
    }

    public function generateRandomPassword()
    {
        $length = 8;
        $code = '';
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789';
        $maxlength = Tools::strlen($chars);
        if ($length > $maxlength) {
            $length = $maxlength;
        }

        $i = 0;
        while ($i < $length) {
            $char = Tools::substr($chars, mt_rand(0, $maxlength - 1), 1);
            if (!strstr($code, $char)) {
                $code .= $char;
                $i++;
            }
        }
        return $code;
    }

    public static function aliasExistOveridden($alias, $id_address, $id_customer)
    {
        $query = new DbQuery();
        $query->select('count(*)');
        $query->from('address');
        $query->where('alias = \'' . pSQL($alias) . '\'');
        $query->where('id_address != ' . (int) $id_address);
        $query->where('id_customer = ' . (int) $id_customer);
        $query->where('deleted = 0');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    protected function supercheckoutUpdateMsg($message_content)
    {
        if ($message_content) {
            if (!Validate::isMessage($message_content)) {
                $invalid_message_error = $this->module->l('Invalid message', 'SupercheckoutCore');
                $this->errors[] = Tools::displayError($invalid_message_error);
            } elseif ($old_message = Message::getMessageByCartId((int) $this->context->cart->id)) {
                $message = new Message((int) $old_message['id_message']);
                $message->message = $message_content;
                $message->update();
            } else {
                $message = new Message();
                $message->message = $message_content;
                $message->id_cart = (int) $this->context->cart->id;
                $message->id_customer = (int) $this->context->cart->id_customer;
                $message->add();
            }
        } else {
            if ($old_message = Message::getMessageByCartId($this->context->cart->id)) {
                $message = new Message($old_message['id_message']);
                $message->delete();
            }
        }
        return true;
    }

    protected function addEmailToList($email, $fname = '', $lname = '')
    {
        if (!class_exists('KbMailChimp')) {
            include_once _PS_MODULE_DIR_ . 'supercheckout/libraries/mailchimpl library.php';
        }
        $apikey = $this->supercheckout_settings['mailchimp']['api'];
        $mailchimp = new KbMailChimp($apikey);
        $listid = $this->supercheckout_settings['mailchimp']['list'];
        try {
            $mailchimp->call(
                'lists/subscribe',
                array(
                    'id' => $listid,
                    'email' => array('email' => $email),
                    'merge_vars' => array('FNAME' => $fname, 'LNAME' => $lname),
                    'double_optin' => false,
                    'update_existing' => true,
                    'replace_interests' => false,
                    'send_welcome' => false,
                )
            );
        } catch (Exception $e) {
            return;
        }
    }
    
    
    /*
    * Function added by Anshul to subscribe customer email to SendinBlue
    *
    * @param    string email   Email of customer
    * @param    string first_name   First name of customer
    * @param    string last_name   Last name of customer
    */
    
    protected function addEmailToListSendinBlue($email, $first_name = null, $last_name = null)
    {
        $apikey = $this->supercheckout_settings['SendinBlue']['api'];
        $listid = $this->supercheckout_settings['SendinBlue']['list'];
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', $apikey);

        $data_arr = array("email" => $email,
            "listid" => array($listid),
            "attributes" => array("NAME" => $first_name, "SURNAME" => $last_name)
        );

        $mailin->create_update_user($data_arr); //calling function to add user
    }
    
    /*
    * Function added by Anshul to subscribe customer email to Klaviyo
    *
    * @param    string email   Email of customer
    * @param    string first_name   First name of customer
    * @param    string last_name   Last name of customer
    */
    
    protected function addEmailToListKlaviyo($email, $first_name = null, $last_name = null)
    {
        $api_key = $this->supercheckout_settings['klaviyo']['api'];
        $list_id = $this->supercheckout_settings['klaviyo']['list'];
        $properties = array();
        if ($first_name) {
            $properties['$first_name'] = $first_name;
        }
        if ($last_name) {
            $properties['$last_name'] = $last_name;
        }
        $properties_val = count($properties) ? urlencode(json_encode($properties)) : '{}';
        $fields = array(
            'api_key=' . $api_key,
            'email=' . urlencode($email),
            'confirm_optin=false',
            'properties=' . $properties_val,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://a.klaviyo.com/api/v1/list/' . $list_id . '/members');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
    }

    protected function getOtherCartRules($rule_in_cart = array())
    {
        $available_cart_rules = CartRule::getCustomerCartRules(
            $this->context->language->id,
            (isset($this->context->customer->id) ? $this->context->customer->id : 0),
            true,
            true,
            true,
            $this->context->cart
        );

        foreach ($available_cart_rules as $key => $available_cart_rule) {
            if ((isset($available_cart_rule['highlight']) && !$available_cart_rule['highlight'])
                || strpos($available_cart_rule['code'], 'BO_ORDER_') === 0
            ) {
                unset($available_cart_rules[$key]);
                continue;
            }
            foreach ($rule_in_cart as $cart_cart_rule) {
                if ($available_cart_rule['id_cart_rule'] == $cart_cart_rule['id_cart_rule']) {
                    unset($available_cart_rules[$key]);
                    continue 2;
                }
            }
        }
        return $available_cart_rules;
    }

    protected function getGuestInformations()
    {
        $customer = $this->context->customer;
        $address_delivery = new Address($this->context->cookie->supercheckout_perm_address_delivery);

        $address_cookie = $this->context->cookie->supercheckout_perm_address_invoice;
        $id_address_invoice = $this->context->cookie->supercheckout_perm_address_invoice ? (int) $address_cookie : 0;
        $address_invoice = new Address($id_address_invoice);
        if ($customer->birthday) {
            $birthday = explode('-', $customer->birthday);
        } else {
            $birthday = array('0', '0', '0');
        }

        return array(
            'id_customer' => (int) $customer->id,
            'email' => $customer->email,
            'customer_lastname' => $customer->lastname,
            'customer_firstname' => $customer->firstname,
            'newsletter' => (int) $customer->newsletter,
            'optin' => (int) $customer->optin,
            'id_address_delivery' => (int) $this->context->cart->id_address_delivery,
            'company' => $address_delivery->company,
            'lastname' => $address_delivery->lastname,
            'firstname' => $address_delivery->firstname,
            'vat_number' => $address_delivery->vat_number,
            'dni' => $address_delivery->dni,
            'address1' => $address_delivery->address1,
            'address2' => $address_delivery->address2,
            'postcode' => $address_delivery->postcode,
            'city' => $address_delivery->city,
            'phone' => $address_delivery->phone,
            'alias' => $address_delivery->alias,
            'other' => $address_delivery->other,
            'phone_mobile' => $address_delivery->phone_mobile,
            'id_country' => (int) $address_delivery->id_country,
            'id_state' => (int) $address_delivery->id_state,
            'id_gender' => (int) $customer->id_gender,
            'sl_year' => $birthday[0],
            'sl_month' => $birthday[1],
            'sl_day' => $birthday[2],
            'company_invoice' => $address_invoice->company,
            'lastname_invoice' => $address_invoice->lastname,
            'firstname_invoice' => $address_invoice->firstname,
            'vat_number_invoice' => $address_invoice->vat_number,
            'dni_invoice' => $address_invoice->dni,
            'address1_invoice' => $address_invoice->address1,
            'address2_invoice' => $address_invoice->address2,
            'postcode_invoice' => $address_invoice->postcode,
            'city_invoice' => $address_invoice->city,
            'phone_invoice' => $address_invoice->phone,
            'phone_mobile_invoice' => $address_invoice->phone_mobile,
            'id_country_invoice' => (int) $address_invoice->id_country,
            'id_state_invoice' => (int) $address_invoice->id_state,
            'id_address_invoice' => $id_address_invoice,
            'invoice_company' => $address_invoice->company,
            'invoice_lastname' => $address_invoice->lastname,
            'invoice_firstname' => $address_invoice->firstname,
            'invoice_vat_number' => $address_invoice->vat_number,
            'invoice_dni' => $address_invoice->dni,
            'invoice_address' => $this->context->cart->id_address_invoice != $this->context->cart->id_address_delivery,
            'invoice_address1' => $address_invoice->address1,
            'invoice_address2' => $address_invoice->address2,
            'invoice_postcode' => $address_invoice->postcode,
            'invoice_city' => $address_invoice->city,
            'invoice_phone' => $address_invoice->phone,
            'invoice_phone_mobile' => $address_invoice->phone_mobile,
            'invoice_id_country' => (int) $address_invoice->id_country,
            'invoice_id_state' => (int) $address_invoice->id_state,
            'invoice_alias' => $address_invoice->alias,
            'invoice_other' => $address_invoice->other,
        );
    }

    protected function updateCarrier()
    {
        $error = array();
        if (Tools::getIsset('delivery_option')) {
            $this->checkout_session->setDeliveryOption(
                Tools::getValue('delivery_option')
            );
        } else {
            $error[] = $this->module->l('Please select delivery option.', 'SupercheckoutCore');
        }

        Hook::exec('actionCarrierProcess', array('cart' => $this->checkout_session->getCart()));

        return array('hasError' => !empty($error), 'errors' => $error);
    }
    
    
    /*
     * Function to check module url is secure or not
     */

    private function getModuleDirUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        return $module_dir;
    }

    /*
     * Function to check url is secure or not
     */

    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Code added by Anshul for uploading the file field first
     */
    public function saveFileTypeCustomField()
    {
        $errors = array();
        $errors['error_occured'] = 0;
//        print_r($_FILES['custom_fields']);die;
        if (isset($_FILES['custom_fields'])) {
            $id_cart = (int) $this->context->cookie->id_cart;
            $where_delete = "id_cart = " . (int) $id_cart;
            Db::getInstance()->delete('velsof_supercheckout_fields_data', $where_delete);
            foreach ($_FILES['custom_fields']["tmp_name"] as $key => $value) {
                $file_info = array();
                $file_extension = pathinfo($_FILES['custom_fields']["name"][$key], PATHINFO_EXTENSION);
                $time = time().'_'.$_FILES['custom_fields']["name"][$key];
                $path = _PS_MODULE_DIR_ . $this->module->name . '/views/img/upload/' . $time . '.' . $file_extension;
                $upload = move_uploaded_file(
                    $_FILES["custom_fields"]["tmp_name"][$key],
                    $path
                );
                chmod($path, 0777);
                if ($upload) {
                    // Each field value
                    $path = $this->getModuleDirUrl() .'/'.$this->module->name. '/views/img/upload/' . $time . '.' . $file_extension;
                    $id_velsof_supercheckout_custom_fields = Tools::substr($key, (strrpos($key, '_') + 1));
                    $file_info['path'] = $path;
                    $file_info['relative_path'] = _PS_MODULE_DIR_ .$this->module->name. '/views/img/upload/' . $time . '.' . $file_extension;
                    $file_info['name'] = $_FILES['custom_fields']["name"][$key];
                    $file_info['type'] = $_FILES['custom_fields']["type"][$key];
                    $file_info['extension'] = $file_extension;
                    if (is_array($file_info)) {
                        $value = serialize($file_info);
                    }
                    $fields_data = array(
                        'id_velsof_supercheckout_custom_fields' => (int) $id_velsof_supercheckout_custom_fields,
                        'id_order' => 0,
                        'id_cart' => (int) $id_cart,
                        'field_value' => pSQL($value)
                    );
                    Db::getInstance()->insert('velsof_supercheckout_fields_data', $fields_data);
                } else {
                    $errors['error_occured'] = 1;
                    $errors['msg'] = $this->module->l('Error while uploading a file', 'supercheckout');
                }
            }
            echo Tools::jsonEncode($errors);
            die;
        }
    }

    protected function confirmOrder()
    {
        $response = array();
        if (!$this->nb_products) {
            $response['error']['general'][] = $this->module->l('Your Cart is Empty', 'SupercheckoutCore');
            return $response;
        }

        $posted_data = $_POST;
        // Getting custom fields array out of $_POST
        $custom_field_values = Tools::getValue('custom_fields');
        if (Tools::getIsset('use_for_invoice')) {
            $use_for_invoice = Tools::getValue('use_for_invoice');
        } else {
            $use_for_invoice = 'off';
        }
        unset($_POST);

        if (isset($posted_data['checkout_option']) && $posted_data['checkout_option'] == 0) {
            if (!$this->is_logged) {
                $response['error']['general'][] = $this->module->l('Please login first', 'SupercheckoutCore');
                return $response;
            }
        }

        if (!$this->context->cart->isVirtualCart()) {
            if (!isset($posted_data['delivery_option']) || empty($posted_data['delivery_option'])) {
                $response['error']['checkout_option'][] = array('key' => 'shipping_method_error',
                    'error' => $this->module->l('No Delivery Method Selected.', 'SupercheckoutCore'));
                return $response;
            }
        }

        // <editor-fold defaultstate="collapsed" desc="GDPR Change">
        $getMandatoryActivePolicySQL = 'SELECT policy_id FROM '._DB_PREFIX_.'velsof_supercheckout_policies where is_manadatory = 1 and status = 1';
        $getMandatoryActivePolicyList = Db::getInstance()->ExecuteS($getMandatoryActivePolicySQL);
        $oneDimensionalMandatoryServiceListArray = array_map('current', $getMandatoryActivePolicyList);
        if (count($getMandatoryActivePolicyList)) {
            if (!isset($posted_data['kb_super_policy']) || (!is_array($posted_data['kb_super_policy'])) || (!count($posted_data['kb_super_policy']))) {
                $response['error']['general'][] = $this->module->l('Please acccept mandatory policy services before confirming your order', 'SupercheckoutCore');
                return $response;
            } else {
                $acceptedServiceList = array_keys($posted_data['kb_super_policy']);
                if (!(array_intersect($oneDimensionalMandatoryServiceListArray, $acceptedServiceList) == $oneDimensionalMandatoryServiceListArray)) {
                    $response['error']['general'][] = $this->module->l('Please acccept mandatory policy services before confirming your order', 'SupercheckoutCore');
                    return $response;
                }
            }
        }
        if (isset($posted_data['kb_super_policy'])) {
            $this->context->cookie->__set('supercheckout_accepted_consent', Tools::jsonEncode(array_keys($posted_data['kb_super_policy'])));
        } else {
            $this->context->cookie->__set('supercheckout_accepted_consent', Tools::jsonEncode(array()));
        }
        if (Configuration::get('PS_CONDITIONS')) {
            if ($this->supercheckout_settings['confirm']['term_condition'][($this->is_logged) ? 'logged' : 'guest']['require'] == 1) {
                $this->context->cookie->__set('supercheckout_default_policy', $posted_data['supercheckout_default_policy']);
            }
        }

        // </editor-fold>

        $order_total = $this->context->cart->getOrderTotal(false, Cart::BOTH);
        if (!isset($posted_data['payment_method']) && $order_total != 0) {
            $response['error']['general'][] = $this->module->l('No payment method is selected.', 'SupercheckoutCore');
            return $response;
        }

        if ($this->is_logged) {
            $id_customer = $this->context->customer->id;
        } elseif ($this->is_logged && $this->context->cookie->is_guest) {
            $id_customer = (int) $this->context->cookie->id_customer;
        } else {
            $id_customer = 0;
        }

        /* start-MK made changes to display error if product is out of stock */
        $product = $this->context->cart->checkQuantities(true);
        if (!empty($product) && is_array($product)) {
            $str = '';
            $str .= (isset($product['attributes']) && !empty($product['attributes'])) ? $product['attributes'] : '';
            $msg = sprintf('An item (%1s) in your cart is no longer available in this quantity. You cannot proceed with your order until the quantity is adjusted.', $product['name'] . '-' . $str);
            $response['error']['general'][] = $msg;
            return $response;
        }
        /* End-MK made changes to display error if product is out of stock */

        $id_current_address_delivery = $this->checkout_session->getIdAddressDelivery();
        $currency = Currency::getCurrency((int) $this->context->cart->id_currency);
        $minimal_purchase = Tools::convertPrice((float) Configuration::get('PS_PURCHASE_MINIMUM'), $currency);

        if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase) {
            $msg = $this->module->l('A minimum purchase total of %1s (tax excl.) is required in order to validate your order, current purchase is %2s (tax excl.).', 'SupercheckoutCore');
            $formatted_min_purchse = Tools::displayPrice($minimal_purchase, $currency);
            $order_total = Tools::displayPrice(
                $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS),
                $currency
            );
            $response['error']['general'][] = sprintf($msg, $formatted_min_purchse, $order_total);
            return $response;
        }

        $delivery_address = null;
        $invoice_address = null;

        $id_delivery_address = 0;
        if ((isset($posted_data['shipping_address_value'])
            && $posted_data['shipping_address_value'] == 1)
            || !isset($posted_data['shipping_address_value'])
        ) {
            if (isset($this->context->cookie->supercheckout_temp_address_delivery)
                && $this->context->cookie->supercheckout_temp_address_delivery > 0
            ) {
                $id_delivery_address = $this->context->cookie->supercheckout_temp_address_delivery;
            }
        } elseif (isset($posted_data['shipping_address_value'])
            && $posted_data['shipping_address_value'] == 0
            && isset($posted_data['shipping_address_id'])
        ) {
            $id_delivery_address = $posted_data['shipping_address_id'];
        }

        $id_invoice_address = 0;
        if (isset($posted_data['use_for_invoice'])) {
            $id_invoice_address = $id_delivery_address;
        } elseif (((isset($posted_data['payment_address_value']) && $posted_data['payment_address_value'] == 1)
            || !isset($posted_data['payment_address_value']))
        ) {
            if (isset($this->context->cookie->supercheckout_temp_address_invoice)
                && $this->context->cookie->supercheckout_temp_address_invoice > 0) {
                $id_invoice_address = $this->context->cookie->supercheckout_temp_address_invoice;
            } else {
                $temp_invoice_address = new Address();
                $temp_country_var = new Country((int) Configuration::get('PS_COUNTRY_DEFAULT'));
                if ($temp_country_var->need_identification_number) {
                    $temp_invoice_address->dni = '-';
                }

                $temp_invoice_address->firstname = ' ';
                $temp_invoice_address->lastname = ' ';
                $temp_invoice_address->company = ' ';
                $temp_invoice_address->address1 = ' ';
                $temp_invoice_address->address2 = ' ';
                $temp_invoice_address->phone_mobile = ' ';
                $temp_invoice_address->vat_number = ' ';
                $temp_invoice_address->city = ' ';
                $temp_invoice_address->postcode = 0;
                $temp_invoice_address->phone = ' ';
                $temp_invoice_address->alias = $this->module->l('Address Alias', 'SupercheckoutCore')
                    . ' - ' . date('s') . rand(0, 9);
                $temp_invoice_address->other = ' ';
                $temp_invoice_address->id_country = (int) Configuration::get('PS_COUNTRY_DEFAULT');
                $temp_invoice_address->id_state = 0;

                if (!$temp_invoice_address->save()) {
                    $response['error']['general'][] = $this->module->l('Error occurred while creating new address', 'SupercheckoutCore');
                } else {
                    $id_invoice_address = $temp_invoice_address->id;
                }
                $this->context->cookie->supercheckout_temp_address_invoice = $id_invoice_address;
            }
        } elseif (!isset($posted_data['use_for_invoice'])
            && isset($posted_data['payment_address_value'])
            && $posted_data['payment_address_value'] == 0
        ) {
            $id_invoice_address = $posted_data['payment_address_id'];
        }

        //////////////////////////Start - Plugin Validations //////////////////////////
        //Set User Type and password according to user type
        $check_new_password = 0;
        if (isset($posted_data['checkout_option']) && $posted_data['checkout_option'] != 0) {
            $checkout_option = 1;
            $check_new_password = $posted_data['checkout_option'];
        } else {
            $checkout_option = 0;
        }

        $user_type = ($checkout_option == 0) ? 'logged' : 'guest';

        if (!$this->is_logged) {
            $email = $posted_data['supercheckout_email'];

            if ($email == '') {
                $response['error']['checkout_option'][] = array(
                    'key' => 'supercheckout_email',
                    'error' => $this->module->l('An email address required.', 'SupercheckoutCore')
                );
            } elseif (!Validate::isEmail($email)) {
                $response['error']['checkout_option'][] = array(
                    'key' => 'supercheckout_email',
                    'error' => $this->module->l('Invalid email address.', 'SupercheckoutCore')
                );
            } elseif (Customer::customerExists($email)
                && isset($posted_data['checkout_option'])
            ) {
                $response['error']['checkout_option'][] = array(
                    'key' => 'supercheckout_email',
                    'error' => $this->module->l('This customer is already exist', 'SupercheckoutCore')
                );
            }

            //Customer Personal Information
            foreach ($posted_data['customer_personal'] as $key => $value) {
                if ($key != 'dob_days' && $key != 'dob_months' && $key != 'dob_years') {
                    if ($key == 'password') {
                        if ($check_new_password == 2) {
                            $new_password = $posted_data['customer_personal'][$key];
                            if ($new_password == '') {
                                $response['error']['customer_personal'][] = array(
                                    'key' => $key,
                                    'error' => $this->module->l('Password is required.', 'SupercheckoutCore')
                                );
                            } elseif (!(Tools::strlen($new_password) >= $this->password_length
                                && Tools::strlen($new_password) < 255)
                            ) {
                                $response['error']['customer_personal'][] = array(
                                    'key' => $key,
                                    'error' => sprintf($this->module->l('Invalid Password', 'SupercheckoutCore'), Validate::PASSWORD_LENGTH)
                                );
                            }
                        }
                    } else {
                        if (isset($this->supercheckout_settings['customer_personal'][$key][$user_type]['require'])
                            && $this->supercheckout_settings['customer_personal'][$key][$user_type]['require'] == 1
                            && !isset($posted_data['customer_personal'][$key])
                        ) {
                            $response['error']['customer_personal'][] = array(
                                'key' => $key,
                                'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                            );
                        }
                    }
                }
            }
            $check_dob = false;
            if (isset($posted_data['customer_personal']['dob_days'])
                && isset($posted_data['customer_personal']['dob_months'])
                && isset($posted_data['customer_personal']['dob_years'])
            ) {
                if ($this->supercheckout_settings['customer_personal']['dob'][$user_type]['require'] == 1
                    && $checkout_option == 1
                ) {
                    $check_dob = true;
                    $birthday = (((empty($posted_data['customer_personal']['dob_years']))
                        ? '' : (int) $posted_data['customer_personal']['dob_years'])
                        . '-' . ((empty($posted_data['customer_personal']['dob_months']))
                        ? '' : (int) $posted_data['customer_personal']['dob_months'])
                        . '-' . ((empty($posted_data['customer_personal']['dob_days']))
                        ? '' : (int) $posted_data['customer_personal']['dob_days']));
                    if (empty($birthday)) {
                        $response['error']['customer_personal'][] = array(
                            'key' => 'dob',
                            'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                        );
                    } elseif (!Validate::isBirthDate($birthday)) {
                        $response['error']['customer_personal'][] = array(
                            'key' => 'dob',
                            'error' => $this->module->l('Invalid date of birth', 'SupercheckoutCore')
                        );
                    }
                }
            }
        } else {
            $checkout_option = 0;
        }

        $shipping_address_value = 1;
        if (isset($posted_data['shipping_address_value'])) {
            $shipping_address_value = $posted_data['shipping_address_value'];
        }

        $loop_index = 0;
        if ($shipping_address_value == 1) {
            foreach ($posted_data['shipping_address'] as $key => $value) {
                $add_plugin_config = $this->supercheckout_settings['shipping_address'][$key];
                if ($add_plugin_config[$user_type]['require'] == 1
                    && $posted_data['shipping_address'][$key] == ''
                ) {
                    /* Start - Code Modified by Raghu on 22-Aug-2017 for fixing 'In guest checkout if we do not save the address and then place order then system is throwing error (Please provide required Information) and then user is unable to process forward.' issue */
                    if ($key == 'dni') {
                        $country = new Country($posted_data['shipping_address']['id_country']);
                        if ($country->isNeedDni()) {
                            $response['error']['shipping_address'][$loop_index] = array('key' => $key, 'error' => $this->module->l('Required Field', 'SupercheckoutCore'));
                        }
                    } else {
                        $response['error']['shipping_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                        );
                    }
                    /* End - Code Modified by Raghu on 22-Aug-2017 for fixing 'In guest checkout if we do not save the address and then place order then system is throwing error (Please provide required Information) and then user is unable to process forward.' issue */
                }
                if (($key == 'phone_mobile' || $key == 'phone')
                    && !empty($posted_data['shipping_address'][$key])
                    && !(boolean) Validate::isPhoneNumber($posted_data['shipping_address'][$key])
                ) {
                    $response['error']['shipping_address'][$loop_index] = array(
                        'key' => $key,
                        'error' => $this->module->l('Invalid phone number', 'SupercheckoutCore')
                    );
                }
                if ($key == 'id_country') {
                    $country = new Country($posted_data['shipping_address'][$key]);

                    if ($posted_data['shipping_address'][$key] == 0) {
                        $response['error']['shipping_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                        );
                    } elseif (!$country->active) {
                        $response['error']['shipping_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('This country is not active', 'SupercheckoutCore')
                        );
                    } elseif ((int) $country->contains_states
                        && (isset($posted_data['shipping_address']['id_state'])
                        && !(int) $posted_data['shipping_address']['id_state'])
                    ) {
                        $response['error']['shipping_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('This country requires you to chose a State', 'SupercheckoutCore')
                        );
                    }
                    $postcode_error = $this->checkZipForCountry(
                        $country,
                        $posted_data['shipping_address']['postcode'],
                        true
                    );
                    if (isset($posted_data['shipping_address']['postcode'])
                        && $postcode_error && !empty($posted_data['shipping_address']['postcode'])) {
                        $response['error']['shipping_address'][$loop_index] = $postcode_error;
                    }

                    if ($this->supercheckout_settings['shipping_address']['dni'][$user_type]['require'] == 1
                        && $country->isNeedDni() && !empty($posted_data['shipping_address']['dni'])
                    ) {
                        if (isset($posted_data['shipping_address']['dni'])
                            && $country->isNeedDni() && ($posted_data['shipping_address']['dni'] == ''
                            || !Validate::isDniLite($posted_data['shipping_address']['dni']))
                        ) {
                            $response['error']['shipping_address'][$loop_index] = array(
                                'key' => 'dni',
                                'error' => $this->module->l('DNI Error', 'SupercheckoutCore')
                            );
                        }
                    }
                }

                if ($key == 'id_state' && $posted_data['shipping_address'][$key] == 0) {
                    if (Country::containsStates((int) $posted_data['shipping_address']['id_country'])) {
                        $response['error']['shipping_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                        );
                    }
                }
                if ($id_customer != 0) {
                    if ($key == 'alias' && !empty($posted_data['shipping_address'][$key])) {
                        $sql = 'select * from ' . _DB_PREFIX_ . 'address
                            where id_address = ' . (int) $id_delivery_address
                            . ' AND alias = "' . pSQL($posted_data['shipping_address'][$key]) . '"  AND id_customer = "'.(int)$id_customer.'"';
                        $is_alias_onsame_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!count($is_alias_onsame_id)) {
                            $is_alias_overriden = $this->aliasExistOveridden(
                                $posted_data['shipping_address'][$key],
                                (int) $id_delivery_address,
                                $id_customer
                            );
                            if ($is_alias_overriden) {
                                $response['error']['shipping_address'][$loop_index] = array(
                                    'key' => $key,
                                    'error' => $this->module->l('This title has already taken', 'SupercheckoutCore')
                                );
                            }
                        }
                    }
                }


                /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'VAT Number Required Validation' issue */
                if ($key == 'vat_number' && $this->isNeedVat() && empty($posted_data['shipping_address'][$key])) {
                    $response['error']['shipping_address'][$loop_index] = array('key' => $key,
                                'error' => $this->module->l('Required Field', 'SupercheckoutCore'));
                }
                /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'VAT Number Required Validation' issue */

                $loop_index++;
            }
        }

        $payment_address_value = 1;
        if (isset($posted_data['payment_address_value'])) {
            $payment_address_value = $posted_data['payment_address_value'];
        }

        if (!isset($posted_data['use_for_invoice'])) {
            $loop_index = 0;
            if ($payment_address_value == 1) {
                foreach ($posted_data['payment_address'] as $key => $value) {
                    $add_plugin_config = $this->supercheckout_settings['payment_address'][$key];
                    if ($add_plugin_config[$user_type]['require'] == 1
                        && $posted_data['payment_address'][$key] == ''
                    ) {
                        /* Start - Code Modified by Raghu on 22-Aug-2017 for fixing 'In guest checkout if we do not save the address and then place order then system is throwing error (Please provide required Information) and then user is unable to process forward.' issue */
                        if ($key == 'dni') {
                            $country = new Country($posted_data['payment_address']['id_country']);
                            if ($country->isNeedDni()) {
                                $response['error']['payment_address'][$loop_index] = array('key' => $key, 'error' => $this->module->l('Required Field', 'SupercheckoutCore'));
                            }
                        } else {
                            $response['error']['payment_address'][$loop_index] = array(
                                'key' => $key,
                                'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                            );
                        }
                        /* End - Code Modified by Raghu on 22-Aug-2017 for fixing 'In guest checkout if we do not save the address and then place order then system is throwing error (Please provide required Information) and then user is unable to process forward.' issue */
                    }

                    if (($key == 'phone_mobile' || $key == 'phone')
                        && !empty($posted_data['payment_address'][$key])
                        && !(boolean) Validate::isPhoneNumber($posted_data['payment_address'][$key])
                    ) {
                        $response['error']['payment_address'][$loop_index] = array(
                            'key' => $key,
                            'error' => $this->module->l('Invalid phone number', 'SupercheckoutCore')
                        );
                    }

                    if ($key == 'id_country') {
                        $country = new Country($posted_data['payment_address'][$key]);

                        if ($posted_data['payment_address'][$key] == 0) {
                            $response['error']['payment_address'][$loop_index] = array(
                                'key' => $key,
                                'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                            );
                        } elseif ((int) $country->contains_states
                            && (isset($posted_data['payment_address']['id_state'])
                            && !(int) $posted_data['payment_address']['id_state'])
                        ) {
                            $response['error']['payment_address'][$loop_index] = array(
                                'key' => $key,
                                'error' => $this->module->l('This country requires you to chose a State', 'SupercheckoutCore')
                            );
                        } elseif (!$country->active) {
                            $response['error']['payment_address'][$loop_index] = array(
                                'key' => $key,
                                'error' => $this->module->l('This country is not active', 'SupercheckoutCore')
                            );
                        }
                        $postcode_error = $this->checkZipForCountry(
                            $country,
                            $posted_data['payment_address']['postcode']
                        );
                        if (isset($posted_data['payment_address']['postcode']) && $postcode_error && !empty($posted_data['shipping_address']['postcode'])) {
                            $response['error']['payment_address'][$loop_index] = $postcode_error;
                        }

                        if (($this->supercheckout_settings['payment_address']['dni'][$user_type]['require'] == 1)
                            && $country->isNeedDni() && !empty($posted_data['payment_address']['dni'])
                        ) {
                            if (isset($posted_data['payment_address']['dni'])
                                && $country->isNeedDni() && ($posted_data['payment_address']['dni'] == ''
                                || !Validate::isDniLite($posted_data['payment_address']['dni']))
                            ) {
                                $response['error']['payment_address'][$loop_index] = array(
                                    'key' => 'dni',
                                    'error' => $this->module->l('DNI Error', 'SupercheckoutCore')
                                );
                            }
                        }
                    }

                    if ($key == 'id_state' && $posted_data['payment_address'][$key] == 0) {
                        if (Country::containsStates((int) $posted_data['payment_address']['id_country'])) {
                            $response['error']['payment_address'][$loop_index] = array(
                                'key' => $key,
                                'error' => $this->module->l('Required Field', 'SupercheckoutCore')
                            );
                        }
                    }
                    if ($id_customer != 0) {
                        if ($key == 'alias' && !empty($posted_data['payment_address'][$key])) {
                            $sql = 'select * from ' . _DB_PREFIX_ . 'address
                                where id_address = ' . (int) $id_invoice_address
                                . ' AND alias = "' . pSQL($posted_data['payment_address'][$key]) . '"  AND id_customer = "'.(int)$id_customer.'"';
                            $is_alias_onsame_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!count($is_alias_onsame_id)) {
                                $is_alias_overriden = $this->aliasExistOveridden(
                                    $posted_data['payment_address'][$key],
                                    (int) $id_invoice_address,
                                    $id_customer
                                );
                                if ($is_alias_overriden) {
                                    $response['error']['payment_address'][$loop_index] = array(
                                        'key' => $key,
                                        'error' => $this->module->l('This title has already taken', 'SupercheckoutCore')
                                    );
                                }
                            }
                        }
                    }


                    /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'VAT Number Required Validation' issue */
                    if ($key == 'vat_number' && $this->isNeedVat() && empty($posted_data['payment_address'][$key])) {
                        $response['error']['payment_address'][$loop_index] = array('key' => $key,
                                    'error' => $this->module->l('Required Field', 'SupercheckoutCore'));
                    }
                    /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'VAT Number Required Validation' issue */

                    $loop_index++;
                }
            }
        }

        if (isset($response['error']) && count($response['error']) > 0) {
            return $response;
        }

        //////////////////////////End - Plugin Validations //////////////////////////

        if ((isset($posted_data['shipping_address_value']) && $posted_data['shipping_address_value'] == 1)
            || !isset($posted_data['shipping_address_value'])
        ) {
            $delivery_address = new Address($id_delivery_address);

            $delivery_address->firstname = (!empty($posted_data['shipping_address']['firstname']))
                ? $posted_data['shipping_address']['firstname'] : ' ';
            $delivery_address->lastname = (!empty($posted_data['shipping_address']['lastname']))
                ? $posted_data['shipping_address']['lastname'] : ' ';
            $delivery_address->company = (!empty($posted_data['shipping_address']['company']))
                ? $posted_data['shipping_address']['company'] : ' ';
            $delivery_address->address1 = (!empty($posted_data['shipping_address']['address1']))
                ? $posted_data['shipping_address']['address1'] : ' ';
            $delivery_address->address2 = (!empty($posted_data['shipping_address']['address2']))
                ? $posted_data['shipping_address']['address2'] : ' ';
            $delivery_address->city = (!empty($posted_data['shipping_address']['city']))
                ? $posted_data['shipping_address']['city'] : ' ';
            $delivery_address->phone = (!empty($posted_data['shipping_address']['phone']))
                ? $posted_data['shipping_address']['phone'] : ' ';
            $delivery_address->phone_mobile = (!empty($posted_data['shipping_address']['phone_mobile']))
                ? $posted_data['shipping_address']['phone_mobile'] : ' ';
            $delivery_address->id_country = (!empty($posted_data['shipping_address']['id_country']))
                ? $posted_data['shipping_address']['id_country'] : (int) Configuration::get('PS_COUNTRY_DEFAULT');
            $delivery_address->postcode = (!empty($posted_data['shipping_address']['postcode']))
                ? $posted_data['shipping_address']['postcode'] : 0;
            if (!Country::getNeedZipCode($delivery_address->id_country)) {
                $delivery_address->postcode = 0;
            }

            $delivery_address->id_state = (!empty($posted_data['shipping_address']['id_state']))
                ? $posted_data['shipping_address']['id_state'] : 0;
            if (!Country::containsStates($delivery_address->id_country)) {
                $delivery_address->id_state = 0;
            }

            $delivery_address->vat_number = (!empty($posted_data['shipping_address']['vat_number']))
                ? $posted_data['shipping_address']['vat_number'] : ' ';

            $delivery_address->dni = (!empty($posted_data['shipping_address']['dni']))
                ? $posted_data['shipping_address']['dni'] : '-';
            if (!Country::isNeedDniByCountryId($delivery_address->id_country)) {
                $delivery_address->dni = '-';
            }

            $delivery_address->alias = (isset($posted_data['shipping_address']['alias']))
                ? (empty($posted_data['shipping_address']['alias']))
                    ? $this->module->l('Address Alias', 'SupercheckoutCore') . ' - ' . date('s') . rand(0, 9)
                    : $posted_data['shipping_address']['alias']
                : $this->module->l('Address Alias', 'SupercheckoutCore') . ' - ' . date('s') . rand(0, 9);

            $delivery_address->other = (!empty($posted_data['shipping_address']['other']))
                ? $posted_data['shipping_address']['other'] : ' ';

            $delivery_address->id_customer = $id_customer;

            $validate_address = $delivery_address->validateController();
            if ($validate_address && count($validate_address) > 0) {
                $response['error']['shipping_address'] = array();
                foreach ($validate_address as $key => $value) {
                    if ($key == '0') {
                        $response['error']['shipping_address'][] = array('key' => 'vat_number', 'error' => $value);
                    } else {
                        $response['error']['shipping_address'][] = array('key' => $key, 'error' => $value);
                    }
                }
            } else {
                if (!$delivery_address->save()) {
                    $response['error']['general'][] = $this->module->l('Error occurred while creating new address', 'SupercheckoutCore');
                } else {
                    $id_delivery_address = $delivery_address->id;
                }
            }
        } elseif (isset($posted_data['shipping_address_value'])
            && $posted_data['shipping_address_value'] == 0
            && isset($posted_data['shipping_address_id'])
        ) {
            $id_delivery_address = $posted_data['shipping_address_id'];
        }

        if (isset($posted_data['use_for_invoice'])
            && ((isset($posted_data['shipping_address_value']) && $posted_data['shipping_address_value'] == 1)
            || !isset($posted_data['shipping_address_value']))
        ) {
            $invoice_address = $delivery_address;
            $id_invoice_address = $id_delivery_address;
        } elseif (isset($posted_data['use_for_invoice'])
            && isset($posted_data['shipping_address_value'])
            && $posted_data['shipping_address_value'] == 0
        ) {
            $id_invoice_address = $id_delivery_address;
        }

        if (!isset($posted_data['use_for_invoice'])
            && ((isset($posted_data['payment_address_value'])
            && $posted_data['payment_address_value'] == 1)
            || !isset($posted_data['payment_address_value']))
        ) {
            $invoice_address = new Address($id_invoice_address);
            $invoice_address->firstname = (!empty($posted_data['payment_address']['firstname']))
                ? $posted_data['payment_address']['firstname'] : ' ';
            $invoice_address->lastname = (!empty($posted_data['payment_address']['lastname']))
                ? $posted_data['payment_address']['lastname'] : ' ';
            $invoice_address->company = (!empty($posted_data['payment_address']['company']))
                ? $posted_data['payment_address']['company'] : ' ';
            $invoice_address->address1 = (!empty($posted_data['payment_address']['address1']))
                ? $posted_data['payment_address']['address1'] : ' ';
            $invoice_address->address2 = (!empty($posted_data['payment_address']['address2']))
                ? $posted_data['payment_address']['address2'] : ' ';
            $invoice_address->city = (!empty($posted_data['payment_address']['city']))
                ? $posted_data['payment_address']['city'] : ' ';
            $invoice_address->phone = (!empty($posted_data['payment_address']['phone']))
                ? $posted_data['payment_address']['phone'] : ' ';
            $invoice_address->phone_mobile = (!empty($posted_data['payment_address']['phone_mobile']))
                ? $posted_data['payment_address']['phone_mobile'] : ' ';
            $invoice_address->id_country = (!empty($posted_data['payment_address']['id_country']))
                ? $posted_data['payment_address']['id_country'] : (int) Configuration::get('PS_COUNTRY_DEFAULT');
            $invoice_address->postcode = (!empty($posted_data['payment_address']['postcode']))
                ? $posted_data['payment_address']['postcode'] : 0;

            if (!Country::getNeedZipCode($invoice_address->id_country)) {
                $invoice_address->postcode = 0;
            }
            $invoice_address->id_state = (!empty($posted_data['payment_address']['id_state']))
                ? $posted_data['payment_address']['id_state'] : 0;

            if (!Country::containsStates($invoice_address->id_country)) {
                $invoice_address->id_state = 0;
            }
            $invoice_address->vat_number = (!empty($posted_data['payment_address']['vat_number']))
                ? $posted_data['payment_address']['vat_number'] : ' ';
            $invoice_address->dni = (!empty($posted_data['payment_address']['dni']))
                ? $posted_data['payment_address']['dni'] : '-';

            if (!Country::isNeedDniByCountryId($invoice_address->id_country)) {
                $invoice_address->dni = '-';
            }
            $invoice_address->alias = (isset($posted_data['payment_address']['alias']))
                ? (empty($posted_data['payment_address']['alias']))
                    ? $this->module->l('Address Alias', 'SupercheckoutCore') . ' - ' . date('s') . rand(0, 9)
                    : $posted_data['payment_address']['alias']
                : $this->module->l('Address Alias', 'SupercheckoutCore') . ' - ' . date('s') . rand(0, 9);

            $invoice_address->other = (!empty($posted_data['payment_address']['other']))
                ? $posted_data['payment_address']['other'] : ' ';
            $invoice_address->id_customer = $id_customer;

            $validate_address = $invoice_address->validateController();
            if ($validate_address && count($validate_address) > 0) {
                $response['error']['payment_address'] = array();
                foreach ($validate_address as $key => $value) {
                    if ($key == '0') {
                        $response['error']['payment_address'][] = array('key' => 'vat_number', 'error' => $value);
                    } else {
                        $response['error']['payment_address'][] = array('key' => $key, 'error' => $value);
                    }
                }
            } else {
                if (!$invoice_address->save()) {
                    $response['error']['general'][] = $this->module->l('Error occurred while creating new address', 'SupercheckoutCore');
                } else {
                    $id_invoice_address = $invoice_address->id;
                }
            }
        } elseif (!isset($posted_data['use_for_invoice'])
            && isset($posted_data['payment_address_value'])
            && $posted_data['payment_address_value'] == 0
        ) {
            $id_invoice_address = $posted_data['payment_address_id'];
        }

        //If any Error return
        if (isset($response['error']) && count($response['error']) > 0) {
            return $response;
        }

        $customer = null;

        if (!$this->is_logged) {
            $original_password = '';
            if ($posted_data['checkout_option'] == 2) {
                $_POST['is_new_customer'] = 1;
                $_POST['passwd'] = $posted_data['customer_personal']['password'];
                $original_password = Tools::getValue('passwd');
            } else {
                $_POST['is_new_customer'] = 0;
                $_POST['passwd'] = $this->generateRandomPassword(); //uniqid(rand(), true);
                if ($this->supercheckout_settings['enable_guest_register']) {
                    $_POST['is_new_customer'] = 1;
                    $original_password = Tools::getValue('passwd');
                }
            }
            $_POST['email'] = $posted_data['supercheckout_email'];
            $_POST['id_gender'] = (isset($posted_data['customer_personal']['id_gender']))
                ? $posted_data['customer_personal']['id_gender'] : 0;

            if (empty($posted_data['shipping_address']['firstname'])
                && $this->supercheckout_settings['shipping_address']['firstname'][$user_type]['require'] == 0
            ) {
                if (isset($posted_data['payment_address']['firstname'])
                    && !empty($posted_data['payment_address']['firstname'])
                ) {
                    $_POST['customer_firstname'] = $posted_data['payment_address']['firstname'];
                } else {
                    $_POST['customer_firstname'] = ' ';
                }
            } else {
                $_POST['customer_firstname'] = (isset($posted_data['shipping_address']['firstname']))
                    ? $posted_data['shipping_address']['firstname'] : '';
            }

            if (empty($posted_data['shipping_address']['lastname'])
                && $this->supercheckout_settings['shipping_address']['lastname'][$user_type]['require'] == 0
            ) {
                if (isset($posted_data['payment_address']['lastname'])
                    && !empty($posted_data['payment_address']['lastname'])
                ) {
                    $_POST['customer_lastname'] = $posted_data['payment_address']['lastname'];
                } else {
                    $_POST['customer_lastname'] = ' ';
                }
            } else {
                $_POST['customer_lastname'] = (isset($posted_data['shipping_address']['lastname']))
                    ? $posted_data['shipping_address']['lastname'] : '';
            }

            $blocknewsletter = Module::isInstalled('blocknewsletter')
                && $module_newsletter = Module::getInstanceByName('blocknewsletter');

            if (isset($posted_data['customer_personal']['newsletter'])) {
                if ($blocknewsletter && $module_newsletter->active) {
                    $is_subscribed = $module_newsletter->isNewsletterRegistered(Tools::getValue('email'));
                    if (is_callable(array($module_newsletter, 'isNewsletterRegistered'))
                        && $is_subscribed != $module_newsletter::GUEST_REGISTERED) {
                        $_POST['newsletter'] = true;
                    }
                }
            }

            $newsletter = (isset($posted_data['customer_personal']['newsletter'])) ? 1 : 0;
            $_POST['optin'] = (isset($posted_data['customer_personal']['optin'])) ? 1 : 0;
            if ($check_dob) {
                $_POST['days'] = (isset($posted_data['customer_personal']['dob_days']))
                    ? $posted_data['customer_personal']['dob_days'] : '';
                $_POST['months'] = (isset($posted_data['customer_personal']['dob_months']))
                    ? $posted_data['customer_personal']['dob_months'] : '';
                $_POST['years'] = (isset($posted_data['customer_personal']['dob_years']))
                    ? $posted_data['customer_personal']['dob_years'] : '';
            }

            Hook::exec('actionBeforeSubmitAccount');

            $flag = false;
            if ($this->is_logged && $this->context->cookie->is_guest) {
                $customer = new Customer((int) $this->context->cookie->id_customer);
                $flag = true;
            } else {
                $customer = new Customer();
            }
            
            $customer->id_gender = Tools::getValue('id_gender');
            $customer->firstname = Tools::getValue('customer_firstname');
            $customer->lastname = Tools::getValue('customer_lastname');
            $customer->email = Tools::getValue('email');
            $customer->passwd = Tools::encrypt(Tools::getValue('passwd'));
            $customer->newsletter = $newsletter;
            $customer->optin = Tools::getValue('optin');
            $customer->secure_key = md5(uniqid(rand(), true));
            // changes by rishabh jain for recaptcha integration
            $recaptcha_verification_status = 1;
            if (!$flag || $this->context->cookie->is_guest) {
                if (Module::isInstalled('googlerecaptcha') && Module::isEnabled('googlerecaptcha')) {
                    //Testing for one page checkout is disabled and authentication is enabled
                    $values = Tools::unSerialize(Configuration::get('GOOGLE_RECAPTCHA'));
                    if (isset($values['enable']) && $values['enable'] == 1) {
                        if ($values['google_recaptcha']['check'][5] == 'on') {
                            $recaptch_type = $values['recaptcha_supercheckout'];
                            if ($recaptch_type == 'v2') {
                                if (isset($this->context->cookie->check_login_attempt)) {
                                    $gr_set = $this->context->cookie->check_login_attempt + 1;
                                    $this->context->cookie->__set('check_login_attempt', $gr_set);
                                    if ($this->context->cookie->check_login_attempt > $values['attempts']) {
                                        $recaptcha_mod_obj = Module::getInstanceByName('googlerecaptcha');
                                        $recaptcha_verification_status = $recaptcha_mod_obj->v2RecaptchaVerificationSupercheckout($values);
                                    }
                                }
                            } else {
                                $recaptcha_mod_obj = Module::getInstanceByName('googlerecaptcha');
                                $recaptcha_verification_status = $recaptcha_mod_obj->v3RecaptchaVerificationSupercheckout($values);
                            }
                            if ($recaptcha_verification_status == 1) {
                                $this->context->cookie->__set('check_login_attempt', 0);
                            } else if ($recaptcha_verification_status == 0) {
                                $response['error']['general'][] = $this->module->l('Failed to verify.Try again.');
                            } else if ($recaptcha_verification_status == 2) {
                                $response['error']['general'][] = $this->module->l('Please verify the reCAPTCHA.');
                            }
                        }
                    }
                }
                if (isset($response['error'])) {
                    return $response;
                }
            }
            // changes over
            if (isset($posted_data['customer_personal']['newsletter'])) {
                $this->processCustomerNewsletter($customer);
                if ($this->supercheckout_settings['mailchimp']['enable'] == 1) {
                    $this->addEmailToList($customer->email, $customer->firstname, $customer->lastname);
                } else if ($this->supercheckout_settings['SendinBlue']['enable'] == 1) {
                    $this->addEmailToListSendinBlue($customer->email, $customer->firstname, $customer->lastname);
                } else if ($this->supercheckout_settings['klaviyo']['enable'] == 1) {
                    $this->addEmailToListKlaviyo($customer->email, $customer->firstname, $customer->lastname);
                }
            }

            if ($check_dob) {
                $customer->birthday = (int) Tools::getValue('years') . '-'
                    . (int) Tools::getValue('months') . '-' . Tools::getValue('days');
            } else {
                $customer->birthday = '';
            }

            $customer->active = 1;

            if ($flag) {
                $customer->update(true);
            } else {
                // New Guest customer
                if (Tools::isSubmit('is_new_customer')) {
                    $customer->is_guest = !Tools::getValue('is_new_customer', 1);
                } else {
                    $customer->is_guest = 0;
                }

                if (!$customer->add()) {
                    $response['error']['general'][] = $this->module->l('An error occurred while creating your account.', 'SupercheckoutCore');
                } else {
                    $customer->cleanGroups();
                    if (!$customer->is_guest) {
                        // we add the guest customer in the default customer group
                        $customer->addGroups(array((int) Configuration::get('PS_CUSTOMER_GROUP')));

                        if (!$this->sendConfirmationMail($customer, $original_password)) {
                            $response['warning'][] = $this->module->l('An error ocurred while sending account confirmation email', 'SupercheckoutCore');
                        }
                    } else {
                        $customer->addGroups(array((int) Configuration::get('PS_GUEST_GROUP')));
                    }

                    Hook::exec('actionCustomerAccountAdd', array(
                        '_POST' => $_POST,
                        'newCustomer' => $customer
                    ));
                    $id_customer = $customer->id;
                }
            }
        } else {
            $id_customer = $this->context->customer->id;
        }

        if (!isset($response['error'])) {
            if (Validate::isLoadedObject($delivery_address) && $delivery_address != null) {
                $delivery_address->id_customer = $id_customer;
                if (!$delivery_address->save()) {
                    $response['error']['general'][] = $this->module->l('Error occurred while updating address', 'SupercheckoutCore');
                } else {
                    $id_delivery_address = $delivery_address->id;
                }
            }

            if (Validate::isLoadedObject($invoice_address) && $invoice_address != null) {
                $invoice_address->id_customer = $id_customer;
                if (!$invoice_address->save()) {
                    $response['error']['general'][] = $this->module->l('Error occurred while updating address', 'SupercheckoutCore');
                } else {
                    $id_invoice_address = $invoice_address->id;
                }
            }
        }

        if (isset($response['error'])) {
            return $response;
        }

        if (!isset($response['error'])) {
            // Add customer to the context
            if (!$this->is_logged) {
                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int) $customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $customer->logged = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;
            }

            $this->context->cart->recyclable = (isset($posted_data['recyclable'])) ? 1 : 0;
            $this->context->cart->gift = (isset($posted_data['gift'])) ? 1 : 0;
            if (isset($posted_data['gift_comment'])) {
                // changes by rishabh jain
                $this->context->cart->gift_message = strip_tags($posted_data['gift_comment']);
            } else {
                $this->context->cart->gift_message = '';
            }

            if (isset($posted_data['comment'])) {
                $this->supercheckoutUpdateMsg($posted_data['comment']);
            }

            if (Configuration::get('PS_CART_FOLLOWING')
                && (empty($this->context->cookie->id_cart)
                || Cart::getNbProducts($this->context->cookie->id_cart) == 0)
            ) {
                $this->context->cookie->id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id);
            }

            if (Tools::getIsset('delivery_option')) {
                $_POST['delivery_option'] = Tools::getValue('delivery_option');
                $this->updateCarrier();
            }

            $this->updateCartDeliveryAddress($id_current_address_delivery, $id_delivery_address);
            $this->context->cart->id_customer = (int) $id_customer;
            $this->context->cart->id_address_delivery = $id_delivery_address;
            $this->context->cart->id_address_invoice = $id_invoice_address;
            $this->context->cart->secure_key = $this->context->customer->secure_key;

            $this->context->cart->save();
            $this->context->cookie->id_cart = (int) $this->context->cart->id;
            $this->context->cookie->write();
            //As there is no multishipping, set each product delivery address with main delivery address
            $this->context->cart->setNoMultishipping();
            $this->context->cart->autosetProductAddress();
        }

        /****** Custom Fields code *********/
        // Sending data for validation
        $custom_fields_response = $this->saveCustomfieldsData($custom_field_values, $use_for_invoice);
        // If there is some error in custom fields, then the whole form is not submitted
        if (isset($custom_fields_response['error_occured']) && $custom_fields_response['error_occured'] == 1) {
            $response['error']['general'][] = $this->module->l('Please provide required information. Check all the fields in the form.', 'SupercheckoutCore');
            $response['custom_fields_errors'] = $custom_fields_response;
            return $response;
        }

        if (!isset($response['error'])) {
            $this->context->cookie->supercheckout_perm_address_delivery = $id_delivery_address;
            $this->context->cookie->supercheckout_perm_address_invoice = $id_invoice_address;
            $response['is_free_order'] = ((float) $order_total <= 0) ? true : false;
            $response['success'] = true;
        }

        return $response;
    }

    /*
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     */
    protected function validateCustomFieldsData($custom_field_values, $use_for_invoice)
    {
        $errors = array();
        $errors['error_occured'] = 0;
        $id_lang = $this->context->cookie->id_lang;
        // Each field value
        //$query = 'SELECT id_velsof_supercheckout_custom_fields FROM '._DB_PREFIX_.'velsof_supercheckout_custom_fields WHERE active = 1';
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query = $query . 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        $query = $query . 'WHERE active = 1 AND cfl.id_lang = '.(int)$id_lang;

        $result_array = Db::getInstance()->executeS($query);
        foreach ($result_array as $array_id) {
            /*
             * Start : Added by Anshul
             */
            if ($array_id['type'] == 'file') {
                continue;
            }
            /*
             * End : Added by Anshul
             */
            $id_velsof_supercheckout_custom_fields = $array_id['id_velsof_supercheckout_custom_fields'];
            // Get the validation details of the field from database
            $query = 'SELECT required, validation_type, position, type FROM '
                    . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields WHERE
				id_velsof_supercheckout_custom_fields = ' . (int)$id_velsof_supercheckout_custom_fields;
            $result_field_data = Db::getInstance()->executeS($query);
            $type = $result_field_data[0]['type'];

            $validation_type = $result_field_data[0]['validation_type'];
            $position = $result_field_data[0]['position'];
            $required = $result_field_data[0]['required'];

            // Condition for checkboxes
            // If field index in not present then the values are not provided
            $flag_read = 1;
            if ($type == 'checkbox' && !isset($custom_field_values["field_$id_velsof_supercheckout_custom_fields"]) || ($type == 'radio' && !isset($custom_field_values["field_$id_velsof_supercheckout_custom_fields"]))) {
                $flag_read = 0;
                if ($required == 1) {
                    $errors['error_occured'] = 1;
                    $errors['error']["field_$id_velsof_supercheckout_custom_fields"] = $this->module->l('Required Field', 'SupercheckoutCore');
                    return $errors;
                }
            }
            if ($flag_read == 1) {
                $field_value = $custom_field_values["field_$id_velsof_supercheckout_custom_fields"];
            }

            // Skip the validation if the checkbox is checked
            if ($use_for_invoice == 'on' && $position == 'payment_address_form') {
                $a = 1;
                unset($a);
            } else {
                if ($result_field_data[0]['required'] == 1) {
                    if (empty($field_value)) {
                        $errors['error_occured'] = 1;
                        $errors['error']["field_$id_velsof_supercheckout_custom_fields"] = $this->module->l('Required Field', 'SupercheckoutCore');
                    }
                }
                if ($validation_type != '0') {
                    $field_value = trim($field_value);
                    if (isset($field_value) && !empty($field_value) && $field_value!= "" && $validation_type != '' && !Validate::$validation_type($field_value)) { //Modified by Anshul Mittal
                        $message = '';
                        if ($validation_type == 'isInt') {
                            $message = $this->module->l('Only digits are allowed.', 'SupercheckoutCore');
                        }
                        if ($validation_type == 'isName') {
                            $message = $this->module->l('Only name allowed.', 'SupercheckoutCore');
                        }
                        if ($validation_type == 'isEmail') {
                            $message = $this->module->l('Only email allowed (example@example.com).', 'SupercheckoutCore');
                        }
                        if ($validation_type == 'isDate') {
                            $message = $this->module->l('Only date is allowed. Check prestashop isDate() for reference.', 'SupercheckoutCore');
                        }
                        if ($validation_type == 'isUrl') {
                            $message = $this->module->l('Only URL is allowed.', 'SupercheckoutCore');
                        }

                        $errors['error_occured'] = 1;
                        $errors['error']["field_$id_velsof_supercheckout_custom_fields"] = $this->module->l('Please provide data in valid format', 'SupercheckoutCore') . $message;
                    }
                }
            }
        }
        return $errors;
    }

    /*
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     */
    public function saveCustomfieldsData($custom_field_values, $use_for_invoice)
    {
        $errors = $this->validateCustomFieldsData($custom_field_values, $use_for_invoice);
        if ($errors['error_occured'] == 0) {
            $id_cart = (int) $this->context->cookie->id_cart;
            // Delete all the values from a table where cart id is same. Delets previously saved values from same cart
            if (!empty($custom_field_values)) {
                // Start: Modified by Anshul for not deleting the entries for FILES type
                $where_delete = "id_cart = " . (int) $id_cart ." and id_velsof_supercheckout_custom_fields NOT IN (Select id_velsof_supercheckout_custom_fields from "._DB_PREFIX_."velsof_supercheckout_custom_fields where type = 'file')";
                // End: Modified by Anshul for not deleting the entries for FILES type
                Db::getInstance()->delete('velsof_supercheckout_fields_data', $where_delete);
                // Each field value
                foreach ($custom_field_values as $key => $value) {
                    $id_velsof_supercheckout_custom_fields = Tools::substr($key, (strrpos($key, '_') + 1));

                    // If the value is array then serialize it and save
                    if (is_array($value)) {
                        $value = serialize($value);
                    }

                    $fields_data = array(
                        'id_velsof_supercheckout_custom_fields' => pSQL($id_velsof_supercheckout_custom_fields),
                        'id_order' => 0,
                        'id_cart' => pSQL($id_cart),
                        'field_value' => pSQL($value)
                    );
                    Db::getInstance()->insert('velsof_supercheckout_fields_data', $fields_data);
                }
            }
            return $custom_field_values;
        } else {
            return $errors;
        }
    }
}
