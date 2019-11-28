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

use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\ServiceLocator;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/OnePageCheckoutPSCore.php';
require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/FieldClass.php';
require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/FieldCustomerClass.php';
require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/FieldControl.php';
require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/FieldOptionClass.php';
require_once _PS_MODULE_DIR_.'onepagecheckoutps/classes/PaymentClass.php';

class OnePageCheckoutPS extends Module
{
    const VERSION = '4.0.5';

    public $core;
    public $errors = array();
    public $warnings = array();
    public $html = '';
    public $prefix_module;
    public $params_back;
    public $override_css;
    public $override_js;
    public $translations_path;

    protected $smarty;
    protected $cookie;

    public $onepagecheckoutps_dir;
    public $onepagecheckoutps_tpl;
    public $translation_dir;
    public $fields_to_capitalize = array(
        'firstname',
        'lastname',
        'address1',
        'address2',
        'city',
        'company',
        'postcode'
    );

    public $only_register = false;

    public $globals = array();
    public $config_vars = array();
    public $configure_vars = array(
        array('name' => 'OPC_VERSION', 'default_value' => self::VERSION, 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_OVERRIDE_CSS', 'default_value' => '', 'is_html' => true, 'is_bool' => false),
        array('name' => 'OPC_OVERRIDE_JS', 'default_value' => '', 'is_html' => true, 'is_bool' => false),
        /* general */
        array('name' => 'OPC_SHOW_DELIVERY_VIRTUAL', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_CONFIRM_ADDRESS', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array(
            'name' => 'OPC_ID_CONTENT_PAGE',
            'default_value' => '#content-wrapper #main',
            'is_html' => false,
            'is_bool' => false
        ),
        array('name' => 'OPC_DEFAULT_PAYMENT_METHOD', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_DEFAULT_GROUP_CUSTOMER', 'default_value' => 3, 'is_html' => false, 'is_bool' => false),
        array(
            'name' => 'OPC_GROUPS_CUSTOMER_ADDITIONAL',
            'default_value' => '',
            'is_html' => false,
            'is_bool' => false
        ),
        array('name' => 'OPC_ID_CUSTOMER', 'default_value' => 0, 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_VALIDATE_DNI', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REDIRECT_DIRECTLY_TO_OPC', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REPLACE_AUTH_CONTROLLER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REPLACE_IDENTITY_CONTROLLER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REPLACE_ADDRESSES_CONTROLLER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        /* register - step 1 */
        array('name' => 'OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_BUTTON_REGISTER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_USE_SAME_NAME_CONTACT_DA', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_USE_SAME_NAME_CONTACT_BA', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REQUEST_PASSWORD', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array(
            'name' => 'OPC_OPTION_AUTOGENERATE_PASSWORD',
            'default_value' => 1,
            'is_html' => false,
            'is_bool' => true
        ),
        array('name' => 'OPC_ENABLE_INVOICE_ADDRESS', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REQUIRED_INVOICE_ADDRESS', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REQUEST_CONFIRM_EMAIL', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_CHOICE_GROUP_CUSTOMER', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array(
            'name' => 'OPC_CHOICE_GROUP_CUSTOMER_ALLOW',
            'default_value' => '',
            'is_html' => false,
            'is_bool' => false
        ),
        array('name' => 'OPC_SHOW_LIST_CITIES_GEONAMES', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_AUTO_ADDRESS_GEONAMES', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_AUTOCOMPLETE_GOOGLE_ADDRESS', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SUGGESTED_ADDRESS_GOOGLE', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_GOOGLE_API_KEY', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_CAPITALIZE_FIELDS', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_INSERT_ISO_CODE_IN_INVOI_DNI', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_INSERT_ISO_CODE_IN_DELIV_DNI', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        /* shipping - step 2 */
        array('name' => 'OPC_SHIPPING_COMPATIBILITY', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_RELOAD_SHIPPING_BY_STATE', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_DESCRIPTION_CARRIER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_IMAGE_CARRIER', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_FORCE_NEED_POSTCODE', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_FORCE_NEED_CITY', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array(
            'name'          => 'OPC_MODULE_CARRIER_NEED_POSTCODE',
            'default_value' => 'envialacarrier',
            'is_html'       => false
        ),
        array(
            'name'          => 'OPC_MODULE_CARRIER_NEED_CITY',
            'default_value' => 'shippingconfiguratorpro',
            'is_html'       => false
        ),
        /* payment - step 3 */
        //array('name' => 'OPC_SHOW_POPUP_PAYMENT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        //array('name' => 'OPC_PAYMENTS_WITHOUT_RADIO', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        //array('name' => 'OPC_MODULES_WITHOUT_POPUP', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_SHOW_IMAGE_PAYMENT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_DETAIL_PAYMENT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_PAYMENT_NEED_REGISTER', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        /* review - step 4 */
        array('name' => 'OPC_SHOW_LINK_CONTINUE_SHOPPING', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_LINK_CONTINUE_SHOPPING', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_SHOW_ZOOM_IMAGE_PRODUCT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_REMOVE_LINK_PRODUCTS', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_PRODUCT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_DISCOUNT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_WRAPPING', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_SHIPPING', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_WITHOUT_TAX', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_TAX', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_TOTAL_PRICE', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array(
            'name' => 'OPC_SHOW_REMAINING_FREE_SHIPPING',
            'default_value' => 1,
            'is_html' => false,
            'is_bool' => true
        ),
        array('name' => 'OPC_ENABLE_TERMS_CONDITIONS', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_ID_CMS_TEMRS_CONDITIONS', 'default_value' => 0, 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_ENABLE_PRIVACY_POLICY', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_ID_CMS_PRIVACY_POLICY', 'default_value' => 0, 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_REQUIRE_PP_BEFORE_BUY', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_WEIGHT', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_REFERENCE', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_UNIT_PRICE', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_AVAILABILITY', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_DELIVERY_TIME', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_ENABLE_HOOK_SHOPPING_CART', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_COMPATIBILITY_REVIEW', 'default_value' => 0, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_VOUCHER_BOX', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_SHOW_ORDER_MESSAGE', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        /* theme */
        array('name' => 'OPC_THEME_BACKGROUND_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_BORDER_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_ICON_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_CONFIRM_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_CONFIRM_TEXT_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_TEXT_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_SELECTED_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_SELECTED_TEXT_COLOR', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_ALREADY_REGISTER_BUTTON', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array(
            'name' => 'OPC_ALREADY_REGISTER_BUTTON_TEXT',
            'default_value' => '',
            'is_html' => false,
            'is_bool' => false
        ),
        array('name' => 'OPC_THEME_LOGIN_BUTTON', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_LOGIN_BUTTON_TEXT', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_VOUCHER_BUTTON', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_THEME_VOUCHER_BUTTON_TEXT', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array('name' => 'OPC_BACKGROUND_BUTTON_FOOTER', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        array(
            'name' => 'OPC_THEME_BORDER_BUTTON_FOOTER',
            'default_value' => '',
            'is_html' => false,
            'is_bool' => false
        ),
        array('name' => 'OPC_CONFIRMATION_BUTTON_FLOAT', 'default_value' => 1, 'is_html' => false, 'is_bool' => true),
        /* social */
        array('name' => 'OPC_SOCIAL_NETWORKS', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
        /* debug mode */
        array('name' => 'OPC_ENABLE_DEBUG', 'default_value' => '0', 'is_html' => false, 'is_bool' => true),
        array('name' => 'OPC_IP_DEBUG', 'default_value' => '', 'is_html' => false, 'is_bool' => false),
    );

    public function __construct()
    {
        $this->prefix_module = 'OPC';
        $this->name          = 'onepagecheckoutps';
        $this->displayName   = 'One Page Checkout PrestaShop';
        $this->tab           = 'checkout';
        $this->version       = '4.0.5';
        $this->author        = 'PresTeamShop';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        $this->module_key    = 'f4b7743a760d424aca4799adef34de89';
        $this->author_address = '0x91C5d6F1c2ADb4eE96da147F3Fa4aD164F874a15';

        if (property_exists($this, 'controllers')) {
            $this->controllers = array('login');
        }

        parent::__construct();

        $this->errors      = array();
        $this->warnings    = array();
        $this->params_back = array();
        $this->globals     = new stdClass();

        $this->core = new OnePageCheckoutPSCore($this, $this->context);
        $this->smarty = $this->context->smarty;
        $this->cookie = $this->context->cookie;

        if (version_compare(_PS_VERSION_, '1.7.4', '<')) {
            if (property_exists($this->smarty, 'inheritance_merge_compiled_includes')) {
                $this->smarty->inheritance_merge_compiled_includes = false;
            }
        }

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->globals->object = (object) array(
                'customer' => 'customer',
                'delivery' => 'delivery',
                'invoice'  => 'invoice',
        );

        $this->globals->type = (object) array(
                'isAddress'     => 'string',
                'isBirthDate'   => 'string',
                'isDate'        => 'string',
                'isBool'        => 'boolean',
                'isCityName'    => 'string',
                'isDniLite'     => 'string',
                'isEmail'       => 'email',
                'isGenericName' => 'string',
                'isMessage'     => 'text',
                'isName'        => 'string',
                'isPasswd'      => 'password',
                'isPhoneNumber' => 'string',
                'isPostCode'    => 'string',
                'isVatNumber'   => 'string',
                'number'        => 'integer',
                'url'           => 'string',
                'confirmation'  => 'string',
        );

        $this->globals->theme = (object) array(
                'gray'  => 'gray',
                'blue'  => 'blue',
                'black' => 'black',
                'green' => 'green',
                'red'   => 'red',
        );

        $this->globals->lang->object = array(
            'customer' => $this->l('Customer'),
            'delivery' => $this->l('Address delivery'),
            'invoive'  => $this->l('Address invoice'),
        );

        $this->globals->lang->theme = array(
            'gray'  => $this->l('Gray'),
            'blue'  => $this->l('Blue'),
            'black' => $this->l('Black'),
            'green' => $this->l('Green'),
            'red'   => $this->l('Red'),
        );

        $this->description      = $this->l('The simplest and  fastest way to increase sales.');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall?');

        $this->onepagecheckoutps_dir = __PS_BASE_URI__.'modules/'.$this->name.'/';
        $this->onepagecheckoutps_tpl = _PS_ROOT_DIR_.'/modules/'.$this->name.'/';
        $this->translation_dir = _PS_MODULE_DIR_.$this->name.'/translations/';

        if (property_exists($this, 'controllers')) {
            $this->controllers = array('login', 'payment', 'actions');
        }

        if (Configuration::get('PS_DISABLE_OVERRIDES') == 1 && Validate::isLoadedObject($this->context->employee)) {
            $this->warnings[] = $this->l('This module does not work with the override disabled in your store. Turn off option -Disable all overrides- on -Advanced Parameters--Performance-');
        }

        //Delete fields required, this cause problem on our module.
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('DELETE FROM '._DB_PREFIX_.'required_field');

        $this->core->checkModulePTS();

        if (isset($this->context->cookie->opc_suggest_address)
            && (!$this->context->customer->isLogged()
                || ($this->context->customer->isLogged() && !isset($this->context->cookie->id_cart)))
        ) {
            unset($this->context->cookie->opc_suggest_address);
        }

        if (!function_exists('curl_init')
            && !function_exists('curl_setopt')
            && !function_exists('curl_exec')
            && !function_exists('curl_close')
        ) {
            $this->errors[] = $this->l('CURL functions not available for registration module.');
        }
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            !$this->core->install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('displayShoppingCart') ||
            !$this->registerHook('actionShopDataDuplication') ||
            !$this->registerHook('displayAdminOrder') ||
            !$this->registerHook('registerGDPRConsent') ||
            !$this->registerHook('actionDeleteGDPRCustomer') ||
            !$this->registerHook('actionObjectAddressAddAfter') ||
            !$this->registerHook('actionObjectAddressUpdateAfter') ||
            !$this->registerHook('actionObjectCustomerAddAfter') ||
            !$this->registerHook('actionObjectCustomerUpdateAfter') ||
            !$this->registerHook('actionObjectAddressDeleteAfter') ||
            !$this->registerHook('actionCustomerLogoutAfter')
            || !$this->registerHook('actionAdminCustomersFormModifier')
            || !$this->registerHook('actionAdminAddressesFormModifier')
        ) {
            return false;
        }

        $this->createCustomerOPC();

        //install field shops
        $this->installLanguageShop();

        //social network for login
        $sc_google = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
        $json_networks = array(
            'facebook' => array(
                'network'       => 'Facebook',
                'name_network'  => 'Facebook',
                'client_id'     => '',
                'client_secret' => '',
                'scope'         => 'email,public_profile',
                'class_icon'    => 'facebook-official',
                'enable'        => 0
            ),
            'google'   => array(
                'network'       => 'Google',
                'name_network'  => 'Google',
                'client_id'     => '',
                'client_secret' => '',
                'scope' => $sc_google,
                'class_icon'    => 'google',
                'enable'        => 0
            ),
            'paypal'   => array(
                'network'       => 'Paypal',
                'name_network'  => 'Paypal',
                'client_id'     => '',
                'client_secret' => '',
                'scope' => 'openid profile email address',
                'class_icon'    => 'paypal',
                'enable'        => 0
            ),
            'biocryptology'   => array(
                'network'       => 'Biocryptology',
                'name_network'  => 'Biocryptology',
                'client_id'     => '',
                'client_secret' => '',
                'scope' => 'openid+profile+email+address',
                'class_icon'    => 'biocryptology',
                'enable'        => 0
            )
        );
        Configuration::updateValue('OPC_SOCIAL_NETWORKS', Tools::jsonEncode($json_networks));

        //desactiva el tema movil
        Configuration::updateValue('PS_ALLOW_MOBILE_DEVICE', 0);

        //config default group customer
        $id_customer_group = Configuration::get('PS_CUSTOMER_GROUP');
        if (!empty($id_customer_group)) {
            Configuration::updateValue('OPC_DEFAULT_GROUP_CUSTOMER', $id_customer_group);
        }

        $id_country_default = Configuration::get('PS_COUNTRY_DEFAULT');

        //update default country
        $sql_country = 'UPDATE '._DB_PREFIX_.'opc_field_shop fs';
        $sql_country .= ' INNER JOIN '._DB_PREFIX_.'opc_field f ON f.id_field = fs.id_field';
        $sql_country .= ' SET fs.default_value = \''.(int)$id_country_default.'\'';
        $sql_country .= ' WHERE f.name = \'id_country\'';
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_country);

        //update state default
        $country = new Country($id_country_default);
        if (Validate::isLoadedObject($country) && $country->contains_states) {
            $states = State::getStatesByIdCountry($id_country_default);

            if (count($states)) {
                $id_state = $states[0]['id_state'];

                if (!empty($id_state)) {
                    $sql_state = 'UPDATE '._DB_PREFIX_.'opc_field_shop fs';
                    $sql_state .= ' INNER JOIN '._DB_PREFIX_.'opc_field f ON f.id_field = fs.id_field';
                    $sql_state .= ' SET fs.default_value = \''.(int)$id_state.'\'';
                    $sql_state .= ' WHERE f.name = \'id_state\'';
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_state);
                }
            }
        }

        //remove class_cache.php
        $file_class_cache = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR. 'app'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.(_PS_MODE_DEV_ ? 'dev' : 'prod').DIRECTORY_SEPARATOR.'class_index.php';
        if (file_exists($file_class_cache)) {
            unlink($file_class_cache);
        }

        //remove hook displayOverrideTemplate, else our module dont show.
        if ($ps_legalcompliance = $this->core->isModuleActive('ps_legalcompliance')) {
            $ps_legalcompliance->unregisterHook('displayOverrideTemplate');
        }

        //support module: m4gdpr - v1.2.1 - PrestaAddons
        if ($this->core->isModuleActive('m4gdpr')) {
            //Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("INSERT INTO `"._DB_PREFIX_."m4_gdpr_location` (`id_location`, `name`, `file_name`, `selector`) VALUES (99, 'Module: One Page Checkout PS', 'order-opc', 'onepagecheckoutps')");
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("INSERT INTO `"._DB_PREFIX_."m4_gdpr_location` (`id_location`, `name`, `file_name`, `selector`, `event`) VALUES (99, 'Module: One Page Checkout PS', 'order-opc', 'onepagecheckoutps', 'submit')");
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("INSERT INTO `"._DB_PREFIX_."m4_gdpr_location_consent` (`id_location_consent`, `id_location`, `id_consent`, `position`, `required`) VALUES (NULL, 99, 2, 0, 1), (NULL, 99, 1, 1, 1)");
        }

        return true;
    }

    public function uninstall()
    {
        $query = 'DELETE FROM `'._DB_PREFIX_.'customer` WHERE id_customer = '.(int)$this->config_vars['OPC_ID_CUSTOMER'];
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
        $query = 'DELETE FROM `'._DB_PREFIX_.'customer_group` WHERE id_customer = '.(int)$this->config_vars['OPC_ID_CUSTOMER'];
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);

        if (!parent::uninstall() || !$this->core->uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $forms = $this->getHelperForm();
        if (is_array($forms)
            && count($forms)
            && isset($forms['forms'])
            && is_array($forms['forms'])
            && count($forms['forms'])
        ) {
            foreach ($forms['forms'] as $key => $form) {
                if (Tools::isSubmit('form-'.$key)) {
                    $this->smarty->assign('CURRENT_FORM', $key);
                    //save form data in configuration
                    $this->core->saveFormData($form);
                    //show message
                    $this->smarty->assign('show_saved_message', true);
                    break;
                }
            }
        }
        //removeIf(addons)
        $this->core->getContent();
        //endRemoveIf(addons)

        $this->displayForm();

        return $this->html;
    }

    public function validValuesConfigVars($id_cms_privacy_policy, &$warn_acceptance)
    {
        if (!method_exists('CMS', 'getCMSContent')) {
            return false;
        }

        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            if (empty(CMS::getCMSContent($id_cms_privacy_policy, $lang['id_lang'])['content'])) {
                $warn_acceptance[] = $lang['name'];
            }
        }

        if (count($warn_acceptance) > 0) {
            return false;
        }
        return true;
    }

    public function initBeforeControllerOPC($controller)
    {
        if (Tools::getIsset('rc_page') && !$this->context->customer->isLogged() && !Tools::getIsset('is_ajax')) {
            Tools::redirect($this->context->link->getPageLink('my-account'));
        }

        $controller->display_column_right = false;
        $controller->display_column_left  = false;
    }

    public function initAfterControllerOPC($controller)
    {
        $controller = $controller;

        $this->context->smarty->assign('link', $this->context->link);

        //Soporte al modulo Email Verification OPC - PresTeamShop
        $emailverificationopc = $this->core->isModuleActive('emailverificationopc');
        if ($emailverificationopc) {
            if (isset($this->context->cookie->check_account)) {
                $require_email_verified = Configuration::get('EVOPC_REQUIRE_VERIFY_EMAIL');

                if ($require_email_verified) {
                    $url = $this->context->link->getModuleLink('emailverificationopc', 'verifyemail', array(
                        'token' => Tools::encrypt('emailverificationopc/index'),
                        'check_account' => $this->context->cookie->check_account
                    ));
                    Tools::redirect($url);
                }
            }
        }

        if ($this->context->cart->nbProducts() > 0) {
            if (!$this->context->customer->isLogged()) {
                if (empty($this->context->cart->id_address_delivery) && empty($this->context->cart->id_address_invoice)) {
                    $object = 'delivery';
                    if ($this->context->cart->isVirtualCart() && !$this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL']) {
                        $object = 'invoice';
                    }

                    $id_address = $this->getIdAddressAvailable($object);

                    $this->context->cart->id_address_delivery = $id_address;
                    $this->context->cart->id_address_invoice = $id_address;
                    $this->context->cart->update();
                }
            } else {
                $id_address = 0;

                if (!$this->context->customer->getAddresses($this->context->cookie->id_lang)) {
                    if (empty($this->context->cart->id_address_delivery)
                        && empty($this->context->cart->id_address_invoice)
                        && ($this->context->cart->isVirtualCart() && !$this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL'])
                        && (($this->config_vars['OPC_ENABLE_INVOICE_ADDRESS']
                            && !$this->config_vars['OPC_REQUIRED_INVOICE_ADDRESS'])
                            || !$this->config_vars['OPC_ENABLE_INVOICE_ADDRESS']
                        )
                    ) {
                        $id_address = $this->getIdAddressAvailable('invoice');
                    }

                    $this->context->cart->id_address_delivery = $id_address;
                    $this->context->cart->id_address_invoice = $id_address;
                    $this->context->cart->update();
                }
            }
        }

        //En ocaciones los productos quedaban con el id_address_delivery del OPC dando problemas
        //asi que mejor ejecutar esta funcion cada vez que se cargue el OPC para evitar problemas.
        $this->context->cart->setNoMultishipping();
    }

    public function initContentControllerOPC($controller)
    {
        $is_need_invoice = false;
        $this->only_register = $this->supportModulesRequiredAutentication();

        $opc_fields_position = $this->getFieldsFront($is_need_invoice);

        $this->context->smarty->assign(array(
            'OPC_GLOBALS'     => $this->globals,
            'OPC_FIELDS'      => $opc_fields_position,
            'is_need_invoice' => $is_need_invoice,
            'rc_page'         => $this->context->controller->php_self
        ));
        Media::addJsDef(array('is_need_invoice' => $is_need_invoice));

        $this->addModulesExtraFee();

        $templateVars = $this->getTemplateVarsOPC();

        $this->context->smarty->assign($templateVars);
        Media::addJsDef($templateVars);

        $this->loadCarrier($controller);

        if (file_exists(_PS_THEME_DIR_.'modules/'.$this->name.'/views/templates/front/'.$this->name.'.tpl')) {
            $controller->setTemplate('../../../themes/'._THEME_NAME_.'/modules/'.$this->name.'/views/templates/front/'.$this->name);
        } else {
            $controller->setTemplate('../../../modules/'.$this->name.'/views/templates/front/'.$this->name);
        }
    }

    public function initContentRegisterControllerOPC($controller, $rc_page)
    {
        $is_need_invoice     = false;
        $this->only_register = true;

        $opc_fields_position = $this->getFieldsFront($is_need_invoice);

        $this->context->smarty->assign(array(
            'OPC_GLOBALS'     => $this->globals,
            'OPC_FIELDS'      => $opc_fields_position,
            'is_need_invoice' => $is_need_invoice,
            'rc_page'         => $rc_page
        ));
        Media::addJsDef(array('is_need_invoice' => $is_need_invoice));

        $templateVars = $this->getTemplateVarsOPC();
        $this->context->smarty->assign($templateVars);

        Media::addJsDef($templateVars);

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/onepagecheckoutps.tpl')) {
            $controller->setTemplate('../../../themes/'._THEME_NAME_.'/modules/'.$this->name.'/views/templates/front/'.$this->name);
        } else {
            $controller->setTemplate('../../../modules/'.$this->name.'/views/templates/front/'.$this->name);
        }
    }

    public function postProcessControllerOPC($controller)
    {
        if (Tools::getIsset('is_ajax')) {
            $data_type = 'json';
            if (Tools::isSubmit('dataType')) {
                $data_type = Tools::getValue('dataType');
            }

            $action = Tools::getValue('action');
            if (method_exists($controller, $action)) {
                switch ($data_type) {
                    case 'html':
                        die($controller->$action());
                    case 'json':
                        $response = Tools::jsonEncode($controller->$action());
                        die($response);
                    default:
                        die('Invalid data type.');
                }
            } elseif (method_exists($this, $action)) {
                switch ($data_type) {
                    case 'html':
                        die($this->$action($controller));
                    case 'json':
                        $response = Tools::jsonEncode($this->$action($controller));
                        die($response);
                    default:
                        die('Invalid data type.');
                }
            } else {
                switch ($action) {
                    case 'updateExtraCarrier':
                        // Change virtualy the currents delivery options
                        $delivery_option = $this->context->cart->getDeliveryOption();
                        $delivery_option[(int) Tools::getValue('id_address')] = Tools::getValue('id_delivery_option');
                        $this->context->cart->setDeliveryOption($delivery_option);
                        $this->context->cart->save();
                        $return = array(
                            'content' => Hook::exec(
                                'displayCarrierList',
                                array(
                                    'address' => new Address((int) Tools::getValue('id_address'))
                                )
                            )
                        );
                        die(Tools::jsonEncode($return));
                    case 'checkRegisteredCustomerEmail':
                        $id_customer = 0;
                        if ($this->config_vars['OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED']) {
                            $id_customer = (int)Customer::customerExists(Tools::getValue('email'), true);
                        }
                        die(Tools::jsonEncode($id_customer));
                }
            }
        }
    }

    public function updateCarrierControllerOPC($controller)
    {
        $controller = $controller;

        if ($this->core->isModuleActive('ps_googleanalytics')) {
            if (Tools::strpos($this->context->cookie->ga_cart, 'addCheckoutOption')) {
                unset($this->context->cookie->ga_cart);
            }
        }
    }

    public function saveCustomConfigValue($option, &$config_var_value)
    {
        $warn_acceptance = array();

        switch ($option['name']) {
            case 'redirect_directly_to_opc':
                if (Tools::getIsset('enable_guest_checkout')) {
                    Configuration::updateValue('PS_GUEST_CHECKOUT_ENABLED', 1);
                } else {
                    Configuration::updateValue('PS_GUEST_CHECKOUT_ENABLED', 0);
                }
                break;
            case 'default_payment_method':
                $default_carrier = Tools::getValue('default_carrier');
                Configuration::updateValue('PS_CARRIER_DEFAULT', $default_carrier);
                break;
            case 'autocomplete_google_address':
                $google_api_key = Tools::getValue('google_api_key');
                if (empty($google_api_key)) {
                    $config_var_value = 0;
                }
                break;
            case 'id_cms_temrs_conditions':
                if (Tools::getIsset('enable_terms_conditions')) {
                    Configuration::updateValue('PS_CONDITIONS_CMS_ID', $config_var_value);
                }
                break;
            case 'enable_terms_conditions':
                if ($config_var_value) {
                    $config_var_value = (int) $this->validValuesConfigVars($this->config_vars['OPC_ID_CMS_TEMRS_CONDITIONS'], $warn_acceptance);

                    if (count($warn_acceptance) > 0) {
                        $this->warnings[] = sprintf(
                            $this->l('The CMS of the "Require acceptance of terms of conditions before buying" option, in the "Review" tab does not have content for the languages: %s'),
                            implode(', ', $warn_acceptance)
                        );
                    }
                }
                Configuration::updateValue('PS_CONDITIONS', $config_var_value);
                break;
            case 'enable_privacy_policy':
                if ($config_var_value) {
                    $config_var_value = (int) $this->validValuesConfigVars($this->config_vars['OPC_ID_CMS_PRIVACY_POLICY'], $warn_acceptance);

                    if (count($warn_acceptance) > 0) {
                        $this->warnings[] = sprintf(
                            $this->l('The CMS of the "Require acceptance of policy privacy" option, in the "Register" tab does not have content for the languages: %s'),
                            implode(', ', $warn_acceptance)
                        );
                    }
                }
                break;
            case 'id_customer':
                $id_customer    = (int)Tools::getValue('id_customer');
                $customer       = new Customer($id_customer);

                if (!Validate::isLoadedObject($customer)) {
                    $config_var_value = $this->createCustomerOPC();
                }
                break;
        }
    }

    protected function displayForm()
    {
        $this->core->getContentUpgrade();
        if (!empty($this->html)) {
            return false;
        }

        $js_files  = array();
        $css_files = array();

        //own bootstrap
        if ($this->context->language->is_rtl) {
            array_push($css_files, $this->_path.'views/css/lib/pts/pts-bootstrap_rtl.css');
        }

        //sortable
        array_push($js_files, $this->_path.'views/js/lib/jquery/plugins/sortable/jquery-sortable.js');
        array_push($css_files, $this->_path.'views/css/lib/jquery/plugins/sortable/jquery-sortable.css');

        //fileinput
        array_push($js_files, $this->_path.'views/js/lib/bootstrap/plugins/fileinput/bootstrap-fileinput.js');
        array_push($css_files, $this->_path.'views/css/lib/bootstrap/plugins/fileinput/bootstrap-fileinput.css');

        //color picker
        array_push($js_files, $this->_path.'views/js/lib/bootstrap/plugins/colorpicker/bootstrap-colorpicker.js');
        array_push($css_files, $this->_path.'views/css/lib/bootstrap/plugins/colorpicker/bootstrap-colorpicker.css');

        //tab drop
        array_push($js_files, $this->_path.'views/js/lib/bootstrap/plugins/tabdrop/tabdrop.js');
        array_push($css_files, $this->_path.'views/css/lib/bootstrap/plugins/tabdrop/tabdrop.css');

        //totalStorage
        array_push($js_files, $this->_path.'views/js/lib/jquery/plugins/total-storage/jquery.total-storage.min.js');

        //chart
        array_push($js_files, $this->_path.'views/js/lib/jquery/plugins/chart/chart.min.js');

        $carriers = Carrier::getCarriers(Configuration::get('PS_LANG_DEFAULT'), true, false, false, null, 5);
        $payments = $this->getPaymentModulesInstalled();

        $field_position = $this->getFieldsPosition();

        $default_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $languages        = Language::getLanguages(false);

        //ids lang
        $lang_separator = utf8_encode(chr(164));
        $ids_flag       = array('field_description', 'option_field_description', 'custom_field_description');
        $ids_flag       = join($lang_separator, $ids_flag);
        $iso            = Language::getIsoById((int) Configuration::get('PS_LANG_DEFAULT'));

        $server_name = Tools::strtolower($_SERVER['SERVER_NAME']);
        $server_name = str_ireplace('www.', '', $server_name);

        $helper_form = $this->getHelperForm();

        //extra tabs for PresTeamShop
        $this->getExtraTabs($helper_form);

        $social_login   = Tools::jsonDecode($this->config_vars['OPC_SOCIAL_NETWORKS']);
        $social_data    = $this->getSocialData($social_login);

        //Asignacion de varibles a tpl de administracion.
        $this->params_back = array(
            'MODULE_PREFIX'                        => $this->prefix_module,
            'DEFAULT_LENGUAGE'                     => $default_language,
            'LANGUAGES'                            => $languages,
            'ISO_LANG'                             => $iso,
            'FLAGS_FIELD_DESCRIPTION'              => $this->displayFlags(
                $languages,
                $default_language,
                $ids_flag,
                'field_description',
                true
            ),
            'FLAGS_CUSTOM_FIELD_DESCRIPTION'       => $this->displayFlags(
                $languages,
                $default_language,
                $ids_flag,
                'custom_field_description',
                true
            ),
            'FLAGS_OPTION_FIELD_DESCRIPTION'       => $this->displayFlags(
                $languages,
                $default_language,
                $ids_flag,
                'option_field_description',
                true
            ),
            'STATIC_TOKEN'                         => Tools::getAdminTokenLite('AdminModules'),
            'HELPER_FORM'                          => $helper_form,
            'JS_FILES'                             => $js_files,
            'CSS_FILES'                            => $css_files,
            'CARRIERS'                             => $carriers,
            'PAYMENTS'                             => $payments,
            'FIELDS_POSITION'                      => $field_position,
            'GLOBALS_JS'                           => Tools::jsonEncode($this->globals),
            'GROUPS_CUSTOMER'                      => Group::getGroups($this->cookie->id_lang),
            'DISPLAY_NAME'                         => $this->displayName,
            'CMS'                                  => CMS::listCms($this->cookie->id_lang),
            'SOCIAL_LOGIN'                         => $social_login,
            'SHOP'                                 => $this->context->shop,
            'LINK'                                 => $this->context->link,
            'SHOP_PROTOCOL'                        => Tools::getShopProtocol(),
            'CRON_LINK'                            => $this->context->link->getModuleLink($this->name, 'cron', array('token' => Tools::encrypt($this->name.'/index'))),
            'array_label_translate'                => $this->core->getTranslations(),
            'id_lang'                              => $this->context->language->id,
            'iso_lang_backoffice_shop'             => Language::getIsoById($this->context->employee->id_lang),
            'code_editors'                         => $this->core->codeEditors(),
            'remote_addr'   => Tools::getRemoteAddr(),
            'SOCIAL_DATA'   => $social_data
        );

        $this->core->displayForm();

        $this->smarty->assign('paramsBack', $this->params_back);

        $this->html .= $this->display(__FILE__, 'views/templates/admin/header.tpl');
        $this->html .= $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    private function installLanguageShop($shops = array())
    {
        if (empty($shops)) {
            $shops = Shop::getShops();
            $shops = array_keys($shops);
        } elseif (is_array($shops)) {
            $shops = array_values($shops);
        } else {
            $shops = array($shops);
        }

        $sql_shops = Tools::file_get_contents(dirname(__FILE__).'/sql/shop.sql');
        if ($sql_shops) {
            $sql_shops = str_replace('PREFIX_', _DB_PREFIX_, $sql_shops);
            foreach ($shops as $id_shop) {
                $sql_shop = str_replace('ID_SHOP', $id_shop, $sql_shops);
                $sql_shop = preg_split("/;\s*[\r\n]+/", $sql_shop);

                foreach ($sql_shop as $query_shop) {
                    if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(trim($query_shop))) {
                        return false;
                    }
                }
            }
        }

        //install languages
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $iso_code = 'en';
            if (file_exists(dirname(__FILE__).'/translations/sql/'.$lang['iso_code'].'.sql')) {
                $iso_code = $lang['iso_code'];
            }

            $sql_langs = Tools::file_get_contents(dirname(__FILE__).'/translations/sql/'.$iso_code.'.sql');
            if ($sql_langs) {
                $sql_lang = str_replace('PREFIX_', _DB_PREFIX_, $sql_langs);
                $sql_lang = str_replace('ID_LANG', $lang['id_lang'], $sql_lang);
                foreach ($shops as $id_shop) {
                    $sql_lang_shop = str_replace('ID_SHOP', $id_shop, $sql_lang);
                    $sql_lang_shop = preg_split("/;\s*[\r\n]+/", $sql_lang_shop);

                    foreach ($sql_lang_shop as $query_lang_shop) {
                        if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(trim($query_lang_shop))) {
                            return false;
                        }
                    }
                }
            }
        }
    }

    private function createCustomerOPC()
    {
        $query_cs = new DbQuery();
        $query_cs->select('id_customer');
        $query_cs->from('customer');
        $query_cs->where('email = "'.pSQL('noreply@'.$this->context->shop->domain).'"');

        $id_customer = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query_cs);

        if (!$id_customer) {
            $date = date('Y-m-d H:i:s');
            $values = array(
                'firstname' => 'OPC PTS Not Delete',
                'lastname' => 'OPC PTS Not Delete',
                'email' => pSQL('noreply@'.$this->context->shop->domain),
                'passwd' => Tools::encrypt('OPC123456'),
                'id_shop' => (int)Context::getContext()->shop->id,
                'id_shop_group' => (int)Context::getContext()->shop->id_shop_group,
                'id_default_group' => (int)Configuration::get('PS_CUSTOMER_GROUP'),
                'id_lang' => (int)Context::getContext()->language->id,
                'birthday' => '0000-00-00',
                'secure_key' => md5(uniqid(rand(), true)),
                'date_add' => $date,
                'date_upd' => $date,
                'active' => 0,
                'deleted' => 1
            );

            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('customer', $values);

            $id_customer = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->Insert_ID();
        }

        if (empty($id_customer)) {
            return false;
        }

        $query_csg = new DbQuery();
        $query_csg->from('customer_group');
        $query_csg->where('id_customer = '.(int)$id_customer);
        $result_csg = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query_csg);

        if (!$result_csg) {
            $values = array(
                'id_customer' => (int)$id_customer,
                'id_group' => (int)Configuration::get('PS_CUSTOMER_GROUP')
            );

            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('customer_group', $values);
        }

        Configuration::updateValue('OPC_ID_CUSTOMER', (int)$id_customer);

        return $id_customer;
    }

    /**
     * Extra tabs for PresTeamShop
     * @param type $helper_form
     */
    private function getExtraTabs(&$helper_form)
    {
        $helper_form['tabs']['translate'] = array(
            'label'   => $this->l('Translate'),
            'href'    => 'translate',
            'icon'    => 'globe'
        );

        $helper_form['tabs']['code_editors'] = array(
            'label'   => $this->l('Code Editors'),
            'href'    => 'code_editors',
            'icon'    => 'code'
        );

        $helper_form['tabs']['another_modules'] = array(
            'label' => $this->l('Another modules'),
            'href'  => 'another_modules',
            'icon'  => 'cubes',
        );

        //removeIf(addons)
        $helper_form['tabs']['addons'] = array(
            'label' => $this->l('Addons'),
            'href' => 'addons',
            'icon' => 'puzzle-piece'
        );

        $protocol = 'http://';
        if (Configuration::get('PS_SSL_ENABLED')) {
            $protocol = 'https://';
        }

        $addons_json = Tools::file_get_contents($protocol.'www.presteamshop.com/products_presteamshop_opc.json');
        $addons = Tools::jsonDecode($addons_json);

        if (is_array($addons) && !empty($addons)) {
            foreach ($addons as &$addon) {
                if (Module::isInstalled($addon->name)) {
                    $addon->active = true;
                } else {
                    $addon->active = false;
                }
            }

            $this->smarty->assign('ADDONS', $addons);
        }

        $carousel_json = Tools::file_get_contents($protocol.'www.presteamshop.com/products_presteamshop.json');
        $carousel      = Tools::jsonDecode($carousel_json);
        foreach ($carousel as &$module) {
            if (Module::isInstalled($module->name)) {
                $module->active = true;
            } else {
                $module->active = false;
            }
        }

        $this->smarty->assign('ANOTHER_MODULES', $carousel);
        //endRemoveIf(addons)

        $helper_form['tabs']['suggestions'] = array(
            'label'   => $this->l('Suggestions'),
            'href'    => 'suggestions',
            'icon'    => 'pencil'
        );
    }

    /**
     * Get position of fields
     * @return type array with positions in "group, row, col" order.
     */
    public function getFieldsPosition()
    {
        //get fields
        $fields = FieldClass::getAllFields((int) $this->cookie->id_lang);

        $position = array();
        foreach ($fields as $field) {
            $position[$field->group][$field->row][$field->col] = $field;
        }

        return $position;
    }

    private function getGeneralForm()
    {
        $payment_methods = array(array('id_module' => '', 'name' => '--'));
        $payment_methods_ori = PaymentModule::getInstalledPaymentModules();
        foreach ($payment_methods_ori as $payment) {
            $payment_methods[] = $payment;
        }

        $carriers = array_merge(
            array(
                -1 => array('id_carrier' => -1, 'name' => $this->l('Best price')),
                -2 => array('id_carrier' => -2, 'name' => $this->l('Best grade'))
            ),
            Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::ALL_CARRIERS)
        );

        $options = array(
            'enable_debug' => array(
                'name' => 'enable_debug',
                'prefix' => 'chk',
                'label' => $this->l('Sandbox'),
                'type' => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_ENABLE_DEBUG'],
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'depends' => array(
                    'ip_debug' => array(
                        'name' => 'ip_debug',
                        'prefix' => 'txt',
                        'label' => $this->l('IP'),
                        'type' => $this->globals->type_control->textbox,
                        'value' => $this->config_vars['OPC_IP_DEBUG'],
                        'hidden_on' => false
                    )
                ),
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('It is recommended that you enable this option to test the module in your store before enabling it for clients.'),
                    )
                )
            ),
            'enable_guest_checkout' => array(
                'name'     => 'enable_guest_checkout',
                'prefix'   => 'chk',
                'label'    => $this->l('Enable guest checkout'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
            ),
            'redirect_directly_to_opc'   => array(
                'name'     => 'redirect_directly_to_opc',
                'prefix'   => 'chk',
                'label'    => $this->l('Redirect directly to page checkout'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REDIRECT_DIRECTLY_TO_OPC'],
            ),
            'replace_auth_controller' => array(
                'name'     => 'replace_auth_controller',
                'prefix'   => 'chk',
                'label'    => $this->l('Replace the registration form with the checkout form'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REPLACE_AUTH_CONTROLLER'],
            ),
            'replace_addresses_controller' => array(
                'name'     => 'replace_addresses_controller',
                'prefix'   => 'chk',
                'label'    => $this->l('Show checkout registration form at: My addresses'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REPLACE_ADDRESSES_CONTROLLER'],
            ),
            'replace_identity_controller' => array(
                'name'     => 'replace_identity_controller',
                'prefix'   => 'chk',
                'label'    => $this->l('Show checkout registration form at: My personal information'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REPLACE_IDENTITY_CONTROLLER'],
            ),
            'show_delivery_virtual'      => array(
                'name'     => 'show_delivery_virtual',
                'prefix'   => 'chk',
                'label'    => $this->l('Show the delivery address for the purchase of virtual goods'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL'],
            ),
            'confirm_address' => array(
                'name'     => 'confirm_address',
                'prefix'   => 'chk',
                'label'    => $this->l('Confirm delivery address before checkout'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_CONFIRM_ADDRESS'],
            ),
            'default_payment_method'     => array(
                'name'           => 'default_payment_method',
                'prefix'         => 'lst',
                'label'          => $this->l('Choose a default payment method'),
                'type'           => $this->globals->type_control->select,
                'data'           => $payment_methods,
                'default_option' => $this->config_vars['OPC_DEFAULT_PAYMENT_METHOD'],
                'option_value'   => 'name',
                'option_text'    => 'name'
            ),
            'default_carrier'    => array(
                'name'           => 'default_carrier',
                'prefix'         => 'lst',
                'label'          => $this->l('Choose a default carrier'),
                'type'           => $this->globals->type_control->select,
                'data'           => $carriers,
                'default_option' => Configuration::get('PS_CARRIER_DEFAULT'),
                'option_value'   => 'id_carrier',
                'option_text'    => 'name'
            ),
            'default_group_customer'     => array(
                'name'           => 'default_group_customer',
                'prefix'         => 'lst',
                'label'          => $this->l('Add new customers to the group'),
                'type'           => $this->globals->type_control->select,
                'data'           => Group::getGroups($this->cookie->id_lang),
                'default_option' => $this->config_vars['OPC_DEFAULT_GROUP_CUSTOMER'],
                'option_value'   => 'id_group',
                'option_text'    => 'name',
            ),
            'groups_customer_additional' => array(
                'name'             => 'groups_customer_additional',
                'prefix'           => 'lst',
                'label'            => $this->l('Add new customers in other groups'),
                'type'             => $this->globals->type_control->select,
                'multiple'         => true,
                'data'             => Group::getGroups($this->cookie->id_lang),
                'selected_options' => $this->config_vars['OPC_GROUPS_CUSTOMER_ADDITIONAL'],
                'option_value'     => 'id_group',
                'option_text'      => 'name',
                'condition'        => array(
                    'compare'  => $this->config_vars['OPC_DEFAULT_GROUP_CUSTOMER'],
                    'operator' => 'neq',
                    'value'    => 'id_group',
                ),
            ),
            'validate_dni'               => array(
                'name'     => 'validate_dni',
                'prefix'   => 'chk',
                'label'    => $this->l('Validate identification Spain and Chile'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_VALIDATE_DNI'],
            ),
            'id_content_page'            => array(
                'name'   => 'id_content_page',
                'prefix' => 'txt',
                'label'  => $this->l('Container page (HTML)'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_ID_CONTENT_PAGE'],
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('It is recommended to leave this field with the default settings, unless your template has a different identifier and must be changed.'),
                    )
                )
            ),
            'id_customer'                => array(
                'name'    => 'id_customer',
                'prefix'  => 'txt',
                'label'   => $this->l('Customer ID'),
                'type'    => $this->globals->type_control->textbox,
                'value'   => $this->config_vars['OPC_ID_CUSTOMER'],
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('Do not change unless you understand its functionality.'),
                    )
                )
            )
        );

        $form = array(
            'tab'     => 'general',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options
        );

        return $form;
    }

    private function getRegisterForm()
    {
        $options = array(
            'required_login_customer_registered' => array(
                'name'     => 'required_login_customer_registered',
                'prefix'   => 'chk',
                'label'    => $this->l('Require login to a registered customer'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED'],
            ),
            'show_button_register' => array(
                'name'     => 'show_button_register',
                'prefix'   => 'chk',
                'label'    => $this->l('Show button "Save Information"'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_SHOW_BUTTON_REGISTER'],
            ),
            'capitalize_fields' => array(
                'name'     => 'capitalize_fields',
                'prefix'   => 'chk',
                'label'    => $this->l('Capitalize fields'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_CAPITALIZE_FIELDS'],
                'tooltip' => array(
                    'warning' => array(
                        'title' => $this->l('Info'),
                        'content' => $this->l('This option only applies to the fields: First name, Last name, Address, City, Company and Postal Code. Not applicable for custom fields.')
                    )
                )
            ),
            'enable_privacy_policy' => array(
                'name'     => 'enable_privacy_policy',
                'prefix'   => 'chk',
                'label'    => $this->l('Enable privacy policies'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_ENABLE_PRIVACY_POLICY'],
                'depends'  => array(
                    'require_pp_before_buy' => array(
                        'name' => 'require_pp_before_buy',
                        'prefix' => 'chk',
                        'label' => $this->l('Require before buying'),
                        'label_on' => $this->l('YES'),
                        'label_off' => $this->l('NO'),
                        'hidden_on' => false,
                        'type' => $this->globals->type_control->checkbox,
                        'check_on' => $this->config_vars['OPC_REQUIRE_PP_BEFORE_BUY']
                    ),
                    'id_cms_privacy_policy' => array(
                        'name'           => 'id_cms_privacy_policy',
                        'prefix'         => 'lst',
                        'type'           => $this->globals->type_control->select,
                        'data'           => CMS::listCms($this->cookie->id_lang),
                        'default_option' => $this->config_vars['OPC_ID_CMS_PRIVACY_POLICY'],
                        'hidden_on'      => false,
                        'option_value'   => 'id_cms',
                        'option_text'    => 'meta_title',
                    ),
                )
            ),
            'enable_invoice_address'      => array(
                'name'        => 'enable_invoice_address',
                'prefix'      => 'chk',
                'label'       => $this->l('Request invoice address'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_ENABLE_INVOICE_ADDRESS'],
                'data_toggle' => true,
                'depends'     => array(
                    'required_invoice_address' => array(
                        'name'      => 'required_invoice_address',
                        'prefix'    => 'chk',
                        'label'     => $this->l('Required'),
                        'label_on' => $this->l('YES'),
                        'label_off' => $this->l('NO'),
                        'type'      => $this->globals->type_control->checkbox,
                        'check_on'  => $this->config_vars['OPC_REQUIRED_INVOICE_ADDRESS'],
                        'hidden_on' => false,
                    ),
                    'use_same_name_contact_ba' => array(
                        'name'      => 'use_same_name_contact_ba',
                        'prefix'    => 'chk',
                        'label' => $this->l('Use the same first name and last name for the customers invoice address'),
                        'label_on' => $this->l('YES'),
                        'label_off' => $this->l('NO'),
                        'type'      => $this->globals->type_control->checkbox,
                        'check_on'  => $this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA'],
                        'hidden_on' => false,
                    ),
                    'insert_iso_code_in_invoi_dni' => array(
                        'name'      => 'insert_iso_code_in_invoi_dni',
                        'prefix'    => 'chk',
                        'label'     => $this->l('Automatically insert the country ISO code at the beginning of the DNI in the invoice address'),
                        'label_on' => $this->l('YES'),
                        'label_off' => $this->l('NO'),
                        'type'      => $this->globals->type_control->checkbox,
                        'check_on'  => $this->config_vars['OPC_INSERT_ISO_CODE_IN_INVOI_DNI'],
                        'hidden_on' => false,
                    ),
                ),
            ),
            'insert_iso_code_in_deliv_dni' => array(
                'name'      => 'insert_iso_code_in_deliv_dni',
                'prefix'    => 'chk',
                'label'     => $this->l('Automatically insert the country ISO code at the beginning of the DNI in the delivery address'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_INSERT_ISO_CODE_IN_DELIV_DNI'],
                'hidden_on' => false,
            ),
            'use_same_name_contact_da'    => array(
                'name'     => 'use_same_name_contact_da',
                'prefix'   => 'chk',
                'label'    => $this->l('Use the same first name and last name for the customers delivery address'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA'],
            ),
            'request_confirm_email'       => array(
                'name'     => 'request_confirm_email',
                'prefix'   => 'chk',
                'label'    => $this->l('Request confirmation email'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REQUEST_CONFIRM_EMAIL'],
            ),
            'request_password'            => array(
                'name'     => 'request_password',
                'prefix'   => 'chk',
                'label'    => $this->l('Password request'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_REQUEST_PASSWORD'],
                'tooltip' => array(
                    'warning' => array(
                        'title' => $this->l('Info'),
                        'content' => $this->l('If the - Password request - option is not active, the customer password will be created automatically')
                    )
                )
            ),
            'choice_group_customer'       => array(
                'name'     => 'choice_group_customer',
                'prefix'   => 'chk',
                'label'    => $this->l('Show customer group list'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_CHOICE_GROUP_CUSTOMER'],
                'depends'  => array(
                    'choice_group_customer_allow' => array(
                        'name'             => 'choice_group_customer_allow',
                        'prefix'           => 'lst',
                        'hidden_on'        => false,
                        'type'             => $this->globals->type_control->select,
                        'multiple'         => true,
                        'data'             => Group::getGroups($this->cookie->id_lang),
                        'selected_options' => $this->config_vars['OPC_CHOICE_GROUP_CUSTOMER_ALLOW'],
                        'option_value'     => 'id_group',
                        'option_text'      => 'name',
                        'tooltip'          => array(
                            'warning' => array(
                                'title'   => $this->l('Warning'),
                                'content' => $this->l('If you choose a group then only the selected groups will be shown, otherwise all groups will be shown.'),
                            ),
                        ),
                    ),
                ),
            ),
            'show_list_cities_geonames' => array(
                'name'     => 'show_list_cities_geonames',
                'prefix'   => 'chk',
                'label'    => $this->l('Show list of cities using Geonames.org'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('This option does not work when the Shipping Configurator Pro module is installed and active'),
                    ),
                ),
                'check_on' => $this->config_vars['OPC_SHOW_LIST_CITIES_GEONAMES'],
            ),
            'auto_address_geonames' => array(
                'name'     => 'auto_address_geonames',
                'prefix'   => 'chk',
                'label'    => $this->l('Use address autocomplete from Geonames.org'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_AUTO_ADDRESS_GEONAMES'],
            ),
            'autocomplete_google_address' => array(
                'name'        => 'autocomplete_google_address',
                'prefix'      => 'chk',
                'label'       => $this->l('Use address autocomplete from Google'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_AUTOCOMPLETE_GOOGLE_ADDRESS'],
                'data_toggle' => true,
                'depends'     => array(
                    'google_api_key' => array(
                        'name'      => 'google_api_key',
                        'prefix'    => 'txt',
                        'label'     => $this->l('Google API KEY'),
                        'type'      => $this->globals->type_control->textbox,
                        'value'     => $this->config_vars['OPC_GOOGLE_API_KEY'],
                        'hidden_on' => false,
                    ),
                    'suggested_address_google' => array(
                        'name'      => 'suggested_address_google',
                        'prefix'    => 'chk',
                        'label'     => $this->l('Autocomplete the address field with the Google Maps suggestion'),
                        'label_on' => $this->l('YES'),
                        'label_off' => $this->l('NO'),
                        'type'      => $this->globals->type_control->checkbox,
                        'check_on'  => $this->config_vars['OPC_SUGGESTED_ADDRESS_GOOGLE'],
                        'hidden_on' => false,
                        'class'     => 'suggested_address_google',
                    )
                ),
            ),
        );

        $enable_guest_checkout = Configuration::get('PS_GUEST_CHECKOUT_ENABLED');

        if (!$enable_guest_checkout) {
            $options['request_password']['depends'] = array(
                'option_autogenerate_password' => array(
                    'name'      => 'option_autogenerate_password',
                    'prefix'    => 'chk',
                    'label'     => $this->l('Option to auto-generate'),
                    'label_on' => $this->l('YES'),
                    'label_off' => $this->l('NO'),
                    'type'      => $this->globals->type_control->checkbox,
                    'check_on'  => $this->config_vars['OPC_OPTION_AUTOGENERATE_PASSWORD'],
                    'hidden_on' => false,
                    'class'     => 'option_autogenerate_password',
                ),
            );
        }

        $form = array(
            'tab'     => 'register',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-register',
                    'icon'  => 'save',
                ),
                'delete_address' => array(
                    'label' => $this->l('Delete empty addresses'),
                    'class' => 'delete-address',
                    'icon'  => 'trash',
                )
            ),
            'options' => $options,
        );

        return $form;
    }

    private function getShippingForm()
    {
        $options = array(
            'shipping_compatibility'       => array(
                'name'      => 'shipping_compatibility',
                'prefix'    => 'chk',
                'label'     => $this->l('Enable compatibility for shipping modules'),
                'label_on'  => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHIPPING_COMPATIBILITY'],
                'tooltip'   => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('Enable this option only if a shipping module is not working properly. This adds a small extra step.'),
                    )
                )
            ),
            'show_description_carrier' => array(
                'name'     => 'show_description_carrier',
                'prefix'   => 'chk',
                'label'    => $this->l('Show description of carriers'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_SHOW_DESCRIPTION_CARRIER'],
            ),
            'show_image_carrier'       => array(
                'name'     => 'show_image_carrier',
                'prefix'   => 'chk',
                'label'    => $this->l('Show image of carriers'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_SHOW_IMAGE_CARRIER'],
            ),
            'reload_shipping_by_state' => array(
                'name'     => 'reload_shipping_by_state',
                'prefix'   => 'chk',
                'label'    => $this->l('Reload shipping when changing state'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_RELOAD_SHIPPING_BY_STATE'],
            ),
            'force_need_postcode'      => array(
                'name'        => 'force_need_postcode',
                'prefix'      => 'chk',
                'label'       => $this->l('Require a postal code to be entered'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_FORCE_NEED_POSTCODE'],
                'data_toggle' => true
            ),
            'module_carrier_need_postcode' => array(
                'name'      => 'module_carrier_need_postcode',
                'prefix'    => 'txt',
                'label'     => $this->l('Carrier module that requires a postal code'),
                'type'      => $this->globals->type_control->textbox,
                'value'     => $this->config_vars['OPC_MODULE_CARRIER_NEED_POSTCODE'],
                'hidden_on' => $this->config_vars['OPC_FORCE_NEED_POSTCODE'],
            ),
            'force_need_city'          => array(
                'name'        => 'force_need_city',
                'prefix'      => 'chk',
                'label'       => $this->l('Require a city to be entered'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_FORCE_NEED_CITY'],
                'data_toggle' => true
            ),
            'module_carrier_need_city' => array(
                'name'      => 'module_carrier_need_city',
                'prefix'    => 'txt',
                'label'     => $this->l('Carrier module that requires a city'),
                'type'      => $this->globals->type_control->textbox,
                'value'     => $this->config_vars['OPC_MODULE_CARRIER_NEED_CITY'],
                'hidden_on' => $this->config_vars['OPC_FORCE_NEED_CITY'],
            )
        );

        $form = array(
            'tab'     => 'shipping',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-shipping',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }

    private function getPaymentForm()
    {
        $options = array(
            'show_image_payment' => array(
                'name'        => 'show_image_payment',
                'prefix'      => 'chk',
                'label'       => $this->l('Show images of payment methods'),
                'label_on'    => $this->l('YES'),
                'label_off'   => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_IMAGE_PAYMENT']
            ),
            'show_detail_payment' => array(
                'name'        => 'show_detail_payment',
                'prefix'      => 'chk',
                'label'       => $this->l('Show detailed description of payment methods'),
                'label_on'    => $this->l('YES'),
                'label_off'   => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_DETAIL_PAYMENT'],
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('After deactivating this option, the payment methods that contain forms to request information will be affected.'),
                    ),
                )
            )
        );

        $form = array(
            'tab'     => 'payment_general',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-payment',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }

    private function getReviewForm()
    {
        $options = array(
            'enable_terms_conditions'      => array(
                'name'     => 'enable_terms_conditions',
                'prefix'   => 'chk',
                'label'    => $this->l('Require acceptance of terms and conditions before buying'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => $this->config_vars['OPC_ENABLE_TERMS_CONDITIONS'],
                'depends'  => array(
                    'id_cms_temrs_conditions' => array(
                        'name'           => 'id_cms_temrs_conditions',
                        'prefix'         => 'lst',
                        'type'           => $this->globals->type_control->select,
                        'data'           => CMS::listCms($this->cookie->id_lang),
                        'default_option' => $this->config_vars['OPC_ID_CMS_TEMRS_CONDITIONS'],
                        'hidden_on'      => false,
                        'option_value'   => 'id_cms',
                        'option_text'    => 'meta_title',
                    ),
                ),
            ),
            'show_link_continue_shopping'  => array(
                'name'        => 'show_link_continue_shopping',
                'prefix'      => 'chk',
                'label'       => $this->l('Show "Continue Shopping" link'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_LINK_CONTINUE_SHOPPING'],
                'data_toggle' => true,
                'depends'  => array(
                    'link_continue_shopping' => array(
                        'name'      => 'link_continue_shopping',
                        'prefix'    => 'txt',
                        'label'     => $this->l('Custom URL for the "Continue shopping" button'),
                        'type'      => $this->globals->type_control->textbox,
                        'value'     => $this->config_vars['OPC_LINK_CONTINUE_SHOPPING'],
                        'hidden_on'   => false,
                        'data_hide' => 'show_link_continue_shopping'
                    )
                )
            ),
            'show_voucher_box' => array(
                'name'        => 'show_voucher_box',
                'prefix'      => 'chk',
                'label'       => $this->l('Show voucher box'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_VOUCHER_BOX'],
                'hidden_on'   => true,
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('So have enabled this option, you must have discounts created to be shown.'),
                    ),
                )
            ),
            'show_order_message' => array(
                'name'        => 'show_order_message',
                'prefix'      => 'chk',
                'label'       => $this->l('Show text box for order message'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_ORDER_MESSAGE'],
                'hidden_on'   => true
            ),
            'remove_link_products' => array(
                'name'        => 'remove_link_products',
                'prefix'      => 'chk',
                'label'       => $this->l('Remove the link from the products'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_REMOVE_LINK_PRODUCTS'],
                'hidden_on'   => true
            ),
            'show_zoom_image_product' => array(
                'name'        => 'show_zoom_image_product',
                'prefix'      => 'chk',
                'label'       => $this->l('Show zoom on image product'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_SHOW_ZOOM_IMAGE_PRODUCT'],
                'hidden_on'   => true
            ),
            'show_total_product'           => array(
                'name'      => 'show_total_product',
                'prefix'    => 'chk',
                'label'     => $this->l('Show subtotal of products'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_PRODUCT'],
                'hidden_on'   => true
            ),
            'show_total_discount'          => array(
                'name'      => 'show_total_discount',
                'prefix'    => 'chk',
                'label'     => $this->l('Show total discount'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_DISCOUNT'],
                'hidden_on'   => true
            ),
            'show_total_wrapping'          => array(
                'name'      => 'show_total_wrapping',
                'prefix'    => 'chk',
                'label'     => $this->l('Show gift wrapping total'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_WRAPPING'],
                'data_hide' => 'compatibility_review',
                'hidden_on'   => true
            ),
            'show_total_shipping'          => array(
                'name'      => 'show_total_shipping',
                'prefix'    => 'chk',
                'label'     => $this->l('Show shipping total'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_SHIPPING'],
                'hidden_on'   => true
            ),
            'show_total_without_tax'       => array(
                'name'      => 'show_total_without_tax',
                'prefix'    => 'chk',
                'label'     => $this->l('Show total excluding tax'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_WITHOUT_TAX'],
                'hidden_on'   => true
            ),
            'show_total_tax'               => array(
                'name'      => 'show_total_tax',
                'prefix'    => 'chk',
                'label'     => $this->l('Show total tax'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_TAX'],
                'hidden_on'   => true
            ),
            'show_total_price'             => array(
                'name'      => 'show_total_price',
                'prefix'    => 'chk',
                'label'     => $this->l('Show total'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_TOTAL_PRICE'],
                'data_hide' => 'compatibility_review',
                'hidden_on'   => true
            ),
            'show_remaining_free_shipping' => array(
                'name'      => 'show_remaining_free_shipping',
                'prefix'    => 'chk',
                'label'     => $this->l('Show amount remaining to qualify for free shipping'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_REMAINING_FREE_SHIPPING'],
                'hidden_on'   => true
            ),
            'show_weight'                  => array(
                'name'      => 'show_weight',
                'prefix'    => 'chk',
                'label'     => $this->l('Show weight'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_WEIGHT'],
                'hidden_on'   => true
            ),
            'show_reference'               => array(
                'name'      => 'show_reference',
                'prefix'    => 'chk',
                'label'     => $this->l('Show reference'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_REFERENCE'],
                'data_hide' => 'compatibility_review',
                'hidden_on'   => true
            ),
            'show_unit_price' => array(
                'name'      => 'show_unit_price',
                'prefix'    => 'chk',
                'label'     => $this->l('Show unit price'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_UNIT_PRICE'],
                'hidden_on'   => true
            ),
            'show_availability' => array(
                'name'      => 'show_availability',
                'prefix'    => 'chk',
                'label'     => $this->l('Show availability'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_AVAILABILITY'],
                'hidden_on'   => true,
                'tooltip' => array(
                    'warning' => array(
                        'title'   => $this->l('Warning'),
                        'content' => $this->l('This option only works when the Enable stock management option is active in Shop parameters - Product settings'),
                    ),
                )
            ),
            'show_delivery_time' => array(
                'name'      => 'show_delivery_time',
                'prefix'    => 'chk',
                'label'     => $this->l('Show delivery time of the product'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_SHOW_DELIVERY_TIME'],
                'hidden_on'   => true
            ),
            'enable_hook_shopping_cart'    => array(
                'name'      => 'enable_hook_shopping_cart',
                'prefix'    => 'chk',
                'label'     => $this->l('Enable hook shopping cart'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'      => $this->globals->type_control->checkbox,
                'check_on'  => $this->config_vars['OPC_ENABLE_HOOK_SHOPPING_CART'],
                'hidden_on'   => true
            )
            /*'compatibility_review'         => array(
                'name'        => 'compatibility_review',
                'prefix'      => 'chk',
                'label'       => $this->l('Show compatibility summary'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_COMPATIBILITY_REVIEW'],
                'data_toggle' => true,
                'depends'     => array(
                )
            )*/
        );

        $form = array(
            'tab'     => 'review',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-review',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }

    private function getThemeForm()
    {
        $options = array(
            'theme_background_color'   => array(
                'name'   => 'theme_background_color',
                'prefix' => 'txt',
                'label'  => $this->l('Background color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_BACKGROUND_COLOR'],
                'color'  => true
            ),
            'theme_border_color'       => array(
                'name'   => 'theme_border_color',
                'prefix' => 'txt',
                'label'  => $this->l('Border color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_BORDER_COLOR'],
                'color'  => true
            ),
            'theme_icon_color'         => array(
                'name'   => 'theme_icon_color',
                'prefix' => 'txt',
                'label'  => $this->l('Color of images'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_ICON_COLOR'],
                'color'  => true
            ),
            'theme_text_color'         => array(
                'name'   => 'theme_text_color',
                'prefix' => 'txt',
                'label'  => $this->l('Text color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_TEXT_COLOR'],
                'color'  => true
            ),
            'theme_selected_color' => array(
                'name'   => 'theme_selected_color',
                'prefix' => 'txt',
                'label'  => $this->l('Background color of the selected blocks'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_SELECTED_COLOR'],
                'color'  => true
            ),
            'theme_selected_text_color' => array(
                'name'   => 'theme_selected_text_color',
                'prefix' => 'txt',
                'label'  => $this->l('Text color of the selected blocks'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_SELECTED_TEXT_COLOR'],
                'color'  => true
            ),
            'theme_confirm_color'      => array(
                'name'   => 'theme_confirm_color',
                'prefix' => 'txt',
                'label'  => $this->l('Checkout button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_CONFIRM_COLOR'],
                'color'  => true
            ),
            'theme_confirm_text_color' => array(
                'name'   => 'theme_confirm_text_color',
                'prefix' => 'txt',
                'label'  => $this->l('Text color of checkout button'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_CONFIRM_TEXT_COLOR'],
                'color'  => true
            ),
            'already_register_button' => array(
                'name'   => 'already_register_button',
                'prefix' => 'txt',
                'label'  => $this->l('Already register button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_ALREADY_REGISTER_BUTTON'],
                'color'  => true
            ),
            'already_register_button_text' => array(
                'name'   => 'already_register_button_text',
                'prefix' => 'txt',
                'label'  => $this->l('Already register text button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_ALREADY_REGISTER_BUTTON_TEXT'],
                'color'  => true
            ),
            'theme_login_button' => array(
                'name'   => 'theme_login_button',
                'prefix' => 'txt',
                'label'  => $this->l('Login button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_LOGIN_BUTTON'],
                'color'  => true
            ),
            'theme_login_button_text' => array(
                'name'   => 'theme_login_button_text',
                'prefix' => 'txt',
                'label'  => $this->l('Login text button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_LOGIN_BUTTON_TEXT'],
                'color'  => true
            ),
            'theme_voucher_button' => array(
                'name'   => 'theme_voucher_button',
                'prefix' => 'txt',
                'label'  => $this->l('Voucher button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_VOUCHER_BUTTON'],
                'color'  => true
            ),
            'theme_voucher_button_text' => array(
                'name'   => 'theme_voucher_button_text',
                'prefix' => 'txt',
                'label'  => $this->l('Voucher text button color'),
                'type'   => $this->globals->type_control->textbox,
                'value'  => $this->config_vars['OPC_THEME_VOUCHER_BUTTON_TEXT'],
                'color'  => true
            ),
            'confirmation_button_float' => array(
                'name'        => 'confirmation_button_float',
                'prefix'      => 'chk',
                'label'       => $this->l('Show confirmation button float'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'        => $this->globals->type_control->checkbox,
                'check_on'    => $this->config_vars['OPC_CONFIRMATION_BUTTON_FLOAT'],
                'data_toggle' => true,
                'depends'  => array(
                    'background_button_footer' => array(
                        'name'   => 'background_button_footer',
                        'prefix' => 'txt',
                        'label'  => $this->l('Background color float confirmation button'),
                        'type'   => $this->globals->type_control->textbox,
                        'value'  => $this->config_vars['OPC_BACKGROUND_BUTTON_FOOTER'],
                        'color'  => true,
                        'hidden_on' => false,
                        'data_hide' => 'confirmation_button_float'
                    ),
                    'theme_border_button_footer' => array(
                        'name'   => 'theme_border_button_footer',
                        'prefix' => 'txt',
                        'label'  => $this->l('Border color float confirmation button'),
                        'type'   => $this->globals->type_control->textbox,
                        'value'  => $this->config_vars['OPC_THEME_BORDER_BUTTON_FOOTER'],
                        'color'  => true,
                        'hidden_on' => false,
                        'data_hide' => 'confirmation_button_float'
                    )
                )
            ),
        );

        $form = array(
            'tab'     => 'theme',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'class' => 'save-theme',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }

    private function getRequiredFieldsForm()
    {
        $options = array(
            'field_id'            => array(
                'name'   => 'id_field',
                'prefix' => 'hdn',
                'type'   => 'hidden',
            ),
            'field_object'        => array(
                'name'   => 'field_object',
                'prefix' => 'lst',
                'label'  => $this->l('Object'),
                'type'   => $this->globals->type_control->select,
                'data'   => $this->globals->object,
            ),
            'field_name'          => array(
                'name'   => 'field_name',
                'prefix' => 'txt',
                'label'  => $this->l('Name'),
                'type'   => $this->globals->type_control->textbox,
            ),
            'field_description'   => array(
                'name'      => 'field_description',
                'prefix'    => 'txt',
                'label'     => $this->l('Description'),
                'type'      => $this->globals->type_control->textbox,
                'multilang' => true,
            ),
            'field_label'   => array(
                'name'      => 'field_label',
                'prefix'    => 'txt',
                'label'     => $this->l('Label'),
                'type'      => $this->globals->type_control->textbox,
                'multilang' => true
            ),
            'field_type'          => array(
                'name'         => 'field_type',
                'prefix'       => 'lst',
                'label'        => $this->l('Type'),
                'type'         => $this->globals->type_control->select,
                'data'         => $this->globals->type,
                'key_as_value' => true,
            ),
            'field_size'          => array(
                'name'   => 'field_size',
                'prefix' => 'txt',
                'label'  => $this->l('Size'),
                'type'   => $this->globals->type_control->textbox,
            ),
            'field_type_control'  => array(
                'name'   => 'field_type_control',
                'prefix' => 'lst',
                'label'  => $this->l('Type control'),
                'type'   => $this->globals->type_control->select,
                'data'   => $this->globals->type_control,
            ),
            'field_default_value' => array(
                'name'   => 'field_default_value',
                'prefix' => 'txt',
                'label'  => $this->l('Default value'),
                'type'   => $this->globals->type_control->textbox,
            ),
            'field_required'      => array(
                'name'     => 'field_required',
                'prefix'   => 'chk',
                'label'    => $this->l('Required'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => true,
            ),
            'field_active'        => array(
                'name'     => 'field_active',
                'prefix'   => 'chk',
                'label'    => $this->l('Active'),
                'label_on' => $this->l('YES'),
                'label_off' => $this->l('NO'),
                'type'     => $this->globals->type_control->checkbox,
                'check_on' => true,
            ),
        );

        $list = $this->getRequiredFieldList();

        $form = array(
            'id'      => 'form_required_fields',
            'tab'     => 'required_fields',
            'class'   => 'hidden',
            'modal'   => true,
            'actions' => array(
                'save' => array(
                    'label' => $this->l('Save'),
                    'name'  => 'update_field',
                    'icon'  => 'save',
                )
            ),
            'options' => $options,
            'list'    => $list,
        );

        return $form;
    }

    private function getSocialSubTabs()
    {
        $social_networks = Tools::jsonDecode($this->config_vars['OPC_SOCIAL_NETWORKS']);
        $sub_tabs        = array();

        if ($social_networks) {
            foreach ($social_networks as $name => $social_network) {
                $sub_tabs[] = array(
                    'label' => $social_network->name_network,
                    'href'  => 'social_login_'.$name,
                    'icon'  => $social_network->class_icon,
                );
            }
        }

        $sub_tabs[] = array(
            'label' => $this->l('Statistics'),
            'href'  => 'statistics',
            'icon'  => 'line-chart'
        );

        return $sub_tabs;
    }

    private function getHelperTabs()
    {
        $tabs = array(
            'general'         => array(
                'label' => $this->l('General'),
                'href'  => 'general',
            ),
            'register'        => array(
                'label' => $this->l('Register'),
                'href'  => 'register',
                'icon'  => 'user',
            ),
            'shipping'        => array(
                'label' => $this->l('Shipping'),
                'href'  => 'shipping',
                'icon'  => 'truck',
            ),
            'payment'         => array(
                'label'   => $this->l('Payment'),
                'href'    => 'payment',
                'icon'    => 'credit-card',
                'sub_tab' => array(
                    'payment_general' => array(
                        'label' => $this->l('General'),
                        'href'  => 'payment_general',
                        'icon'  => 'cogs',
                    ),
                    'pay_methods'  => array(
                        'label' => $this->l('Pay methods'),
                        'href'  => 'pay_methods',
                        'icon'  => 'credit-card',
                    ),
                    'ship_pay'     => array(
                        'label' => $this->l('Ship to Pay'),
                        'href'  => 'ship_pay',
                        'icon'  => 'truck',
                    ),
                ),
            ),
            'review'          => array(
                'label' => $this->l('Review'),
                'href'  => 'review',
                'icon'  => 'check',
            ),
            'theme'           => array(
                'label' => $this->l('Theme'),
                'href'  => 'theme',
                'icon'  => 'paint-brush',
            ),
            'required_fields' => array(
                'label' => $this->l('Fields register'),
                'href'  => 'required_fields',
                'icon'  => 'pencil-square-o',
            ),
            'fields_position' => array(
                'label' => $this->l('Fields position'),
                'href'  => 'fields_position',
                'icon'  => 'arrows',
            ),
            'social_login'    => array(
                'label'   => $this->l('Social login'),
                'href'    => 'social_login',
                'icon'    => 'share-alt',
                'sub_tab' => $this->getSocialSubTabs(),
            ),
            'information'    => array(
                'label'   => $this->l('Information'),
                'href'    => 'information',
                'icon'    => 'info'
            )
        );

        return $tabs;
    }

    private function getHelperForm()
    {
        $tabs = $this->getHelperTabs();

        $general       = $this->getGeneralForm();
        $register      = $this->getRegisterForm();
        $shipping      = $this->getShippingForm();
        $payment       = $this->getPaymentForm();
        $review        = $this->getReviewForm();
        $theme         = $this->getThemeForm();

        $fields_register = $this->getRequiredFieldsForm();
        $form            = array(
            'title' => $this->l('Menu'),
            'tabs'  => $tabs,
            'forms' => array(
                'general'         => $general,
                'register'        => $register,
                'shipping'        => $shipping,
                'payment_general' => $payment,
                'review'          => $review,
                'theme'           => $theme,
                'fields_register' => $fields_register,
            ),
        );

        return $form;
    }

    private function getPaymentModulesInstalled()
    {
        //get payments
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT DISTINCT m.`id_module`, h.`id_hook`, m.`name`, hm.`position`
			FROM `'._DB_PREFIX_.'module` m
			LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON (hm.`id_module` = m.`id_module`
                AND hm.id_shop='.(int) $this->context->shop->id.')
            LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
			INNER JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module`
                AND ms.id_shop='.(int) $this->context->shop->id.')
            WHERE h.`name` = "PaymentOptions"
		');

        if ($result) {
            foreach ($result as &$row) {
                $row['force_display'] = 0;
                $row['name_image'] = $row['name'].'.gif';
                $row['test_mode'] = 0;
                $row['test_ip'] = '';

                $id_payment = PaymentClass::getIdPaymentBy('name', $row['name']);

                if (!empty($id_payment)) {
                    $payment = new PaymentClass($id_payment);
                    if (Validate::isLoadedObject($payment)) {
                        $row['data']['title']       = $payment->title;
                        $row['data']['description'] = $payment->description;

                        $path_image = dirname(__FILE__).'/views/img/payments/'.$payment->name_image;
                        if (!empty($payment->name_image) && file_exists($path_image)) {
                            $row['name_image'] = $payment->name_image;
                        }

                        $row['force_display'] = $payment->force_display;
                        $row['test_mode'] = $payment->test_mode;
                        $row['test_ip'] = $payment->test_ip;

                        $payment->id_module = $row['id_module'];
                        $payment->update();
                    }
                }
            }
        } else {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('TRUNCATE TABLE '._DB_PREFIX_.'opc_payment');
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('TRUNCATE TABLE '._DB_PREFIX_.'opc_payment_lang');
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('TRUNCATE TABLE '._DB_PREFIX_.'opc_payment_shop');
        }

        return $result;
    }

    public function getTotalConnections($network = null)
    {
        $sql = new DbQuery();
        $sql->select('count(id)');
        $sql->from('opc_social_network');

        if (!is_null($network)) {
            $sql->where('network = \''.$network.'\'');
        }

        return (int)Db::getInstance()->getValue($sql);
    }

    public function getSocialData($social_login)
    {
        $colors = array(
            'facebook'  => '#5C7FC9',
            'google'    => '#DC3C2A',
            'paypal'    => '#0195DA',
            'biocryptology' => '#DCDCDA'
        );

        $social_data = array(
            'labels'    => array(),
            'data'      => array(),
            'backgroundColor'   => array(),
            'total_connections' => $this->getTotalConnections()
        );

        if (!$social_data['total_connections']) {
            return array();
        }

        if ($social_login) {
            foreach ($social_login as $key => $data) {
                $social_data['labels'][$key]    = $data->network;
                $social_data['data'][$key]      = $this->getTotalConnections($key);
                $social_data['backgroundColor'][$key] = $colors[$key];
            }
        }

        return $social_data;
    }

    public function saveSocialLogin()
    {
        $data            = Tools::getValue('data');
        $social_networks = Tools::jsonDecode($this->config_vars['OPC_SOCIAL_NETWORKS']);

        foreach ($data['values'] as $key => $value) {
            $social_networks->{$data['social_network']}->{$key} = trim($value);
        }

        Configuration::updateValue('OPC_SOCIAL_NETWORKS', Tools::jsonEncode($social_networks));

        return array(
            'message_code' => $this->core->CODE_SUCCESS,
            'message'      => $this->l('Social login data updated successful')
        );
    }

    public function getOptionsByField()
    {
        $id_field = Tools::getValue('id_field');
        $options  = FieldOptionClass::getOptionsByIdField($id_field);
        //return result
        return array('message_code' => $this->core->CODE_SUCCESS, 'options' => $options);
    }

    public function saveOptionsByField()
    {
        $id_field = Tools::getValue('id_field');
        $options  = Tools::getValue('options');

        if (!empty($options)) {
            foreach ($options as $option) {
                if (empty($option['id_option']) || (int) $option['id_option'] === 0) {
                    $option['id_option'] = null;
                }

                $field_option = new FieldOptionClass($option['id_option']);

                $is_description_value_empty = false;
                $description_value = array();
                foreach ($option['description'] as $description) {
                    if (empty($description['value'])
                        && $description['id_lang'] == Configuration::get('PS_LANG_DEFAULT')
                    ) {
                        $is_description_value_empty = true;
                    }
                    $description_value[$description['id_lang']] = $description['value'];
                }

                $field_option->id_field    = $id_field;
                $field_option->value       = $option['value'];
                $field_option->description = $description_value;

                if (!$is_description_value_empty) {
                    $field_option->save();
                } else {
                    return array(
                        'message_code' => $this->core->CODE_ERROR,
                        'message' => $this->l('The options must have a description at least in the default language.')
                    );
                }
            }
        }

        $options_to_remove = Tools::getValue('options_to_remove');
        if (!empty($options_to_remove)) {
            foreach ($options_to_remove as $option_to_remove) {
                $field_option = new FieldOptionClass($option_to_remove);
                $field_option->delete();
            }
        }

        //return result
        return array('message_code' => $this->core->CODE_SUCCESS, 'message' => $this->l('Options updated successful.'));
    }

    public function getFieldsByObject()
    {
        $object_name = Tools::getValue('object');
        $fields_db   = FieldClass::getAllFields(
            $this->cookie->id_lang,
            $this->context->shop->id,
            $object_name,
            null,
            null,
            null,
            null,
            true
        );
        $fields = array();
        foreach ($fields_db as $field) {
            $fields[] = array(
                'id_field'    => $field->id,
                'name'        => $field->name,
                'description' => $field->description,
            );
        }
        //return result
        return array('message_code' => $this->core->CODE_SUCCESS, 'fields' => $fields);
    }

    /**
     * Save field positions
     */
    public function saveFieldsPosition()
    {
        //update positions
        $positions = Tools::getValue('positions');
        if (is_array($positions) && count($positions)) {
            foreach ($positions as $row => $cols) {
                if (is_array($cols) && count($cols)) {
                    foreach ($cols as $col => $data) {
                        $field        = new FieldClass($data['id_field']);
                        $field->group = $data['group'];
                        $field->row   = $row;
                        $field->col   = $col;
                        $field->save();
                    }
                }
            }
        }
        //return result
        return array('message_code' => $this->core->CODE_SUCCESS, 'message' => $this->l('Positions updated successful.'));
    }

    /**
     * Toggle required fieldstatus.
     * @return type array
     */
    public function toggleActiveField()
    {
        if (Tools::isSubmit('id_field')) {
            $field_class = new FieldClass((int) Tools::getValue('id_field'));

            if (Validate::isLoadedObject($field_class)) {
                $field_class->active = !$field_class->active;

                if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $field_class->setFieldsToUpdate(array('active' => 1));
                }

                if ($field_class->update()) {
                    return array(
                        'message_code' => $this->core->CODE_SUCCESS,
                        'message'      => $this->l('Field updated successful.'),
                    );
                }
            }
        }

        return array(
            'message_code' => $this->core->CODE_ERROR,
            'message'      => $this->l('An error occurred while trying to update.')
        );
    }

    /**
     * Toggle required fieldstatus.
     * @return type array
     */
    public function toggleRequiredField()
    {
        if (Tools::isSubmit('id_field')) {
            $field_class = new FieldClass((int) Tools::getValue('id_field'));

            if (Validate::isLoadedObject($field_class)) {
                $field_class->required = !$field_class->required;

                if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $field_class->setFieldsToUpdate(array('required' => 1));
                }

                if ($field_class->update()) {
                    return array(
                        'message_code' => $this->core->CODE_SUCCESS,
                        'message'      => $this->l('Field updated successful.'),
                    );
                }
            }
        }

        return array(
            'message_code' => $this->core->CODE_ERROR,
            'message'      => $this->l('An error occurred while trying to update.')
        );
    }

    /**
     * Remove associations of shipment and payment, then will create again from data form.
     * @return type array
     */
    public function updateShipToPay()
    {
        if (!Tools::isSubmit('payment_carrier')) {
            return array(
                'message_code' => $this->core->CODE_ERROR,
                'message'      => $this->l('An error occurred while trying to update.')
            );
        }

        $carriers = Tools::getValue('payment_carrier');

        //Reset table asociations
        $query  = 'DELETE FROM '._DB_PREFIX_.'module_carrier WHERE id_shop = '.(int)$this->context->shop->id;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);

        //Create new asociations from form
        $error = false;
        if ($result) {
            foreach ($carriers as $carrier) {
                if (isset($carrier['payments']) && is_array($carrier['payments']) && count($carrier['payments'])) {
                    foreach ($carrier['payments'] as $id_module) {
                        $values = array(
                            'id_reference'  => (int)$carrier['id_reference'],
                            'id_module'     => (int)$id_module,
                            'id_shop'       => (int)$this->context->shop->id,
                        );

                        if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('module_carrier', $values)) {
                            $error = true;
                        }
                    }
                }
            }
        }

        if (!$error) {
            return array(
                'message_code' => $this->core->CODE_SUCCESS,
                'message'      => $this->l('The associations are updated correctly.')
            );
        } else {
            return array(
                'message_code' => $this->core->CODE_ERROR,
                'message'      => $this->l('An error occurred while trying to update.')
            );
        }
    }

    /**
     * Get data of carriers-payment asociation
     * @return type array
     */
    public function getAssociationsShipToPay()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('module_carrier');
        $sql->where('`id_shop` = '.(int)$this->context->shop->id);

        $carriers = Db::getInstance()->executeS($sql);

        return array('message_code' => $this->core->CODE_SUCCESS, 'carriers' => $carriers);
    }

    /**
     * Sort fields.
     * @return type array
     */
    public function updateFieldsPosition()
    {
        if (!Tools::isSubmit('order_fields')) {
            return array('message_code' => $this->core->CODE_ERROR, 'message' => $this->l('Error to update fields position'));
        }

        $order_fields = Tools::getValue('order_fields');
        $position     = 1;
        $errors_field = array();
        $message_code = $this->core->CODE_SUCCESS;

        if (is_array($order_fields) && count($order_fields)) {
            foreach ($order_fields as $id_field) {
                if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->update(
                    'opc_field',
                    array('position' => (int)$position),
                    'id_field = '.(int)$id_field
                )
                ) {
                    $field_class    = new FieldClass((int) $id_field);
                    $errors_field[] = $field_class->name;
                }
                $position++;
            }
        }

        $message = $this->l('Sort positions of fields has been updated successful');
        if (count($errors_field)) {
            $fields       = implode(', ', $errors_field);
            $message      = $this->l('Error to update position for field(s)').': '.$fields;
            $message_code = $this->core->CODE_ERROR;
        }

        return array(
            'message_code' => $message_code,
            'message'      => $message,
        );
    }

    public function removeField()
    {
        $id_field = (int) Tools::getValue('id_field', null);
        if (empty($id_field) || (int) $id_field === 0) {
            return array('message_code' => $this->core->CODE_ERROR, 'message' => $this->l('No field selected to remove.'));
        }

        $field_class = new FieldClass($id_field);
        if ((int) $field_class->is_custom === 0) {
            return array('message_code' => $this->core->CODE_ERROR, 'message' => $this->l('Cannot remove this field.'));
        }

        if (!$field_class->delete()) {
            return array(
                'message_code' => $this->core->CODE_ERROR,
                'message'      => $this->l('An error occurred while trying to remove.')
            );
        }

        return array('message_code' => $this->core->CODE_SUCCESS, 'message' => $this->l('Field remove successful.'));
    }

    /**
     * Save the field data.
     * @return type array
     */
    public function updateField()
    {
        if (!Tools::isSubmit('id_field')) {
            return array(
                'message_code' => $this->core->CODE_ERROR,
                'message'      => $this->l('An error occurred while trying to update.')
            );
        }

        $id_field = (int) Tools::getValue('id_field', null);
        if (empty($id_field) || (int) $id_field === 0) {
            $id_field = null;
        }

        $field_class = new FieldClass($id_field);

        $validate_object = false;
        if (is_null($id_field)) {
            $field_class->is_custom = true;
        } else {
            $validate_object = $field_class->object;
        }

        if (is_null($id_field)) {
            $field_class->is_custom = true;
        }

        $multishop_fields = array(
            'default_value' => 1,
            'required'      => 1,
            'active'        => 1
        );

        //only if field is custom can update data.
        if ($field_class->is_custom) {
            $field_class->name         = Tools::getValue('name');
            $field_class->object       = Tools::getValue('object');

            if (empty($id_field) && $field_class->is_custom && Validate::isLoadedObject(FieldClass::getField(
                $this->context->language->id,
                $this->context->shop->id,
                $field_class->object,
                $field_class->name
            ))) {
                return array(
                    'message_code' => $this->core->CODE_ERROR,
                    'message'      => $this->l('A field with this name already exists, try a different name')
                );
            }

            $field_class->type         = Tools::getValue('type');
            $field_class->size         = (int) Tools::getValue('size');
            $field_class->type_control = Tools::getValue('type_control');
            //shop
            $field_class->group        = $field_class->object;
            $field_class->row          = (int) FieldClass::getLastRowByGroup($field_class->group) + 1;
            $field_class->col          = 0;

            $multishop_fields['group'] = 1;
            $multishop_fields['row'] = 1;
            $multishop_fields['col'] = 1;
        }

        $array_description = array();
        $array_label = array();
        $descriptions  = Tools::getValue('description');
        $labels = Tools::getValue('label');

        foreach ($descriptions as $description) {
            $array_description[$description['id_lang']] = $description['description'];
            $multishop_fields['description'][$description['id_lang']] = 1;
        }
        foreach ($labels as $label) {
            $array_label[$label['id_lang']] = $label['description'];
            $multishop_fields['label'][$label['id_lang']] = 1;
        }

        $field_class->description = $array_description;
        $field_class->label = $array_label;

        $default_value = Tools::getValue('default_value');
//		if ($field_class->type == $this->globals->type->string)
//			$default_value = Tools::substr($default_value, 0, $field_class->size);

        $field_class->default_value = $default_value;
        $field_class->required      = (int) Tools::getValue('required');
        $field_class->active        = (int) Tools::getValue('active');

        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $field_class->setFieldsToUpdate($multishop_fields);
        }

        if ($field_class->validateFieldsLang(false) && $field_class->save()) {
            $result = array(
                'message_code'  => $this->core->CODE_SUCCESS,
                'message'       => $this->l('The field was successfully updated.'),
                'description'   => $array_description[$this->cookie->id_lang],
                'default_value' => $field_class->default_value,
            );

            if (is_null($id_field)) {
                $result['id_field'] = $field_class->id;
            } else if ($validate_object !== false && $field_class->object !== $validate_object) {
                $result['refresh_position'] = true;
            }
        } else {
            $result = array(
                'message_code' => $this->core->CODE_ERROR,
                'message'      => $this->l('An error occurred while trying to update.'),
            );
        }

        return $result;
    }

    public function removeImagePayment()
    {
        $errors    = array();

        $id_module = Tools::getValue('id_module');
        $name_payment = Tools::getValue('name_module');
        $id_payment = PaymentClass::getIdPaymentBy('id_module', $id_module);

        $paymentClass = new PaymentClass($id_payment);
        $paymentClass->name_image = 'no-image.png';
        $paymentClass->id_module = $id_module;
        $paymentClass->name = $name_payment;

        if (!$paymentClass->save()) {
            $errors[] = $this->l('There was an error while trying to delete the image.');
        }

        if (!empty($errors)) {
            return array('message_code' => $this->core->CODE_ERROR, 'message' => implode(', ', $errors));
        } else {
            return array(
                'message_code' => $this->core->CODE_SUCCESS,
                'message'      => $this->l('Image deleted successfully.'),
            );
        }
    }

    /**
     *
     * @param string $name
     * @return type
     */
    public function uploadImage()
    {
        $errors    = array();

        $id_module    = Tools::getValue('id_module');
        $force_display = Tools::getValue('force_display');
        $payment_data = Tools::getValue('payment_data');
        $test_mode   = Tools::getValue('test_mode');
        $test_ip     = Tools::getValue('test_ip');

        $id_payment = PaymentClass::getIdPaymentBy('id_module', $id_module);
        $payment    = new PaymentClass($id_payment);
        $module    = Module::getInstanceById($id_module);

        if (!Validate::isLoadedObject($payment)) {
            $payment->name_image = $module->name.'.gif';
        }

        $payment->id_module = $id_module;
        $payment->name = $module->name;
        $payment->force_display = $force_display;
        $payment->test_mode = $test_mode;
        $payment->test_ip = $test_ip;

        /* update payment image */
        if (count($_FILES)) {
            foreach ($_FILES as $payment_name => $file) {
                $payment_name = $payment_name;

                if (!isset($file['tmp_name']) || is_null($file['tmp_name']) || empty($file['tmp_name'])) {
                    $errors[] = $this->l('Cannot add file because it did not sent');
                }

                if (!ImageManager::isRealImage($file['tmp_name'], $file['type']) && $file['type'] != 'image/png' && $file['type'] != 'image/gif') {
                    $errors[] = $this->l('Image extension not allowed');
                }

                if (empty($errors)) {
                    $path = '';
                    $path_backup = '';
                    $extension = Tools::substr($file['type'], 6);

                    if (!empty($payment->name_image) && $payment->name_image != 'no-image.png') {
                        $path = dirname(__FILE__).'/views/img/payments/'.$payment->name_image;
                        $path_backup = $path.'.backup';

                        if (file_exists($path)) {
                            rename($path, $path_backup);
                        }
                    }

                    $payment->name_image = $payment->name.'.'.$extension;
                    $path = dirname(__FILE__).'/views/img/payments/'.$payment->name_image;

                    if (move_uploaded_file($file['tmp_name'], $path)) {
                        if (!empty($path_backup) && file_exists($path_backup)) {
                            unlink($path_backup);
                        }

                        $payment->save();
                    } else {
                        if (!empty($path_backup)) {
                            rename($path_backup, Tools::substr($path_backup, 0, Tools::strlen($path_backup) - 7));
                        }
                        $errors[] = $this->l('Cannot copy the file');
                    }
                }
            }
        }

        if (Tools::isSubmit('payment_data')) {
            //save description
            $payment_data = Tools::jsonDecode($payment_data);

            if (is_array($payment_data) && count($payment_data)) {
                $title       = array();
                $description = array();
                foreach ($payment_data as $data) {
                    $title[$data->id_lang]       = $data->title;
                    $description[$data->id_lang] = $data->description;
                }

                $payment->title       = $title;
                $payment->description = $description;

                if (!$payment->save()) {
                    $errors[] = $this->l('An error has ocurred while trying save');
                }
            }
        }

        if (!empty($errors)) {
            return array('message_code' => $this->core->CODE_ERROR, 'message' => implode(', ', $errors));
        } else {
            return array(
                'message_code' => $this->core->CODE_SUCCESS,
                'name_image' => count($_FILES) ? $payment->name_image : '',
                'message'      => $this->l('Payment configuration has been updated successfully.'),
            );
        }
    }

    /**
     * List of provider packs
     * @return type array
     */
    public function getRequiredFieldList()
    {
        //get content field list
        $content = FieldClass::getAllFields(null, null, null, null, null, array(), 'f.id_field');

        //se quitan del listado de campos los ID, pues no tiene sentido mostrarlos.
        foreach ($content as $i => $item) {
            if ($item->name == 'id') {
                unset($content[$i]);
            }
        }

        $actions = array(
            'edit'   => array(
                'action_class' => 'Fields',
                'class'        => 'has-action nohover',
                'icon'         => 'edit',
                'title'        => $this->l('Edit'),
                'tooltip'      => $this->l('Edit'),
            ),
            'remove' => array(
                'action_class' => 'Fields',
                'class'        => 'has-action nohover',
                'icon'         => 'times',
                'title'        => $this->l('Remove'),
                'tooltip'      => $this->l('Remove'),
                'condition'    => array(
                    'field'      => 'is_custom',
                    'comparator' => '1',
                ),
            ),
        );

        $headers  = array(
            'name'          => $this->l('Name'),
            'object'        => $this->l('Object'),
            'description'   => $this->l('Description'),
            'label'         => $this->l('Label'),
            'default_value' => $this->l('Default value'),
            'required'      => $this->l('Required'),
            'active'        => $this->l('Active'),
            'actions'       => $this->l('Actions'),
        );

        $truncate = array(
            'description' => 30,
            'label' => 30
        );

        //use array with action_class (optional for var) and action (action name) for custom actions.
        $status = array(
            'required' => array(
                'action_class' => 'Fields',
                'action'       => 'toggleRequired',
                'class'        => 'has-action',
            ),
            'active'   => array(
                'action_class' => 'Fields',
                'action'       => 'toggleActive',
                'class'        => 'has-action',
            ),
        );

        $color = array(
            array(
                'by'     => 'object',
                'colors' => array(
                    'customer' => 'primary',
                    'delivery' => 'success',
                    'invoice'  => 'warning',
                )
            )
        );

        return array(
            'message_code' => $this->core->CODE_SUCCESS,
            'content'      => $content,
            'table'        => 'table-required-fields',
            'color'        => $color,
            'headers'      => $headers,
            'actions'      => $actions,
            'truncate'     => $truncate,
            'status'       => $status,
            'prefix_row'   => 'field',
        );
    }

    public function getFieldsForFormModifier(&$params, $id, $object)
    {
        $id_lang    = $this->context->language->id;
        $fields     = FieldClass::getAllFields($id_lang, null, $object, false, true, array(), 'fs.group, fs.row, fs.col', true);

        if ($fields) {
            foreach ($fields as $field) {
                if (array_key_exists($field->name, $params['fields'][0]['form']['input']) || $field->object !== $object) {
                    continue;
                }

                if ($object === 'customer') {
                    $params['fields_value'][$field->name.($field->type_control === 'checkbox' ? '_' : '')] = FieldCustomerClass::getFieldValue($field->id, $id);
                } else {
                    $params['fields_value'][$field->name.($field->type_control === 'checkbox' ? '_' : '')] = FieldCustomerClass::getFieldValue($field->id, null, $id);
                }

                $params['fields'][0]['form']['input'][$field->name] = array(
                    'type'  => ($field->type_control === 'textbox') ? 'text' : $field->type_control,
                    'label' => $field->description,
                    'name'  => $field->name,
                    'col'   => 4,
                );

                if ($field->type_control === 'select') {
                    $params['fields'][0]['form']['input'][$field->name]['options'] = array(
                        'query' => $field->options['data'],
                        'id'    => 'value',
                        'name'  => 'description'
                    );
                } elseif ($field->type_control === 'textbox' || $field->type_control === 'textarea') {
                    $params['fields'][0]['form']['input'][$field->name]['maxlength'] = $field->size;
                } elseif ($field->type_control === 'checkbox') {
                    $params['fields'][0]['form']['input'][$field->name]['values'] = array(
                        'id'    => $field->name,
                        'name'  => $field->name,
                        'query' => ''
                    );
                }
            }
        }
    }

    public function hookActionAdminCustomersFormModifier($params)
    {
        $id_customer = Tools::getValue('id_customer');
        $this->getFieldsForFormModifier($params, $id_customer, 'customer');
    }

    public function hookActionAdminAddressesFormModifier($params)
    {
        $id_address = Tools::getValue('id_address');
        $this->getFieldsForFormModifier($params, $id_address, 'delivery');
        $this->getFieldsForFormModifier($params, $id_address, 'invoice');
    }

    public function hookActionObjectAddressUpdateAfter()
    {
        if ($this->context->controller->controller_type !== 'admin') {
            return false;
        }

        $custom_fields  = array();
        $fields         = FieldClass::getAllFields(null, null, null, false, true, array(), 'fs.group, fs.row, fs.col', true);

        if ($fields) {
            foreach ($fields as $field) {
                if ($field->object === 'customer' || in_array($field->name, $custom_fields)) {
                    continue;
                }

                $custom_fields[] = $field->name;

                if ($field->type_control === 'checkbox') {
                    $field->value = (Tools::getValue($field->name.'_') === 'on') ? 1 : 0;
                } else {
                    $field->value = Tools::getValue($field->name);
                }

//                if (!empty($field->value)) {
                    $this->saveCustomFields($field, Tools::getValue('id_address'));
//                }
            }
        }
    }

    public function hookActionObjectCustomerAddAfter()
    {
        $this->hookActionObjectCustomerUpdateAfter();
    }

    public function hookActionObjectCustomerUpdateAfter()
    {
        if ($this->context->controller->controller_type !== 'admin') {
            return false;
        }

        $fields = FieldClass::getAllFields(null, null, 'customer', false, true, array(), 'fs.group, fs.row, fs.col', true);

        if ($fields) {
            foreach ($fields as $field) {
                if ($field->type_control === 'checkbox') {
                    $field->value = (Tools::getValue($field->name.'_') === 'on') ? 1 : 0;
                } else {
                    $field->value = Tools::getValue($field->name);
                }

//                if (!empty($field->value)) {
                    $this->saveCustomFields($field);
//                }
            }
        }
    }

    public function getFieldsFront(&$is_need_invoice)
    {
        $language = $this->context->language;

        $selected_country = (int) FieldClass::getDefaultValue('delivery', 'id_country');
        if (!$this->context->customer->isLogged() && (Configuration::get('PS_GEOLOCATION_ENABLED'))) {
            if ($this->context->country->active) {
                $selected_country = $this->context->country->id;
            }
        }

        if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
            $countries = Carrier::getDeliveredCountries($language->id, true, true);
        } else {
            $countries = Country::getCountries($language->id, true);
        }

        //-----------------------------------------------------------------------------
        //GROUP CUSTOMER
        //-----------------------------------------------------------------------------
        $groups            = Group::getGroups($this->context->cookie->id_lang);
        $groups_availables = '';

        if (!empty($this->config_vars['OPC_CHOICE_GROUP_CUSTOMER_ALLOW'])) {
            $groups_availables = explode(
                ',',
                $this->config_vars['OPC_CHOICE_GROUP_CUSTOMER_ALLOW']
            );
        }

        foreach ($groups as $key => $group) {
            if (is_array($groups_availables)) {
                if (!in_array($group['id_group'], $groups_availables)) {
                    unset($groups[$key]);
                }
            }
        }
        //-----------------------------------------------------------------------------

        $opc_fields          = array();
        $opc_fields_position = array();

        $fields = FieldControl::getAllFields($this->context->cookie->id_lang);

        foreach ($fields as $field) {
            //support module: pts_customertypeopc - v1.1.0
            if ($ctopc = $this->core->isModuleActive('pts_customertypeopc')) {
                if ($ctopc->isVisible()) {
                    $row_field = $ctopc->getStatusFieldOpc($field->id);

                    if ($row_field && is_array($row_field)) {
                        $field->active = true;
                        $field->required = $row_field['required'];
                    }
                }
            }

            if (!$field->active /*|| ($field->is_custom && $this->only_register)*/) {
                continue;
            }

            $field->capitalize = false;
            if (in_array($field->name, $this->fields_to_capitalize)
                && $this->config_vars['OPC_CAPITALIZE_FIELDS']
            ) {
                $field->capitalize = true;
            }

            if ($field->object == $this->globals->object->customer) {
                if ($this->config_vars['OPC_CHOICE_GROUP_CUSTOMER']) {
                    $new_field = new FieldControl();

                    $new_field->name          = 'group_customer';
                    $new_field->id_control    = 'group_customer';
                    $new_field->name_control  = 'group_customer';
                    $new_field->object        = 'customer';
                    $new_field->description   = $this->l('Are you?');
                    $new_field->type          = 'isInt';
                    $new_field->size          = '11';
                    $new_field->type_control  = 'select';
                    $new_field->default_value = ($this->context->customer->isLogged() ? $this->context->customer->id_default_group : '');
                    $new_field->required      = false;
                    $new_field->is_custom     = false;
                    $new_field->active        = true;
                    $new_field->options       = array(
                        'empty_option' => false,
                        'value'        => 'id_group',
                        'description'  => 'name',
                        'data'         => $groups
                    );

                    $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                }

                if ($ctopc = $this->core->isModuleActive('pts_customertypeopc')) {
                    if ($ctopc->isVisible()) {
                        $new_field = new FieldControl();

                        $new_field->name          = 'customer_type';
                        $new_field->id_control    = 'customer_type';
                        $new_field->name_control  = 'customer_type';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('Customer type');
                        $new_field->type          = 'isInt';
                        $new_field->size          = '1';
                        $new_field->type_control  = Configuration::get('CTOPC_FIELD_TYPE');
                        $new_field->default_value = $ctopc->getValueByCustomer($this->context->customer->id);
                        $new_field->required      = false;
                        $new_field->is_custom     = false;
                        $new_field->active        = true;
                        $new_field->options       = array(
                            'empty_option' => false,
                            'value'        => 'id',
                            'description'  => 'name',
                            'data'         => $ctopc->getValuesCustomerType()
                        );

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                    }
                }

                if ($field->name == 'id_gender') {
                    $genders = array();
                    foreach (Gender::getGenders() as $i => $gender) {
                        $genders[$i]['id_gender'] = $gender->id_gender;
                        $genders[$i]['name']      = $gender->name;
                    }

                    $field->options = array(
                        'value'       => 'id_gender',
                        'description' => 'name',
                        'data'        => $genders
                    );
                } elseif ($field->name == 'passwd') {
                    if ($this->context->customer->isLogged()) {
                        $new_field = new FieldControl();

                        $new_field->name          = 'checkbox_change_passwd';
                        $new_field->id_control    = 'checkbox_change_passwd';
                        $new_field->name_control  = 'checkbox_change_passwd';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('I want to change my password');
                        $new_field->type          = 'isBool';
                        $new_field->size          = '0';
                        $new_field->type_control  = 'checkbox';
                        $new_field->default_value = '0';
                        $new_field->required      = false;
                        $new_field->is_custom     = false;
                        $new_field->active        = true;

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;

                        $new_field = new FieldControl();

                        $new_field->name          = 'current_passwd';
                        $new_field->id_control    = 'customer_current_passwd';
                        $new_field->name_control  = 'current_passwd';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('Current Password');
                        $new_field->type          = 'isPasswd';
                        $new_field->size          = '32';
                        $new_field->type_control  = 'textbox';
                        $new_field->default_value = '';
                        $new_field->required = false;
                        $new_field->is_custom = false;
                        $new_field->active    = true;
                        $new_field->classes   = 'customer';
                        $new_field->is_passwd = true;

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                    }

                    if ($this->config_vars['OPC_REQUEST_PASSWORD'] &&
                        $this->config_vars['OPC_OPTION_AUTOGENERATE_PASSWORD'] &&
                        !Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
                        $new_field = new FieldControl();

                        $new_field->name          = 'checkbox_create_account';
                        $new_field->id_control    = 'checkbox_create_account';
                        $new_field->name_control  = 'checkbox_create_account';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('I want to configure a custom password.');
                        $new_field->type          = 'isBool';
                        $new_field->size          = '0';
                        $new_field->type_control  = 'checkbox';
                        $new_field->default_value = '0';
                        $new_field->required      = false;
                        $new_field->is_custom     = false;
                        $new_field->active        = true;

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                    }

                    //add checkbox guest checkout
                    if (Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
                        $new_field = new FieldControl();

                        $new_field->name          = 'checkbox_create_account_guest';
                        $new_field->id_control    = 'checkbox_create_account_guest';
                        $new_field->name_control  = 'checkbox_create_account_guest';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('Create an account and enjoy the benefits of a registered customer.');
                        $new_field->type          = 'isBool';
                        $new_field->size          = '0';
                        $new_field->type_control  = 'checkbox';
                        $new_field->default_value = (!$this->context->customer->isLogged() && !$this->context->customer->isGuest()) ? '1' : '0';
                        $new_field->required      = false;
                        $new_field->is_custom     = false;
                        $new_field->active        = true;

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                    }

                    if ($this->config_vars['OPC_REQUEST_PASSWORD'] || $this->context->customer->isLogged()) {
                        //add field password
                        $field->name_control = 'passwd_confirmation';

                        if ((int) $this->config_vars['OPC_OPTION_AUTOGENERATE_PASSWORD']) {
                            $field->required = false;
                        } else {
                            $field->required = true;
                        }

                        $opc_fields[$field->object.'_'.$field->name] = $field;

                        //add field confirmation password
                        $new_field = new FieldControl();

                        $new_field->name          = 'conf_passwd';
                        $new_field->id_control    = 'customer_conf_passwd';
                        $new_field->name_control  = 'passwd';
                        $new_field->object        = 'customer';
                        $new_field->description   = $this->l('Repeat password');
                        $new_field->type          = 'confirmation';
                        $new_field->size          = '32';
                        $new_field->type_control  = 'textbox';
                        $new_field->default_value = '';

                        if ((int) $this->config_vars['OPC_OPTION_AUTOGENERATE_PASSWORD']) {
                            $new_field->required = false;
                        } else {
                            $new_field->required = true;
                        }

                        $new_field->is_custom = false;
                        $new_field->active    = true;
                        $new_field->is_passwd = true;

                        $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                    }

                    continue;
                } elseif ($field->name == 'email') {
                    if (!$this->context->customer->isLogged() && !$this->context->customer->isGuest()) {
                        //add field email
                        $field->name_control                         = 'email_confirmation';
                        $opc_fields[$field->object.'_'.$field->name] = $field;

                        if ($this->config_vars['OPC_REQUEST_CONFIRM_EMAIL']) {
                            //add field confirmation email
                            $new_field = new FieldControl();

                            $new_field->name                                     = 'conf_email';
                            $new_field->id_control                               = 'customer_conf_email';
                            $new_field->name_control                             = 'email';
                            $new_field->object                                   = 'customer';
                            $new_field->description                              = $this->l('Confirm email');
                            $new_field->type                                     = 'confirmation';
                            $new_field->size                                     = '128';
                            $new_field->type_control                             = 'textbox';
                            $new_field->default_value                            = '';
                            $new_field->required                                 = $field->required;
                            $new_field->is_custom                                = false;
                            $new_field->active                                   = true;
                            $opc_fields[$new_field->object.'_'.$new_field->name] = $new_field;
                        }

                        continue;
                    }
                }
            } elseif ($field->object == $this->globals->object->delivery) {
                if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA']) {
                    if ($field->name == 'firstname') {
                        continue;
                    } elseif ($field->name == 'lastname') {
                        continue;
                    }
                }
            } elseif ($field->object == $this->globals->object->invoice) {
                if ($this->config_vars['OPC_ENABLE_INVOICE_ADDRESS']) {
                    if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA']) {
                        if ($field->name == 'firstname') {
                            continue;
                        } elseif ($field->name == 'lastname') {
                            continue;
                        }
                    }

                    if ($this->config_vars['OPC_REQUIRED_INVOICE_ADDRESS']) {
                        $is_need_invoice = true;
                    }
                }
            }

            if ($field->name == 'id_country') {
                $field->default_value = $selected_country;
                $field->options       = array(
                    'empty_option' => true,
                    'value'        => 'id_country',
                    'description'  => 'name',
                    'data'         => $countries
                );
            }

            if ($field->name == 'vat_number') {
                $module = $this->core->isModuleActive('vatnumber');
                if ($module) {
                    if (Configuration::get('VATNUMBER_MANAGEMENT') || Configuration::get('VATNUMBER_CHECKING')) {
                        $field->type = 'isVatNumber';
                    }
                }
                $vatnumbercleaner = $this->core->isModuleActive('vatnumbercleaner');//v1.3.8 - MassonVincent
                if ($vatnumbercleaner) {
                    $field->type = 'isVatNumber';
                }
                $validatevatnumber = $this->core->isModuleActive('validatevatnumber');//v2.1.7 - ActiveDesign
                if ($validatevatnumber) {
                    $field->type = 'isVatNumber';
                }
            }

            $opc_fields[$field->object.'_'.$field->name] = $field;
        }

        $fields_position = $this->getFieldsPosition();
        if ($fields_position) {
            foreach ($fields_position as $group => $rows) {
                foreach ($rows as $row => $fields) {
                    foreach ($fields as $position => $field) {
                        if ($field->name == 'id' && $group == 'customer') {
                            if (isset($opc_fields[$field->object.'_group_customer'])) {
                                $index = $field->object.'_group_customer';
                                $opc_fields_position[$group][$row - 2][$position - 1] = $opc_fields[$index];
                            }
                            if (isset($opc_fields[$field->object.'_customer_type'])) {
                                $index = $field->object.'_customer_type';
                                $opc_fields_position[$group][$row - 3][$position - 1] = $opc_fields[$index];
                            }
                        }

                        //aditional field before
                        if ($field->name == 'passwd') {
                            if ($this->context->customer->isLogged()) {
                                if (isset($opc_fields[$field->object.'_checkbox_change_passwd'])) {
                                    $index = $field->object.'_checkbox_change_passwd';
                                    $opc_fields_position[$group][-1][-1] = $opc_fields[$index];
                                }
                            } else {
                                if (isset($opc_fields[$field->object.'_checkbox_create_account'])) {
                                    $index = $field->object.'_checkbox_create_account';
                                    $opc_fields_position[$group][-1][-1] = $opc_fields[$index];
                                }
                                if (isset($opc_fields[$field->object.'_checkbox_create_account_guest'])) {
                                    $index = $field->object.'_checkbox_create_account_guest';
                                    $opc_fields_position[$group][-1][-1] = $opc_fields[$index];
                                }
                            }
                        }

                        //field
                        if (isset($opc_fields[$field->object.'_'.$field->name])) {
                            $index = $field->object.'_'.$field->name;
                            $opc_fields_position[$group][$row][$position] = $opc_fields[$index];
                        }

                        //aditional field after
                        if ($field->name == 'passwd') {
                            if ($this->context->customer->isLogged()) {
                                if (isset($opc_fields[$field->object.'_current_passwd'])) {
                                    $index                                            = $field->object.'_current_passwd';
                                    array_unshift($opc_fields_position[$group][$row], $opc_fields[$index]);
                                }
                            }
                            if (isset($opc_fields[$field->object.'_conf_passwd'])) {
                                $index                                            = $field->object.'_conf_passwd';
                                $opc_fields_position[$group][$row][$position + 2] = $opc_fields[$index];
                            }
                        } elseif ($field->name == 'email') {
                            if (Validate::isLoadedObject($this->context->customer) && !empty($this->context->customer->email)) {
                                $opc_fields_position[$group][$row][$position]->value = $this->context->customer->email;
                            }

                            if (isset($opc_fields[$field->object.'_conf_email'])) {
                                if (isset($opc_fields[$field->object.'_conf_passwd'])) {
                                    $row += 2;
                                } else {
                                    $row += 1;
                                }

                                $index                                            = $field->object.'_conf_email';
                                $opc_fields_position[$group][$row][$position] = $opc_fields[$index];
                            }
                        }
                    }
                }
            }
        }

        if ($this->core->isModuleActive('idxrecargoe')) {
            $new_field_idxrecargoeq = new FieldControl();
            $new_field_idxrecargoeq->name          = 'idxrecargoeq';
            $new_field_idxrecargoeq->id_control    = 'idxrecargoeq';
            $new_field_idxrecargoeq->name_control  = 'idxrecargoeq';
            $new_field_idxrecargoeq->object        = 'customer';
            $new_field_idxrecargoeq->description   = $this->l('Do you need to apply the equivalent surcharge?');
            $new_field_idxrecargoeq->type          = 'isBool';
            $new_field_idxrecargoeq->size          = '0';
            $new_field_idxrecargoeq->type_control  = 'checkbox';
            $new_field_idxrecargoeq->default_value = '0';
            $new_field_idxrecargoeq->required = false;
            $new_field_idxrecargoeq->is_custom = false;
            $new_field_idxrecargoeq->active    = true;

            array_push($opc_fields_position['customer'], array($new_field_idxrecargoeq));
        }

        return $opc_fields_position;
    }

    public function getMediaFront()
    {
        $this->context->smarty->assign('onepagecheckoutps', $this);

        $this->context->controller->addJqueryUI('ui.datepicker');

        $this->context->controller->addJS($this->_path.'views/js/lib/bootstrap/plugins/typeahead/bootstrap-typeahead.min.js');

        /* Include datepicker language */
        $file_datepicker = 'js/jquery/ui/i18n/jquery.ui.datepicker-'.$this->context->language->iso_code.'.js';
        if (file_exists($file_datepicker)) {
            $this->context->controller->registerJavascript('jquery_ui_localizations', 'js/jquery/ui/i18n/jquery.ui.datepicker-'.$this->context->language->iso_code.'.js', null);
        }
        /* End Include datepicker language */

        if ($this->config_vars['OPC_SHOW_LIST_CITIES_GEONAMES'] ||
            $this->config_vars['OPC_AUTO_ADDRESS_GEONAMES']
        ) {
            $this->context->controller->addJS($this->_path.'views/js/lib/jeoquery.js');
        }

        if ($this->config_vars['OPC_AUTOCOMPLETE_GOOGLE_ADDRESS']) {
            if (!empty($this->config_vars['OPC_GOOGLE_API_KEY'])) {
                $google_apy_source = 'https://maps.googleapis.com/maps/api/js?key=';
                $google_apy_source .= trim($this->config_vars['OPC_GOOGLE_API_KEY']);
                $google_apy_source .= '&sensor=false&libraries=places&language='.$this->context->language->iso_code;

                $this->context->controller->registerJavascript(sha1($google_apy_source), $google_apy_source, array('server' => 'remote'));
            }
        }

        $this->context->controller->addJS($this->_path.'views/js/lib/form-validator/jquery.form-validator.min.js');
        $this->context->controller->addJS($this->_path.'views/js/lib/jquery/plugins/visible/jquery.visible.min.js');
        $this->context->controller->addJS($this->_path.'views/js/lib/jquery/plugins/total-storage/jquery.total-storage.min.js');
        $this->context->controller->addJS($this->_path.'views/js/lib/pts/tools.js');
        $this->context->controller->addJS($this->_path.'views/js/front/onepagecheckoutps.js');
        $this->context->controller->addJS($this->_path.'views/js/front/override.js');

        //Compatibilidad modulo paypalbraintree - v1.0.1 by WebDevOverture
        $module = $this->core->isModuleActive('paypalbraintree');
        if ($module !== false) {
            $this->context->controller->registerJavascript(
                'braintree',
                'https://js.braintreegateway.com/web/dropin/1.8.0/js/dropin.min.js',
                array(
                    'server' => 'remote',
                    'position' => 'head',
                    'priority' => 20
                )
            );
        }

        $this->context->controller->addCSS($this->_path.'views/css/lib/font-awesome/font-awesome.css');
        $this->context->controller->addCSS($this->_path.'views/css/front/onepagecheckoutps.css');
        $this->context->controller->addCSS($this->_path.'views/css/front/onepagecheckoutps_17.css');
        $this->context->controller->addCSS($this->_path.'views/css/front/responsive.css');
        $this->context->controller->addCSS($this->_path.'views/css/front/override.css');
    }

    public function hookActionObjectAddressAddAfter($params)
    {
        $sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'product WHERE is_virtual = 1';
        $virtual_products = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

        $sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'product WHERE is_virtual = 0';
        $no_virtual_products = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

        $object_address = 'delivery';
        if ($virtual_products > $no_virtual_products) {
            $object_address = 'invoice';
        }

        $values_customer_address = array(
            'id_customer' => $params['object']->id_customer,
            'id_address' => $params['object']->id,
            'object' => $object_address
        );
        Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('opc_customer_address', $values_customer_address);

        $this->hookActionObjectAddressUpdateAfter();
    }

    public function hookActionObjectAddressDeleteAfter($params)
    {
        $query = 'DELETE FROM '._DB_PREFIX_.'opc_customer_address WHERE id_address = '.(int)$params['object']->id;
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    }

    public function hookDisplayHeader()
    {
        if (!$this->core->isModuleActive($this->name) || !$this->core->isVisible()) {
            return;
        }

        if ($this->context->controller->php_self == 'order') {
            $this->getMediaFront();

            if ($this->context->cart->nbProducts() <= 0) {
                $this->context->cart->id_address_delivery = 0;
                $this->context->cart->id_address_invoice = 0;
                $this->context->cart->update();
                $this->context->cart->setDeliveryOption(null);
            }
        } else {
            if ($this->context->controller->php_self == 'cart'
                && !Tools::getIsset('ajax')
//                && !Tools::getIsset('token')
                && (!Tools::getIsset('action') || Tools::getValue('action') == 'show')
                && $this->context->cart->nbProducts() > 0
                && $this->context->cart->checkQuantities()
                && $this->config_vars['OPC_REDIRECT_DIRECTLY_TO_OPC']
            ) {
                $check_minimal = $this->checkMinimalPurchase();
                if (empty($check_minimal)) {
                    Tools::redirect('order');
                }
            }
        }
    }

    public function hookActionShopDataDuplication($params)
    {
        $this->installLanguageShop($params['new_id_shop']);
    }

    public function hookDisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);

        $query = new DbQuery();
        $query->select('fc.value, fl.description field_description, fol.description option_description');
        $query->from('opc_field_customer', 'fc');
        $query->innerJoin('opc_field_lang', 'fl', 'fl.id_field = fc.id_field AND fl.id_lang = '.(int)$this->context->language->id);
        $query->leftJoin(
            'opc_field_option_lang',
            'fol',
            'fc.id_option = fol.id_field_option AND fol.id_lang = '.(int)$this->cookie->id_lang
        );
        $query->where('fc.id_customer = '.(int)$order->id_customer);
        $query->where('fc.id_address IN(0, '.(int)$order->id_address_delivery.', '.(int)$order->id_address_invoice.') OR fc.id_address IS NULL');

        $field_options = Db::getInstance()->executeS($query);

        if (!count($field_options)) {
            return;
        }

        $this->smarty->assign(array(
            'field_options' => $field_options,
        ));

        return $this->display(__FILE__, 'views/templates/hook/order.tpl');
    }

    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (Validate::isLoadedObject($customer)) {
            $sql = "DELETE FROM "._DB_PREFIX_."opc_social_network WHERE id_customer = '".(int)$customer->id."'";
            if (Db::getInstance()->execute($sql)) {
                return Tools::jsonEncode(true);
            }
            return Tools::jsonEncode($this->l('One Page Checkout PS: Unable to erase the token when the client was registered by social networks.'));
        }
    }

    public function hookActionCustomerLogoutAfter($params)
    {
        if (isset($params['customer']) && !$params['customer']->isGuest()) {
            $opc_social_networks = $this->config_vars['OPC_SOCIAL_NETWORKS'];
            $opc_social_networks = Tools::jsonDecode($opc_social_networks);
            if ($opc_social_networks) {
                foreach ($opc_social_networks as $network) {
                    if ($network->enable == 1) {
                        $client = $this->getInstanceOauthClient($network->network);
                        if (($success = $client->Initialize())) {
                            if (($success = $client->Process())) {
                                $client->ResetAccessToken();
                                $client->Finalize($success);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Return the content cms request.
     *
     * @return content html cms
     */
    public function loadCMS()
    {
        $html   = '';
        $id_cms = Tools::getValue('id_cms', '');

        $cms = new CMS($id_cms, $this->context->language->id);
        if (Validate::isLoadedObject($cms)) {
            $html = $cms->content;
        }

        return $html;
    }

    private function saveCustomFields(FieldClass $field, $id_address = null)
    {
        if ($this->context->controller->controller_type === 'front') {
            $id_customer    = $this->context->customer->id;
        } else {
            $id_customer    = Tools::getValue('id_customer');
        }

        if ($id_customer > 0) {
            $sql = new DbQuery();
            $sql->select('count(`id_field`)');
            $sql->from(FieldCustomerClass::$definition['table']);
            $sql->where('id_field = '.(int)$field->id);
            $sql->where('id_customer = '.(int)$id_customer);
            $sql->where('object =  \''.pSQL($field->object).'\'');

            if ($field->object !== 'customer') {
                $sql->where('id_address = '.(int)$id_address);
            }
            $result = Db::getInstance()->getValue($sql);

            $data = array(
                'id_option' => (int)FieldOptionClass::getIdOptionByIdFieldAndValue($field->id, $field->value),
                'value'     => pSQL($field->value),
                'date_upd'  => date('Y-m-d H:i:s')
            );

            if ($result) {
                $where = 'id_field = '.(int)$field->id.' AND id_customer = '.(int)$id_customer.' AND object = \''.pSQL($field->object).'\'';

                if ($field->object !== 'customer') {
                    $where .= ' AND id_address = '.(int)$id_address;
                }

                FieldCustomerClass::updateData($data, $where);
            } else {
                $data['id_field']       = (int)$field->id;
                $data['id_customer']    = $id_customer;
                $data['object']         = $field->object;

                if ($field->object !== 'customer') {
                    $data['id_address']     = $id_address;
                }

                FieldCustomerClass::insertData($data);
            }
        }
    }

    public function validateFields(
        $fields,
        &$customer,
        &$address_delivery,
        &$address_invoice,
        &$password,
        &$is_set_invoice,
        &$custom_fields = null
    ) {
        $fields_by_object = array();
        foreach ($fields as $field) {
            if ($field->name == 'id') {
                continue;
            }

            //Capitalize campos seleccionados.
            if (in_array($field->name, $this->fields_to_capitalize) && $this->config_vars['OPC_CAPITALIZE_FIELDS']) {
                $field->value = ucwords($field->value);
            }

            $field_db = FieldClass::getField(
                $this->context->language->id,
                $this->context->shop->id,
                $field->object,
                $field->name
            );

            if ($field_db) {
                $field_db->value                                = $field->value;
                $fields_by_object[$field->object][$field->name] = $field_db;

                //if custom, save options
                if ($field_db->is_custom) {
                    if (is_array($custom_fields)) {
                        $custom_fields[] = $field_db;
                    }
                }
            }
        }

        foreach ($fields_by_object as $name_object => $fields) {
            if ($name_object == $this->globals->object->customer) {
                if (empty($customer)) {
                    $customer = new Customer();
                }

                $this->addFieldsRequired($fields, $name_object, $customer);
                $this->validateFieldsCustomer($fields, $customer, $password);
            } elseif ($name_object == $this->globals->object->delivery) {
                if (empty($address_delivery)) {
                    $address_delivery = new Address();
                }

                $this->addFieldsRequired($fields, $name_object, $address_delivery);
                $this->validateFieldsAddress($fields, $address_delivery);
            } elseif ($name_object == $this->globals->object->invoice) {
                if (empty($address_invoice)) {
                    $address_invoice = new Address();
                }

                $this->addFieldsRequired($fields, $name_object, $address_invoice);
                $this->validateFieldsAddress($fields, $address_invoice);

                $is_set_invoice = true;
            }
        }
    }

    public function createCustomerAjax()
    {
        $customer = null;
        $password = null;
        $results = array();
        $address_delivery = null;
        $address_invoice = null;
        $custom_fields = array();

        $fields = Tools::jsonDecode(Tools::getValue('fields_opc'));
        $is_set_invoice = (bool)Tools::getValue('is_set_invoice');

//        $id_address_delivery = (int)Tools::getValue('id_address_delivery');
//        $id_address_invoice  = (int)Tools::getValue('id_address_invoice');
//        if (empty($id_address_delivery)) {
//            $id_address_delivery = $this->context->cart->id_address_delivery;
//
//            if (empty($id_address_delivery)) {
//                $id_address_delivery = $this->getIdAddressAvailable('delivery');
//            }
//        }
//        if (empty($id_address_invoice)) {
//            $id_address_invoice = $this->context->cart->id_address_invoice;
//
//            if (empty($id_address_invoice)) {
//                $id_address_invoice = $this->getIdAddressAvailable('invoice');
//            }
//        }
//        $address_delivery = new Address($id_address_delivery);
//        $address_invoice  = $is_set_invoice ? new Address($id_address_invoice) : null;

        $this->validateFields($fields, $customer, $address_delivery, $address_invoice, $password, $is_set_invoice, $custom_fields);

        Hook::exec('actionContactFormSubmitCaptcha');

        if (is_array($this->context->controller->errors) && count($this->context->controller->errors) > 0) {
            return array(
                'hasError' => !empty($this->context->controller->errors),
                'errors' => $this->context->controller->errors
            );
        }

        if (is_array($this->errors) && !count($this->errors)) {
            $this->createCustomer($customer, $address_delivery, $address_invoice, $password, $is_set_invoice);
            if (is_array($this->errors) && !count($this->errors)) {
                if (count($custom_fields) > 0) {
                    foreach ($custom_fields as $custom_field) {
                        $id_address = null;

                        if ($custom_field->object === 'delivery') {
                            $id_address = $address_delivery->id;
                        } elseif ($custom_field->object === 'invoice') {
                            $id_address = $address_invoice->id;
                        }

                        $this->saveCustomFields($custom_field, $id_address);
                    }
                }

                $results = array(
                    'isSaved'             => true,
                    'isGuest'             => $customer->is_guest,
                    'id_customer'         => (int) $customer->id,
                    'id_address_delivery' => !empty($address_delivery) ? $address_delivery->id : '',
                    'id_address_invoice'  => !empty($address_invoice) ? $address_invoice->id : ''
                );

                if ($this->core->isModuleActive('customersactivation')) {
                    $results['redirect'] = $this->context->link->getModuleLink(
                        'customersactivation',
                        'customersactivation',
                        array(
                            'token' => Tools::encrypt('customersactivation/index'),
                            'act_customer' => 0,
                            'ct' => $customer->id
                        ),
                        true,
                        $customer->id_lang
                    );
                }

                /* Compatibilidad con módulo idxvalidatinguser - v2.9.6 - innovadeluxe */
                $idxvalidatinguser = $this->core->isModuleActive('idxvalidatinguser');
                if ($idxvalidatinguser) {
                    $is_wholesaler  = Tools::getValue('id_customertype');
                    $is_b2bForm     = Tools::getValue('idxb2b');

                    if ($is_wholesaler == '1'
                        || $is_b2bForm
                        || !Configuration::get(Tools::strtoupper($idxvalidatinguser->name).'_RETAILERALLOW')
                    ) {
                        $results['redirect'] = $this->context->link->getModuleLink('idxvalidatinguser', 'deluxevalidatinguser');
                    }
                }
            }
        }

        $results['hasError']    = !empty($this->errors);
        $results['errors']      = $this->errors;

        return $results;
    }

    /**
     * Create & login customer.
     *
     * @param object &$customer
     * @param object &$address_delivery
     * @param object &$address_invoice
     * @param string $password
     * @param boolean $is_set_invoice
     */
    public function createCustomer(&$customer, &$address_delivery, &$address_invoice, $password, $is_set_invoice)
    {
        Hook::exec('actionBeforeSubmitAccount');

        Hook::exec('OnePageCheckoutPsIntegration', array(
            'action'    => 'beforeSubmitAccount',
            'object'    => $customer,
            'errors'    => &$this->errors
        ));

        if (count($this->context->controller->errors)) {
            $this->errors = $this->context->controller->errors;
        }

        if (Customer::customerExists($customer->email)) {
            if ($this->config_vars['OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED']) {
                $this->errors[] = sprintf(
                    $this->l('The email %s is already in our database. If the information is correct, please login.'),
                    '<b>'.$customer->email.'</b>'
                );
            }
        }

        $emailverificationopc = $this->core->isModuleActive('emailverificationopc');
        if ($emailverificationopc) {
            $email_verified = $emailverificationopc->validateEmailVerifiedCustomer($customer, true);

            if (!$email_verified) {
                $this->warnings[] = $this->l('The customer was created properly but can not log in the store until you verify your email address in the link sent to your email.');
            }
        }

        if (!is_null($address_delivery)) {
            if ($this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL']
                || ($this->context->cart->nbProducts() > 0 && !$this->context->cart->isVirtualCart())
            ) {
                $country = new Country($address_delivery->id_country, Configuration::get('PS_LANG_DEFAULT'));
                if (!Validate::isLoadedObject($country)) {
                    $this->errors[] = $this->l('Country cannot be loaded.');
                } elseif ((int) $country->contains_states && !(int) $address_delivery->id_state) {
                    $this->errors[] = $this->l('This country requires you to chose a State.');
                }
            }
        }

        if (!is_null($address_invoice) && $is_set_invoice) {
            $country_invoice = new Country($address_invoice->id_country, Configuration::get('PS_LANG_DEFAULT'));
            if (!Validate::isLoadedObject($country_invoice)) {
                $this->errors[] = $this->l('Country cannot be loaded.');
            } elseif ($this->config_vars['OPC_ENABLE_INVOICE_ADDRESS']
                && $is_set_invoice
                && (int) $country_invoice->contains_states
                && !(int) $address_invoice->id_state
            ) {
                $this->errors[] = $this->l('This country requires you to chose a State.');
            }
        }

        $errors = $customer->validateFields(false, true);
        if ($errors !== true) {
            $this->errors[] = $errors;
        }

        if (is_array($this->errors) && !count($this->errors) && is_array($this->warnings) && !count($this->warnings)) {
            //New Guest customer
            if (Tools::getIsset('is_new_customer') && Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
                $customer->is_guest = Tools::getValue('is_new_customer');
            }

            if ($this->config_vars['OPC_CHOICE_GROUP_CUSTOMER'] && Tools::getIsset('group_customer')) {
                $customer->id_default_group = (int) Tools::getValue('group_customer');
            }

            if (!$customer->add()) {
                $this->errors[] = $this->l('An error occurred while creating your account.');
            } else {
                $customer->cleanGroups();

                if ($this->config_vars['OPC_CHOICE_GROUP_CUSTOMER']
                    && Tools::getIsset('group_customer')
                    && $group_customer = Tools::getValue('group_customer', '')
                ) {
                    $customer->addGroups(array((int)$group_customer));
                }

                if (!$customer->is_guest) {
                    $customer->addGroups(array((int) $this->config_vars['OPC_DEFAULT_GROUP_CUSTOMER']));
                } else {
                    $customer->addGroups(array((int) Configuration::get('PS_GUEST_GROUP')));
                }

                //Registro de grupos adicionales a clientes nuevos.
                $groups_customer_additional = $this->config_vars['OPC_GROUPS_CUSTOMER_ADDITIONAL'];
                if (!empty($groups_customer_additional)) {
                    $groups_customer_additional = explode(',', $groups_customer_additional);
                    if (is_array($groups_customer_additional)) {
                        $customer->addGroups($groups_customer_additional);
                    }
                }

                if (!is_null($address_delivery)) {
                    $address_delivery->id_customer = (int) $customer->id;

                    if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA']) {
                        $address_delivery->firstname = $customer->firstname;
                        $address_delivery->lastname  = $customer->lastname;
                    }

                    if (!$address_delivery->save()) {
                        $this->errors[] = $this->l('An error occurred while creating your delivery address.');
                    }
                }

                if (!is_null($address_invoice) && $is_set_invoice) {
                    if (empty($address_invoice->id_customer) ||
                        $address_invoice->id_customer == $this->config_vars['OPC_ID_CUSTOMER']
                    ) {
                        $address_invoice->id_customer = $customer->id;
                    }

                    if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA']) {
                        $address_invoice->firstname = $customer->firstname;
                        $address_invoice->lastname  = $customer->lastname;
                    }

                    if (!$address_invoice->save()) {
                        $this->errors[] = $this->l('An error occurred while creating your billing address.');
                    }

                    if (is_null($address_delivery)) {
                        $address_delivery = $address_invoice;
                    }
                }

                if (is_array($this->errors) && !count($this->errors)) {
                    if (!$customer->is_guest) {
                        $this->sendConfirmationMail($customer, $password);
                    }

                    $emailverificationopc = $this->core->isModuleActive('emailverificationopc');
                    if ($emailverificationopc) {
                        $email_verified = $emailverificationopc->validateEmailVerifiedCustomer($customer, true);

                        if (!$email_verified) {
                            $this->warnings[] = $this->l(
                                'The customer was created properly but can not log in the store until you verify your email address in the link sent to your email.'
                            );

                            if (Tools::getIsset('sv')) {
                                $sql = 'UPDATE '._DB_PREFIX_.'customer SET email_verified = 1 WHERE id_customer='.(int)$customer->id;

                                if (Db::getInstance()->execute($sql)) {
                                    $this->singInCustomer($customer);
                                }
                            }

                            return false;
                        }
                    }

                    //loggin customer
                    $this->context->cookie->id_customer        = (int) $customer->id;
                    $this->context->cookie->customer_lastname  = $customer->lastname;
                    $this->context->cookie->customer_firstname = $customer->firstname;
                    $this->context->cookie->logged             = 1;
                    $customer->logged                          = 1;
                    $this->context->cookie->is_guest           = $customer->isGuest();
                    $this->context->cookie->passwd             = $customer->passwd;
                    $this->context->cookie->email              = $customer->email;

                    // Add customer to the context
                    $this->context->customer = $customer;

                    $nb_products = Cart::getNbProducts($this->context->cookie->id_cart);

                    if (Configuration::get('PS_CART_FOLLOWING')
                        && (empty($this->context->cookie->id_cart)
                        || $nb_products == 0)
                    ) {
                        $this->context->cookie->id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id);
                    }

                    // Update cart address
                    $this->context->cart->id_customer         = (int) $customer->id;
                    $this->context->cart->secure_key          = $customer->secure_key;

                    if (!$is_set_invoice) {
                        if (!is_null($address_delivery)) {
                            $this->context->cart->id_address_delivery = $address_delivery->id;
                            $this->context->cart->id_address_invoice = $address_delivery->id;

                            $this->updateCustomerAddress($customer->id, $address_delivery->id, 'delivery');
                        }
                    } else {
                        if (!is_null($address_delivery)) {
                            $this->context->cart->id_address_delivery = $address_delivery->id;
                            $this->updateCustomerAddress($customer->id, $address_delivery->id, 'delivery');
                        }
                        if (!is_null($address_invoice)) {
                            $this->context->cart->id_address_invoice = $address_invoice->id;
                            $this->updateCustomerAddress($customer->id, $address_invoice->id, 'invoice');
                        }
                    }

                    $this->context->cart->update();
                    $this->context->cart->setNoMultishipping();

                    $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    $this->context->cookie->write();

                    if (!is_null($address_delivery)) {
                        $array_post = array_merge((array) $customer, (array) $address_delivery);
                    } else {
                        $array_post = (array) $customer;
                    }

                    foreach ($array_post as $key => $value) {
                        $_POST[$key] = $value;
                    }

                    //support module: idxrecargoe - 1.0.2 - Innovadeluxe SL
                    if ($this->core->isModuleActive('idxrecargoe')) {
                        $idxrecargoeq = 0;

                        if (in_array('idxrecargoeq', $_POST)) {
                            $idxrecargoeq = Tools::getValue('idxrecargoeq') === 'on' ? 1 : 0;
                        }

                        $_POST['idxrecargoeq'] = $idxrecargoeq;
                    }

                    //support module: psgdpr - v1.0.0 - PrestaShop
                    if ($this->core->isModuleActive('psgdpr')) {
                        GDPRLog::addLog($customer->id, 'consent', $this->id, $this->context->cart->id_guest);
                    }

                    Hook::exec('actionCustomerAccountAdd', array(
                        '_POST'       => $_POST,
                        'newCustomer' => $customer,
                    ));
                }
            }
        }
    }

    /**
     * sendConfirmationMail
     * @param Customer $customer
     * @return bool
     */
    protected function sendConfirmationMail(Customer $customer, $password)
    {
        if (Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            Mail::Send(
                $this->context->language->id,
                'account',
                Mail::l('Welcome!'),
                array('{firstname}' => $customer->firstname,
                    '{lastname}'  => $customer->lastname,
                    '{email}'     => $customer->email,
                    '{passwd}'    => $password
                ),
                $customer->email,
                $customer->firstname.' '.$customer->lastname
            );
        }
    }

    public function singInCustomer($customer)
    {
        $emailverificationopc = $this->core->isModuleActive('emailverificationopc');
        if ($emailverificationopc) {
            $email_verified = $emailverificationopc->validateEmailVerifiedCustomer($customer);

            if (!$email_verified) {
                $this->errors[] = sprintf(
                    $this->l('To sign in the store must verify your email address on the link sent to %s'),
                    $customer->email
                );

                return false;
            }
        }

        $this->context->updateCustomer($customer);

        Hook::exec('actionAuthentication', array('customer' => $customer));

        // Login information have changed, so we check if the cart rules still apply
        CartRule::autoRemoveFromCart();
        CartRule::autoAddToCart();

        return true;
    }

    /**
     * Check the email and password sent, then sing in customer
     *
     * @return array (boolean success, array errors)
     */
    public function loginCustomer()
    {
        $is_logged = false;

        Hook::exec('actionAuthenticationBefore');

        $customer = new Customer();
        $authentication = $customer->getByEmail(
            Tools::getValue('email'),
            Tools::getValue('password')
        );

        if (isset($authentication->active) && !$authentication->active) {
            $this->errors[] = $this->l('Your account isn\'t available at this time, please contact us');
        } elseif (!$authentication || !$customer->id || $customer->is_guest) {
            $this->errors[] = $this->l('The email or password is incorrect. Verify your information and try again.');
        } else {
            if (is_array($this->errors) && count($this->errors) == 0) {
                $is_logged = $this->singInCustomer($customer);
            }
        }

        $results = array(
            'success' => $is_logged,
            'errors'  => $this->errors,
        );

        return $results;
    }

    /**
     * Return customer addresses.
     *
     * @return array Addresses
     */
    public function getAddresses($id_customer, $object)
    {
        $id_lang = $this->context->language->id;
        $shareOrder = (bool)$this->context->shop->getGroup()->share_order;
        $sql = 'SELECT DISTINCT a.*, cl.`name` AS country, s.name AS state, s.iso_code AS state_iso
            FROM `'._DB_PREFIX_.'address` a
            LEFT JOIN `'._DB_PREFIX_.'opc_customer_address` ca ON
                (a.`id_address` = ca.`id_address` AND
                    a.`id_customer` = ca.`id_customer`)
            LEFT JOIN `'._DB_PREFIX_.'country` c ON (a.`id_country` = c.`id_country`)
            LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.`id_country` = cl.`id_country`)
            LEFT JOIN `'._DB_PREFIX_.'state` s ON (s.`id_state` = a.`id_state`)
            '.($shareOrder ? '' : Shop::addSqlAssociation('country', 'c')).'
            WHERE
                `id_lang` = '.(int) $id_lang.' AND
                a.`id_customer` = '.(int)$id_customer.' AND
                a.`deleted` = 0 AND
                (ca.`object` = "'.pSQL($object).'" OR ca.`object` IS NULL)
            ORDER BY a.date_add DESC
            ';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return $result;
    }

    private function updateCustomerAddress($id_customer, $id_address, $object)
    {
        $query = 'UPDATE '._DB_PREFIX_.'opc_customer_address '
            . 'SET id_customer = '.(int)$id_customer.', `object` = "'.pSQL($object).'" '
            .' WHERE id_address = '.(int)$id_address;
        Db::getInstance()->execute($query);
    }

    /**
     * Return the address of customer logged.
     *
     * @return array (id_address_delivery, id_address_invoice, addresses)
     */
    public function loadAddressesCustomer()
    {
        $object = Tools::getValue('object');
        $rc_page = Tools::getValue('rc_page');

        $id_address = (int)$this->context->cart->id_address_delivery;

        $addresses = array();
        if (Validate::isLoadedObject($this->context->customer) && !empty($this->context->customer->id)) {
            $addresses = $this->getAddresses($this->context->customer->id, $object);

            if ($object == 'invoice' && $addresses) {
                $update_address_invoice = true;
                foreach ($addresses as $address) {
                    $id_address = (int)$address['id_address'];

                    if ($id_address == $this->context->cart->id_address_invoice) {
                        $id_address = (int)$this->context->cart->id_address_invoice;
                        $update_address_invoice = false;
                        break;
                    }
                }

                if ($update_address_invoice) {
                    $this->context->cart->id_address_invoice = $id_address;
                    $this->context->cart->update();
                }

                /*if ($this->context->cart->id_address_delivery == $this->context->cart->id_address_invoice
                    && (!$this->context->cart->isVirtualCart() || $this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL'])
                ) {
                    $id_address = 0;
                } else {
                    $id_address = (int)$this->context->cart->id_address_invoice;
                }*/
            }
        }

        if ($rc_page == 'addresses') {
            $id_address = 0;
        }

        $params = array(
            'id_address' => $id_address
        );

        if (count($addresses) > 0) {
            $params['addresses'] = $addresses;
        }

        $this->context->smarty->assign($params);

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/addresses.tpl')) {
            $html = $this->context->smarty->fetch(_PS_THEME_DIR_.'/modules/onepagecheckoutps/views/templates/front/addresses.tpl');
        } else {
            $html = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/addresses.tpl');
        }

        $params['html'] = $html;

        return $params;
    }

    /**
     * Re-use the address already created without a real customer.
     *
     * @return integer Id address available
     */
    public function getIdAddressAvailable($object = 'delivery')
    {
        /*$query = new DbQuery();
        $query->select('a.id_address');
        $query->from('address', 'a');
        $query->leftJoin(
            'opc_customer_address',
            'ca',
            'a.id_address = ca.id_address AND a.id_customer = ca.id_customer'
        );
        $query->where('a.id_customer = '.(int)$this->config_vars['OPC_ID_CUSTOMER']);
        $query->where('a.id_address NOT IN (SELECT id_address_delivery AS id_address FROM '._DB_PREFIX_.'cart WHERE id_address_delivery != 0 UNION SELECT id_address_invoice AS id_address FROM '._DB_PREFIX_.'cart WHERE id_address_delivery != 0)');
        $query->where('ca.object = "'.pSQL($object).'"');
        $query->where('a.deleted = 0');
        $query->where('a.active = 1');

        $id_address = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if (!empty($id_address)) {
            if ($this->context->customer->isLogged()) {
                $values = array('id_customer' => (int)$this->context->customer->id);
                $where = 'id_address = '.(int)$id_address;

                Db::getInstance(_PS_USE_SQL_SLAVE_)->update('address', $values, $where);
            }
        } else {
            $id_address = $this->createAddress($object);
        }

        return $id_address;*/

        return $this->createAddress($object);
    }

    /**
     * Create address with default values.
     *
     * @param int $id_customer
     * @return int id address created.
     */
    public function createAddress($object = 'delivery')
    {
        $values = array(
            'firstname'  => pSQL(FieldClass::getDefaultValue($object, 'firstname')),
            'lastname'   => pSQL(FieldClass::getDefaultValue($object, 'lastname')),
            'address1'   => pSQL(FieldClass::getDefaultValue($object, 'address1')),
            'city'       => pSQL(FieldClass::getDefaultValue($object, 'city')),
            'postcode'   => pSQL(FieldClass::getDefaultValue($object, 'postcode')),
            'id_country' => (int)FieldClass::getDefaultValue($object, 'id_country'),
            'id_state'   => (int)FieldClass::getDefaultValue($object, 'id_state'),
            'alias'      => pSQL(FieldClass::getDefaultValue($object, 'alias')),
            'dni'        => pSQL(FieldClass::getDefaultValue($object, 'dni')),
            'date_add'   => date('Y-m-d H:i:s'),
            'date_upd'   => date('Y-m-d H:i:s')
        );

        $address            = new Address();
        $fields_db_required = $address->getFieldsRequiredDatabase();
        foreach ($fields_db_required as $field) {
            $values[$field['field_name']] = pSQL(FieldClass::getDefaultValue($object, $field['field_name']));
        }

        if (empty($values['id_country'])) {
            //$values['id_country'] = (int)Configuration::get('PS_COUNTRY_DEFAULT');

            if (!$this->context->customer->isLogged() && (Configuration::get('PS_GEOLOCATION_ENABLED') || Configuration::get('PS_DETECT_COUNTRY'))) {
                if ($this->context->country->active) {
                    $values['id_country'] = $this->context->country->id;
                }
            }
        }

        if (!empty($values['id_country'])) {
            $field_state = FieldClass::getField($this->context->cookie->id_lang, $this->context->shop->id, $object, 'id_state');
            if ($field_state->active == '0') {
                if (Country::containsStates((int) $values['id_country'])) {
                    $states = State::getStatesByIdCountry((int) $values['id_country']);
                    if (count($states)) {
                        $values['id_state'] = (int)$states[0]['id_state'];
                    }
                }
            }

            if (empty($values['postcode'])) {
                $country = new Country((int) $values['id_country']);
                if (Validate::isLoadedObject($country)) {
                    $values['postcode'] = str_replace(
                        'C',
                        $country->iso_code,
                        str_replace(
                            'N',
                            '0',
                            str_replace(
                                'L',
                                'A',
                                $country->zip_code_format
                            )
                        )
                    );
                }
            }
        }

        if ($this->context->customer->isLogged() || $this->context->customer->isGuest()) {
            if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA'] && $object == 'delivery') {
                $values['firstname'] = pSQL($this->context->customer->firstname);
                $values['lastname']  = pSQL($this->context->customer->lastname);
            }

            if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA'] && $object == 'invoice') {
                $values['firstname'] = pSQL($this->context->customer->firstname);
                $values['lastname']  = pSQL($this->context->customer->lastname);
            }

            $values['id_customer'] = (int)$this->context->customer->id;
        } else {
            $values['id_customer'] = (int)$this->config_vars['OPC_ID_CUSTOMER'];
        }

        Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('address', $values);

        $id_address = Db::getInstance(_PS_USE_SQL_SLAVE_)->Insert_ID();

        $values_customer_address = array(
            'id_customer' => $values['id_customer'],
            'id_address' => $id_address,
            'object' => $object
        );
        Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('opc_customer_address', $values_customer_address);

        return $id_address;
    }

    /**
     * Support to module 'deliverydays' v1.7.1.0 from samdha.net
     *
     * The method setDate is called from hook header
     */
    public function supportModuleDeliveryDays()
    {
        $module = $this->core->isModuleActive('deliverydays', 'setDate');
        if ($module) {
            if (Tools::getIsset('deliverydays_day') || Tools::getIsset('deliverydays_timeframe')) {
                $module->setDate(
                    $this->context->cart,
                    Tools::getValue('deliverydays_day'),
                    Tools::getValue('deliverydays_timeframe')
                );
            }
        }
    }

    /**
     * Se colocan aca los modulos que requieren iniciar sesion primero antes de mostrar los demas pasos del checkout.
     */
    public function supportModulesRequiredAutentication()
    {
        if (!$this->context->customer->isLogged()) {
            $modules = array(
                'customersactivation', 'emailverificationopc'
            );

            foreach ($modules as $name_module) {
                if ($this->core->isModuleActive($name_module)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Support to module of Vat
     */
    /*public function supportModuleCheckVat($customer)
    {
    }*/

    /**
     * Support to module CPFUser
     */
    /*public function supportModuleCPFUser(&$customer)
    {
    }*/

    /**
     * Support modules of shipping that use pick up.
     *
     * @param string $module
     * @param object &$carrier
     * @param boolean &$is_necessary_postcode
     * @param boolean &$is_necessary_city
     */
    private function supportModulesShipping($module, $address, &$carrier, &$is_necessary_postcode, &$is_necessary_city, &$show_carrier)
    {
        //remove message unused on validator prestashop.
        $carrier = $carrier;
        $address = $address;
        $is_necessary_city = $is_necessary_city;
        $is_necessary_postcode = $is_necessary_postcode;

        switch ($module) {
            //v1.0.0 - miguel.cejas
            case 'envialiacarrier':
                if ($this->core->isModuleActive('envialiacarrier')) {
                    $cart = $this->context->cart;
                    if (isset($cart->id_carrier)) {
                        if ($cart->id_carrier > 0) {
                            $query = 'select c.id_carrier as ID_CARRIER
									from ' . _DB_PREFIX_ . 'carrier c
									join ' . _DB_PREFIX_ . 'envialia_tipo_serv ets
										on ets.ID_CARRIER = c.id_carrier
	 								where c.external_module_name = "envialiacarrier"
										and ets.T_INT = "0"
										and c.id_carrier = "'.$cart->id_carrier.'"';

                            $resultado = Db::getInstance()->executeS($query);

                            if ($resultado) {
                                $query = 'select ad.id_address, ad.postcode, ecp.V_COD_PROV from ' . _DB_PREFIX_ . 'address ad
                                                    left join ' . _DB_PREFIX_ . 'envialia_cp ecp
                                                        on ecp.id_state = ad.id_state
                                                    where ad.id_address = ' . $cart->id_address_delivery. '
                                                      and substring(ad.postcode,1,2) <> ecp.V_COD_PROV';

                                $resultado = Db::getInstance()->executeS($query);
                                if ($resultado) {
                                    $show_carrier = false;
                                }
                            }
                        }
                    }
                }
                break;
        }
    }

    /**
     * Check the DNI Spain if is valid.
     *
     * @param string $dni
     * @param int $id_country
     * @return boolean
     */
    public function checkDni($dni, $id_country)
    {
        $iso_country = Country::getIsoById($id_country);

        if ($this->config_vars['OPC_VALIDATE_DNI']) {
            if ($iso_country == 'ES') {
                require_once dirname(__FILE__).'/lib/nif-nie-cif.php';

                return isValidIdNumber($dni);
            } else if ($iso_country == 'CL') {
                $dni = preg_replace('/[^k0-9]/i', '', $dni);
                $dv  = Tools::substr($dni, -1);
                $numero = Tools::substr($dni, 0, Tools::strlen($dni)-1);
                $i = 2;
                $suma = 0;
                foreach (array_reverse(str_split($numero)) as $v) {
                    if ($i==8) {
                        $i = 2;
                    }
                    $suma += $v * $i;
                    ++$i;
                }
                $dvr = 11 - ($suma % 11);
                if ($dvr == 11) {
                    $dvr = 0;
                }
                if ($dvr == 10) {
                    $dvr = 'K';
                }
                if ($dvr == Tools::strtoupper($dv)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return Validate::isDniLite($dni) ? true : false;
        }
    }

    private function addFieldsRequired(&$fields, $name_object, $object)
    {
        $fields_tmp = array();

        $fields_db_required = $object->getFieldsRequiredDatabase();
        $fields_object      = ObjectModel::getDefinition($object);

        foreach ($fields_db_required as $field) {
            array_push($fields_tmp, $field['field_name']);
        }

        foreach ($fields_object['fields'] as $name_field => $field) {
            if (isset($field['required']) && $field['required'] == 1) {
                array_push($fields_tmp, $name_field);
            }
        }

        array_push($fields_tmp, 'id_country');
        array_push($fields_tmp, 'id_state');

        $fields_db = FieldClass::getAllFields(
            $this->context->cookie->id_lang,
            null,
            $name_object,
            null,
            null,
            $fields_tmp
        );

        foreach ($fields_db as $field) {
            if (!isset($fields[$field->name])
                || (isset($fields[$field->name])
                    && empty($fields[$field->name]->value))
            ) {
                $field->value = $field->default_value;

                $fields[$field->name] = $field;
            }

            $fields[$field->name]->required = 1;
        }
    }

    private function validateFieldsCustomer(&$fields, &$customer, &$password)
    {
        foreach ($fields as $name => $field) {
            //support module: pts_customertypeopc - v3.0.0
            if ($ctopc = $this->core->isModuleActive('pts_customertypeopc')) {
                if ($ctopc->isVisible()) {
                    $row_field = $ctopc->getStatusFieldOpc($field->id);

                    if ($row_field && is_array($row_field)) {
                        $field->active = true;
                        $field->required = $row_field['required'];
                    }
                }
            }

            if ($field->type == 'url') {
                $field->type = 'isUrl';

                if (!empty($field->value) && Tools::substr($field->value, 0, 4) != 'http') {
                    $field->value = 'http://'.$field->value;
                }
            } elseif ($field->type == 'number') {
                $field->type = 'isInt';
            } elseif ($field->type == 'isDate' || $field->type == 'isBirthDate') {
                if (!empty($field->value)) {
                    $field->value = date('Y-m-d', strtotime(str_replace('/', '-', $field->value)));
                }
            }

            if ($name == 'passwd') {
                $password = $field->value;
                //if logged the password does not matter
                if ($this->context->customer->isLogged()/* || $this->context->customer->isGuest()*/) {
                    //unset($fields[$name]);
                    if (empty($field->value)) {
                        continue;
                    }
                } else {
                    if (!$this->config_vars['OPC_REQUEST_PASSWORD']
                        || ($this->config_vars['OPC_REQUEST_PASSWORD']
                            && $this->config_vars['OPC_OPTION_AUTOGENERATE_PASSWORD']
                            && empty($field->value))
                        || (Configuration::get('PS_GUEST_CHECKOUT_ENABLED')
                        && Tools::getValue('is_new_customer') == 1)
                    ) {
                        $password = Tools::passwdGen();
                    }
                }

                $field->value = Tools::encrypt($password);
            } elseif ($name == 'email') {
                if (empty($field->value)) {
                    $field->value = date('His').'@auto-generated.opc';
                }

                if (!$this->context->customer->isLogged()
                    && Customer::customerExists($field->value)
                    //&& !Configuration::get('PS_GUEST_CHECKOUT_ENABLED')
                    && Tools::getValue('is_new_customer') == 1
                    && $this->config_vars['OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED']
                ) {
                    $this->errors[] = $this->l('An account using this email address has already been registered.');
                }
            } elseif ($name == 'current_passwd') {
                if (!empty($field->value)) {
                    $customer->{$name} = $field->value;
                }

                continue;
            }

            $valid = call_user_func(array('Validate', $field->type), $field->value);

            //check field required
            if ($field->required == 1 && empty($field->value)) {
                $this->errors[] = sprintf(
                    $this->l('The field %s is required.'),
                    ObjectModel::displayFieldName(
                        $name,
                        get_class($customer),
                        true
                    )
                );
            } elseif (!empty($field->value) && !$valid) {
                $this->errors[] = sprintf(
                    $this->l('The field %s is invalid.'),
                    ObjectModel::displayFieldName(
                        $name,
                        get_class($customer),
                        true
                    )
                );
            }

            if ($field->active == 0 && !empty($customer->{$name})) {
                continue;
            }

            $customer->{$name} = $field->value;
        }

        //$this->supportModuleCPFUser($customer);
    }

    private function validateFieldsAddress(&$fields, &$address)
    {
        foreach ($fields as $name => $field) {
            //support module: pts_customertypeopc - v3.0.0
            if ($ctopc = $this->core->isModuleActive('pts_customertypeopc')) {
                if ($ctopc->isVisible()) {
                    $row_field = $ctopc->getStatusFieldOpc($field->id);

                    if ($row_field && is_array($row_field)) {
                        $field->active = true;
                        $field->required = $row_field['required'];
                    }
                }
            }
            if ($field->type == 'url') {
                $field->type = 'isUrl';

                if (Tools::substr($field->value, 0, 4) != 'http') {
                    $field->value = 'http://'.$field->value;
                }
            } elseif ($field->type == 'number') {
                $field->type = 'isInt';
            } elseif ($field->type == 'isDate' || $field->type == 'isBirthDate') {
                $field->value = date('Y-m-d', strtotime(str_replace('/', '-', $field->value)));
            }

            $valid = call_user_func(array('Validate', $field->type), $field->value);

            //check field required
            if ($field->required == 1 && empty($field->value)) {
                if ($field->name != 'id_state') {
                    $this->errors[] = sprintf(
                        $this->l('The field %s is required.'),
                        ObjectModel::displayFieldName(
                            $name,
                            get_class($address),
                            true
                        )
                    );
                }
            } elseif (!empty($field->value) && !$valid) {
                //check field validated
                $this->errors[] = sprintf(
                    $this->l('The field %s is invalid.'),
                    ObjectModel::displayFieldName(
                        $name,
                        get_class($address),
                        true
                    )
                );
            }

            if ($field->active == 0 && !empty($address->{$name})) {
                continue;
            }

            $address->{$name} = $field->value;
        }

        if ($address->id_country) {
            // Check country
            if (!($country = new Country($address->id_country)) || !Validate::isLoadedObject($country)) {
                $this->errors[] = $this->l('Country cannot be loaded.');
            }

            if ((int) $country->contains_states) {
                if (!(int) $address->id_state) {
                    $this->errors[] = $this->l('This country requires you to chose a State.');
                } else {
                    $state = new State((int)$address->id_state);

                    if (Validate::isLoadedObject($state) && $state->id_country != $country->id) {
                        $address->id_state = null;
                    }
                }
            } else {
                $address->id_state = null;
            }

            if (!$country->active) {
                $this->errors[] = $this->l('This country is not active.');
            }

            // Check zip code format
            if ($country->zip_code_format && !$country->checkZipCode($address->postcode)) {
                //this fix the problem if the field postcode is disabled.
                if (!empty($address->postcode) && $field->active == 1) {
                    $this->errors[] = sprintf(
                        $this->l('The Zip/Postal code you\'ve entered is invalid. It must follow this format: %s'),
                        str_replace(
                            'C',
                            $country->iso_code,
                            str_replace(
                                'N',
                                '0',
                                str_replace(
                                    'L',
                                    'A',
                                    $country->zip_code_format
                                )
                            )
                        )
                    );
                } else {
                    $address->postcode = str_replace(
                        'C',
                        $country->iso_code,
                        str_replace(
                            'N',
                            '0',
                            str_replace(
                                'L',
                                'A',
                                $country->zip_code_format
                            )
                        )
                    );
                }
            } elseif (empty($address->postcode) && $country->need_zip_code) {
                $address->postcode = str_replace(
                    'C',
                    $country->iso_code,
                    str_replace(
                        'N',
                        '0',
                        str_replace(
                            'L',
                            'A',
                            $country->zip_code_format
                        )
                    )
                );
            }
            //$this->errors[] = $this->l('The Zip/Postal code is required.');
            // Check country DNI
            if (!empty($address->dni)) {
                if ($country->isNeedDni()
                    && (!$address->dni)
                    || !$this->checkDni($address->dni, $address->id_country)
                ) {
                    $this->errors[] = $this->l('The field identification number is invalid.');
                }
//					else
//					{
//						$query = new DbQuery();
//						$query->from('address');
//						$query->where(
//							'dni = "'.$address->dni.'"'.
//								($this->context->customer->isLogged() ? ' AND id_customer != '.$this->context->customer->id : '')
//						);
//						if (Db::getInstance()->executeS($query))
//							$this->errors[] = $this->l('The identification number has already been used.');
//					}
            } else {
                if (!$country->isNeedDni()) {
                    $address->dni = null;
                } else {
                    $address->dni = 0;
                }
            }
        }

        if (!Validate::isDate($address->date_add)) {
            $address->date_add = date('Y-m-d H:i:s');
        }
        if (!Validate::isDate($address->date_upd)) {
            $address->date_upd = $address->date_add;
        }

        Hook::exec('actionSubmitCustomerAddressForm', array('address' => &$address));
    }

    public function isSameAddress($delivery_address, $invoice_address)
    {
        $is_same = true;

        if ($delivery_address->id_country != $invoice_address->id_country) {
            $is_same = false;
        }
        if ($delivery_address->id_state != $invoice_address->id_state) {
            $is_same = false;
        }
        if ($delivery_address->alias != $invoice_address->alias) {
            $is_same = false;
        }
        if ($delivery_address->company != $invoice_address->company) {
            $is_same = false;
        }
        if ($delivery_address->lastname != $invoice_address->lastname) {
            $is_same = false;
        }
        if ($delivery_address->firstname != $invoice_address->firstname) {
            $is_same = false;
        }
        if ($delivery_address->address1 != $invoice_address->address1) {
            $is_same = false;
        }
        if ($delivery_address->address2 != $invoice_address->address2) {
            $is_same = false;
        }
        if ($delivery_address->postcode != $invoice_address->postcode) {
            $is_same = false;
        }
        if ($delivery_address->city != $invoice_address->city) {
            $is_same = false;
        }
        if ($delivery_address->other != $invoice_address->other) {
            $is_same = false;
        }
        if ($delivery_address->phone != $invoice_address->phone) {
            $is_same = false;
        }
        if ($delivery_address->phone_mobile != $invoice_address->phone_mobile) {
            $is_same = false;
        }
        if ($delivery_address->dni != $invoice_address->dni) {
            $is_same = false;
        }

        return $is_same;
    }

    public function loadAddress()
    {
        $id_address = (int) Tools::getValue('id_address');
        $object = Tools::getValue('object');
        $update_cart = (int) Tools::getValue('update_cart');

        if (empty($id_address)) {
            if ($object == 'delivery') {
                $id_address = $this->context->cart->id_address_delivery;
            } else if ($object == 'invoice') {
                $id_address = $this->context->cart->id_address_invoice;
            }
        }

        $address = new Address((int) $id_address);

        if (Validate::isLoadedObject($address)) {
            //verifica no cargar una direccion de otro cliente.
            //error que pasaba en la tienda de laboutiquedelaslanas.com. No sabemos si era algun modulo o modificacion.
            if (!$this->context->customer->isLogged() && !$this->context->customer->isGuest()) {
                if ($address->id_customer != $this->config_vars['OPC_ID_CUSTOMER']) {
                    $id_address = $this->getIdAddressAvailable($object);
                    $address = new Address((int) $id_address);

                    $this->context->cart->id_address_delivery = $id_address;
                    $this->context->cart->id_address_invoice = $id_address;
                    $this->context->cart->update();
                    $this->context->cart->setNoMultishipping();
                }
            }

            if ($update_cart) {
                if ($object == 'delivery') {
                    $this->context->cart->id_address_delivery = $id_address;
                } else if ($object == 'invoice') {
                    $this->context->cart->id_address_invoice = $id_address;
                }

                $this->context->cart->update();
                $this->context->cart->setNoMultishipping();
            }
        }

        //Consultar campos personalizados asociados a un carrito actual
        if (/*Validate::isLoadedObject($this->context->cart) && */$this->context->customer->id > 0) {
            $custom_fileds = FieldCustomerClass::getData(null, $this->context->customer->id);

            //Asigna los valores de los campos personalizados
            if ($custom_fileds) {
                foreach ($custom_fileds as $custom_field) {
                    if ($custom_field['object'] == 'customer') {
                        $this->context->customer->{$custom_field['name']} = $custom_field['value'];
                    } elseif ($custom_field['object'] == $object && (int)$custom_field['id_address'] === (int)$address->id) {
                            $address->{$custom_field['name']} = $custom_field['value'];
                    }
                }
            }
        }

        $result = array(
            'address' => $address,
            'customer' => $this->context->customer,
        );

        return $result;
    }

    /**
     * Load options shipping.
     *
     * @return array
     */
    public function loadCarrier($order_controller)
    {
        $set_id_customer_opc    = false;
        $delivery_options       = array();

        if (!$this->context->cart->isVirtualCart()) {
            if ($this->context->customer->isLogged()) {
                $address_customer = $this->context->customer->getAddresses($this->context->cart->id_lang);
                if (empty($address_customer)) {
                    $this->errors = $this->l('It is necessary to create an address to be able to show the different shipping options.');
                }
            }

            if (is_array($this->errors) && !count($this->errors)) {
                $delivery_address = new Address($this->context->cart->id_address_delivery);

                if (Validate::isLoadedObject($delivery_address)) {
                    //assign opc customer to cookie, customer and cart to calculare fine the prices of carriers
                    if (empty($this->context->cookie->id_customer)) {
                        $module_exception = false;

                        if (!$module_exception) {
                            $this->context->cookie->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];

                            if (empty($this->context->customer->id)) {
                                $this->context->customer = new Customer($this->config_vars['OPC_ID_CUSTOMER']);
                                $this->context->customer->logged = 1;
                            }

                            if (empty($this->context->cart->id_customer)) {
                                $this->context->cart->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];
                            }

                            $set_id_customer_opc = true;
                        }
                    }

                    if (empty($delivery_address->id_country)) {
                        $this->errors[] = $this->l('Select a country to show the different shipping options.');
                    } else if (empty($delivery_address->id_state)) {
                        if (Country::containsStates((int) $delivery_address->id_country)) {
                            $this->errors[] = $this->l('Select a state to show the different shipping options.');
                        }
                    }
                } else {
                    $this->l('This address is invalid. Sign out of session and login again.');
                }
            }

            if (is_array($this->errors) && !count($this->errors)) {
                $address = new Address($this->context->cart->id_address_delivery);
                $delivery_option = $order_controller->getCheckoutSession()->getSelectedDeliveryOption();
                $delivery_options = $order_controller->getCheckoutSession()->getDeliveryOptions();

                if (!count($delivery_options)) {
                    $this->errors[] = $this->l('There are no shipping methods available for your address.');
                }

                $is_necessary_postcode = false;
                $is_necessary_city     = false;
                $show_carrier          = true;

                $delivery_options_tmp = array();
                foreach ($delivery_options as $id_carrier => $carrier) {
                    //support module of shipping for pick up.
                    if (!empty($carrier['external_module_name']) && !$this->config_vars['OPC_SHIPPING_COMPATIBILITY']) {
                        $this->supportModulesShipping(
                            $carrier['external_module_name'],
                            $address,
                            $carrier,
                            $is_necessary_postcode,
                            $is_necessary_city,
                            $show_carrier
                        );
                    }

                    //support module estimateshippingcost - V1.0.5 - PresTeamShop
                    $estimateshippingcost = $this->core->isModuleActive('estimateshippingcost');
                    if ($estimateshippingcost) {
                        if (version_compare($estimateshippingcost->version, '1.0.5') >= 0) {
                            $delivery_options[$id_carrier]['estimate_days'] = $carrier['estimate_days'] = $estimateshippingcost->getEstimateDeliveryDays(
                                $carrier['name'],
                                $delivery_address->id_country,
                                $delivery_address->id_state,
                                $delivery_address->city,
                                $delivery_address->postcode
                            );
                        }
                    }

                    if ($show_carrier) {
                        $delivery_options_tmp[$id_carrier] = $carrier;
                    }
                }

                $delivery_options = $delivery_options_tmp;

                if (!$is_necessary_postcode) {
                    if ($this->config_vars['OPC_FORCE_NEED_POSTCODE']) {
                        $is_necessary_postcode = true;
                    } else {
                        $carriers_postcode = explode(
                            ',',
                            $this->config_vars['OPC_MODULE_CARRIER_NEED_POSTCODE']
                        );
                        foreach ($carriers_postcode as $carrier) {
                            if ($this->core->isModuleActive($carrier)) {
                                $is_necessary_postcode = true;
                            }
                        }
                    }
                }

                if (!$is_necessary_city) {
                    if ($this->config_vars['OPC_FORCE_NEED_CITY']) {
                        $is_necessary_city = true;
                    } else {
                        $carriers_city = explode(',', $this->config_vars['OPC_MODULE_CARRIER_NEED_CITY']);

                        foreach ($carriers_city as $carrier) {
                            if ($this->core->isModuleActive($carrier)) {
                                $is_necessary_city = true;
                            }
                        }
                    }
                }

                if ($is_necessary_city) {
                    $default_value = FieldClass::getDefaultValue('delivery', 'city');

                    if (empty($address->city) || $address->city == $default_value) {
                        $this->errors = $this->l('You need to place a city to show shipping options.');
                    }
                }

                if ($is_necessary_postcode) {
                    $country = new Country($address->id_country);

                    if (Validate::isLoadedObject($country)) {
                        $default_value = FieldClass::getDefaultValue('delivery', 'postcode');

                        if (empty($default_value)) {
                            $default_value = str_replace(
                                'C',
                                $country->iso_code,
                                str_replace(
                                    'N',
                                    '0',
                                    str_replace(
                                        'L',
                                        'A',
                                        $country->zip_code_format
                                    )
                                )
                            );
                        }

                        if ($country->need_zip_code && !empty($country->zip_code_format)) {
                            if (empty($address->postcode) || $address->postcode == $default_value || !$country->checkZipCode($address->postcode)) {
                                $this->errors = $this->l('You need to place a post code to show shipping options.');
                            }
                        }
                    }
                }

                $this->context->smarty->assign(array(
                    'id_address' => $order_controller->getCheckoutSession()->getIdAddressDelivery(),
                    'delivery_options' => $delivery_options,
                    'delivery_option' => $delivery_option,
                    'is_necessary_postcode' => $is_necessary_postcode,
                    'is_necessary_city' => $is_necessary_city,
                ));
            }
        }

        /* Compatibility deliverydate - V1.6.2 of MARICHAL Emmanuel */
        if ($this->core->isModuleActive('deliverydate')) {
            $position = Configuration::get('DELIVERYDATE_POSITION');
            $this->context->smarty->assign(array(
                'deliverydate_position' => !empty($position) ? $position : 'bottom',
                'deliverydate_reason' => Configuration::get('DELIVERYDATE_REASON'),
            ));
        }

        $templateVars = array(
            'ONEPAGECHECKOUTPS_IMG' => $this->onepagecheckoutps_dir.'views/img/',
            'CONFIGS' => $this->config_vars,
            'is_virtual_cart' => (int)$order_controller->getCheckoutSession()->getCart()->isVirtualCart(),
            'hasError' => !empty($this->errors),
            'errors' => $this->errors,
            //native vars
            'hookDisplayBeforeCarrier' => Hook::exec('displayBeforeCarrier', array('cart' => $order_controller->getCheckoutSession()->getCart())),
            'hookDisplayAfterCarrier' => Hook::exec('displayAfterCarrier', array('cart' => $order_controller->getCheckoutSession()->getCart())),
            'recyclable' => $order_controller->getCheckoutSession()->isRecyclable(),
            'delivery_message' => (method_exists($order_controller->getCheckoutSession(), 'getMessage')) ? $order_controller->getCheckoutSession()->getMessage() : '',
            'recyclablePackAllowed' => $order_controller->checkoutDeliveryStep->isRecyclablePackAllowed(),
            'gift' => array(
                'allowed' => $order_controller->checkoutDeliveryStep->isGiftAllowed(),
                'isGift' => $order_controller->getCheckoutSession()->getGift()['isGift'],
                'label' => $this->l('I would like my order to be gift wrapped').$order_controller->checkoutDeliveryStep->getGiftCostForLabel(),
                'message' => $order_controller->getCheckoutSession()->getGift()['message'],
            ),
            'identifier' => 'checkout-delivery-step',
            'step_is_complete' => 0,
            'step_is_reachable' => 0,
            'step_is_current' => 0,
            'position' => '',
            'title' => '',
            'delivery_options' => $delivery_options
        );

        $this->context->smarty->assign($templateVars);

        if ($set_id_customer_opc) {
            $this->context->customer         = new Customer();
            $this->context->customer->logged = 0;
            unset($this->context->cookie->id_customer);

            $this->context->cart->id_customer = null;
            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
        }

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/carrier.tpl')) {
            $html = $this->context->smarty->fetch(_PS_THEME_DIR_.'/modules/onepagecheckoutps/views/templates/front/carrier.tpl');
        } else {
            $html = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/carrier.tpl');
        }

        return $html;
    }

    /**
     * Load payment methods.
     *
     * @return html
     */
    public function loadPayment()
    {
        if (method_exists($this->context->cart, 'checkQuantities')) {
            if (is_array($product = $this->context->cart->checkQuantities(true))) {
                return '<p class="alert alert-warning">'.sprintf($this->l('An item (%s) in your cart is no longer available in this quantity. You cannot proceed with your order until the quantity is adjusted.'), $product['name']).'</p>';
            }
        }

        //soluciona problema con algunos metodos de pago que no les llega este objecto cargado
        //se desconoce la causa.
        $this->context->smarty->assign('link', $this->context->link);

        $payment_need_register = false;

        $paymentOptionsFinder = new PaymentOptionsFinder();

        //--------------------------------------------
        $set_id_customer_opc = false;
        if (empty($this->context->cookie->id_customer)) {
            $this->context->cookie->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];

            if (empty($this->context->customer->id)) {
                $this->context->customer = new Customer($this->config_vars['OPC_ID_CUSTOMER']);
                $this->context->customer->logged = 1;
            }

            if (empty($this->context->cart->id_customer)) {
                $this->context->cart->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];
            }

            $set_id_customer_opc = true;
        }
        //--------------------------------------------

        $payment_options = $paymentOptionsFinder->present();

        if ($payment_options) {
            foreach ($payment_options as $name_module => &$module_options) {
                foreach ($module_options as &$option) {
                    $path_image = _PS_MODULE_DIR_.$this->name.'/views/img/payments/'.$name_module;

                    $module_payment = Module::getInstanceByName($name_module);

                    $option['id_module_payment'] = $module_payment->id;

                    if (empty($option['logo'])) {
                        if (file_exists($path_image.'.png')) {
                            $option['logo'] = $this->onepagecheckoutps_dir.'views/img/payments/'.$name_module.'.png';
                        } elseif (file_exists($path_image.'.gif')) {
                            $option['logo'] = $this->onepagecheckoutps_dir.'views/img/payments/'.$name_module.'.gif';
                        } elseif (file_exists($path_image.'.jpeg')) {
                            $option['logo'] = $this->onepagecheckoutps_dir.'views/img/payments/'.$name_module.'.jpeg';
                        }
                    }

                    $id_payment = PaymentClass::getIdPaymentBy('id_module', (int) $option['id_module_payment']);

                    $payment = new PaymentClass($id_payment, $this->context->language->id);
                    if (Validate::isLoadedObject($payment)) {
                        $display_module = true;

                        if ($payment->test_mode) {
                            $display_module = false;
                            $my_ip = Tools::getRemoteAddr();
                            $array_ips_debug = explode(',', $payment->test_ip);

                            if (in_array($my_ip, $array_ips_debug)) {
                                $display_module = true;
                            }
                        }

                        if (!$display_module) {
                            unset($payment_options[$name_module]);
                            continue;
                        }

                        if ($payment->name_image == 'no-image.png') {
                            if (empty($option['logo'])) {
                                $option['logo'] = '';
                            }
                        } else if (!empty($payment->name_image)) {
                            $option['logo'] = $this->onepagecheckoutps_dir.'views/img/payments/'.$payment->name_image;
                        }

                        if ($payment->title) {
                            $option['title_opc'] = $payment->title;
                        }
                        if ($payment->description) {
                            $option['description_opc'] = $payment->description;
                        }

                        $option['force_display'] = $payment->force_display;
                        if ($payment->force_display) {
                            $option['action'] = $this->context->link->getModuleLink(
                                $this->name,
                                'payment',
                                array('pm' => $name_module)
                            );
                        }
                    }
                }
            }
        }

        //--------------------------------------------
        if ($set_id_customer_opc) {
            $this->context->customer = new Customer();
            $this->context->customer->logged = 0;
            unset($this->context->cookie->id_customer);

            $this->context->cart->id_customer = null;
            $this->context->cart->update();
        }
        //--------------------------------------------

        $total_order = $this->context->cart->getOrderTotal();

        $templateVars = array(
            'payment_options' => $payment_options,
            'selected_payment_option' => false,
            'CONFIGS' => $this->config_vars,
            'payment_need_register' => $payment_need_register,
            'total_order' => $total_order
        );

        $this->context->smarty->assign($templateVars);

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/payment.tpl')) {
            $html = $this->context->smarty->fetch(_PS_THEME_DIR_.'/modules/onepagecheckoutps/views/templates/front/payment.tpl');
        } else {
            $html = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/payment.tpl');
        }

        //Support module: stripe_official - v2.0.4 - 202 ecommerce
        $stripe_official = $this->core->isModuleActive('stripe_official');
        if ($stripe_official) {
            $stripe_official->hookHeader();
            $this->context->smarty->assign('js_def', Media::getJsDef());
            $javascript = $this->context->smarty->fetch(_PS_ALL_THEMES_DIR_.'javascript.tpl');
            $html .= $javascript;
        }

        return $html;
    }

    /**
     * Remove address.
     *
     * @return array
     */
    public function removeAddress()
    {
        if ($this->context->customer->isLogged()) {
            $id_address = (int)Tools::getValue('id_address');

            $address = new Address($id_address);

            if (Validate::isLoadedObject($address)) {
                if ($this->context->customer->id == $address->id_customer) {
                    //Validate if address is used
                    $sql = new DbQuery();
                    $sql->select('id_address_delivery, id_address_invoice');
                    $sql->from('cart');
                    $sql->where('id_address_delivery = '.$id_address.' OR id_address_invoice = '.$id_address);

                    $address_in_use = Db::getInstance()->executeS($sql);

                    if ($address_in_use) {
                        $query = 'UPDATE '._DB_PREFIX_.'address SET deleted = 1 WHERE id_address = '.(int)$id_address;
                        Db::getInstance()->execute($query);
                    } else {
                        $query = 'DELETE FROM '._DB_PREFIX_.'address WHERE id_address = '.(int)$id_address;
                        Db::getInstance()->execute($query);
                    }

                    return array(
                        'message_code' => $this->core->CODE_SUCCESS
                    );
                }
            }
        }

        return array(
            'message_code' => $this->core->CODE_ERROR
        );
    }

    /**
     * Update address.
     *
     * @return array
     */
    public function updateAddress()
    {
        $id_customer = (int)Tools::getValue('id_customer');
        $id_address = (int)Tools::getValue('id_address');
        $object = Tools::getValue('object');
        $update_cart = Tools::getValue('update_cart');
        $is_set_invoice = (bool)Tools::getValue('is_set_invoice');
        $fields = Tools::jsonDecode(Tools::getValue('fields'));
        $id_address_old = $id_address;
        $update_address_order = false;

        if (!Validate::isLoadedObject($this->context->cart) && !Tools::getIsset('rc_page')) {
            return array(
                'message_code' => $this->core->CODE_ERROR
            );
        }

        if (empty($id_address)) {
            if ($this->context->customer->isLogged()) {
                $id_address = $this->getIdAddressAvailable($object);
            } else {
                if ($object == 'delivery') {
                    $id_address = $this->context->cart->id_address_delivery;
                } else if ($object == 'invoice') {
                    $id_address = $this->context->cart->id_address_invoice;

                    if ($update_cart
                        && $this->context->cart->id_address_delivery == $this->context->cart->id_address_invoice
                        && ($this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL'] || !$this->context->cart->isVirtualCart())
                    ) {
                        if (!$this->context->customer->isLogged()) {
                            $id_address = $this->getIdAddressAvailable('invoice');
                        }
                    }
                }
            }
        } else {
            //se crea una nueva direccion en el caso de la direccion sea modificada y este usada ya en un pedido
            if ($this->context->customer->isLogged() && !$update_cart) {
                $address = new Address($id_address);

                if ($address->isUsed()) {
                    if ($address->delete()) {
                        $id_address = $this->getIdAddressAvailable($object);
                        $update_cart = true;
                        $update_address_order = true;
                    }
                }
            }
        }

        $address = new Address($id_address);
        $customer = new Customer($id_customer);
        $password = null;
        $custom_fields = array();

        $this->validateFields($fields, $customer, $address, $address, $password, $is_set_invoice, $custom_fields);

//        if (count($this->errors) > 0) {
//            return array(
//                'message_code' => $this->core->CODE_ERROR
//            );
//        }

        if (($object == 'delivery' && $this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA'])
            || ($object == 'invoice' && $this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA'])
        ) {
            if ($this->context->customer->isLogged() || $this->context->customer->isGuest()) {
                $address->firstname = $this->context->customer->firstname;
                $address->lastname  = $this->context->customer->lastname;
            } else {
                $address->firstname = '.';
                $address->lastname  = '.';
            }
        }

        if (Validate::isLoadedObject($address)) {
            if (empty($address->id_country)) {
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update(
                    'address',
                    array('id_country' => 0),
                    'id_address = '.$address->id
                );

                return array(
                    'message_code' => $this->core->CODE_ERROR,
                    'message' => $this->l('Select a country to show the different shipping options.')
                );
            }

            $country = new Country($address->id_country);
            if ($country->contains_states && empty($address->id_state)) {
                $address->id_state = null;
            } else {
                if (!$country->contains_states && !empty($address->id_state)) {
                    $address->id_state = null;
                }
            }

            if (count($custom_fields) > 0) {
                foreach ($custom_fields as $custom_field) {
                    $this->saveCustomFields($custom_field, $address->id);
                }
            }

            $address->update();

            if ($object == 'delivery') {
                $this->context->country->id_zone = Address::getZoneById((int) $address->id);

                if ($update_cart) {
                    $this->context->cart->id_address_delivery = $address->id;
                    if (!$is_set_invoice || $update_address_order) {
                        $this->context->cart->id_address_invoice = $address->id;
                    }
                    $this->context->cart->update();
                    $this->context->cart->setNoMultishipping();
                }
            } else if ($object == 'invoice') {
                if ($update_cart) {
                    if ($update_address_order && $this->context->cart->id_address_delivery == $id_address_old) {
                        $this->context->cart->id_address_delivery = $address->id;
                    }
                    if (!$this->config_vars['OPC_SHOW_DELIVERY_VIRTUAL'] && $this->context->cart->isVirtualCart()) {
                        $this->context->cart->id_address_delivery = $address->id;
                    }

                    $this->context->cart->id_address_invoice = $address->id;
                    $this->context->cart->update();
                    $this->context->cart->setNoMultishipping();
                }
            }

            return array(
                'message_code' => $this->core->CODE_SUCCESS,
                'id_address_delivery' => (int)$this->context->cart->id_address_delivery,
                'id_address_invoice' => (int)$this->context->cart->id_address_invoice
            );
        }

        return array(
            'message_code' => $this->core->CODE_ERROR
        );
    }

    /**
     * Remove invoice address.
     *
     * @return array
     */
    public function removeAddressInvoice()
    {
        if (!$this->context->customer->isLogged() && !$this->context->customer->isGuest()) {
            if ($this->context->cart->id_address_delivery != $this->context->cart->id_address_invoice) {
                $query = 'DELETE FROM '._DB_PREFIX_.'address WHERE id_address = '.(int)$this->context->cart->id_address_invoice;
                Db::getInstance()->execute($query);
                $query = 'DELETE FROM '._DB_PREFIX_.'opc_customer_address WHERE id_address = '.(int)$this->context->cart->id_address_invoice;
                Db::getInstance()->execute($query);
            }
        }
        $this->context->cart->id_address_invoice = $this->context->cart->id_address_delivery;
        $this->context->cart->update();
        $this->context->cart->setNoMultishipping();
    }

    /**
     * Load summary of cart.
     *
     * @return html
     */
    public function loadReview()
    {
        $set_id_customer_opc = false;
        if (!$this->context->cookie->id_customer) {
            $this->context->cookie->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];

            if (empty($this->context->customer->id)) {
                $this->context->customer = new Customer($this->config_vars['OPC_ID_CUSTOMER']);
                $this->context->customer->logged = true;
            }

            if (empty($this->context->cart->id_customer)) {
                $this->context->cart->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];
            }

            $set_id_customer_opc = true;

            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
        }

        if ($old_message = Message::getMessageByCartId((int) $this->context->cart->id)) {
            $this->context->smarty->assign('oldMessage', $old_message['message']);
        }

        $conditionsToApproveFinder = new ConditionsToApproveFinder($this->context, $this->context->getTranslator());

        $this->context->smarty->assign(array(
            'currency' => $this->context->controller->getTemplateVarCurrency(),
            'customer' => $this->context->controller->getTemplateVarCustomer(),
            'language' => $this->context->controller->objectPresenter->present($this->context->language),
            'page' => $this->context->controller->getTemplateVarPage(),
            'shop' => $this->context->controller->getTemplateVarShop(),
            'urls' => $this->context->controller->getTemplateVarUrls(),
            'configuration' => $this->context->controller->getTemplateVarConfiguration(),
            'link' => $this->context->link,
            'ps_stock_management' => Configuration::get('PS_STOCK_MANAGEMENT'),
            'customer' => ($this->context->customer->isLogged() ? $this->context->customer : false),
            'onepagecheckoutps' => $this,
            'CONFIGS'               => $this->config_vars,
            'ONEPAGECHECKOUTPS_IMG' => $this->onepagecheckoutps_dir.'views/img/',
            'ONEPAGECHECKOUTPS_TPL' => $this->onepagecheckoutps_tpl,
            'PS_WEIGHT_UNIT'        => Configuration::get('PS_WEIGHT_UNIT'),
            'conditions_to_approve' => $conditionsToApproveFinder->getConditionsToApproveForTemplate(),
            'total_cart' => Tools::displayPrice(
                $this->context->cart->getOrderTotal(),
                new Currency($this->context->cart->id_currency),
                false
            ),
            'PS_LABEL_OOS_PRODUCTS_BOA' => Configuration::get('PS_LABEL_OOS_PRODUCTS_BOA', $this->context->language->id),
        ));

        $presenter = new CartPresenter();
        $presented_cart = $presenter->present($this->context->cart);
        
        if ($this->config_vars['OPC_SHOW_DELIVERY_TIME']
            && (isset($presented_cart['products']) && $presented_cart['products'])
        ) {
            foreach ($presented_cart['products'] as &$cart_p) {
                $product = new ProductCore($cart_p->id_product);

                if ($product->additional_delivery_times) {
                    if ($cart_p->stock_quantity > 0 && ($cart_p->cart_quantity <= $cart_p->quantity_available)) {
                        if ((int)$product->additional_delivery_times === 1) {
                            $cart_p->delivery_information_opc = ConfigurationCore::get(
                                'PS_LABEL_DELIVERY_TIME_AVAILABLE',
                                $this->context->language->id
                            );
                        } else {
                            $cart_p->delivery_information_opc = $product->delivery_in_stock[$this->context->language->id];
                        }
                    } else {
                        if ((int)$product->additional_delivery_times === 1) {
                            $cart_p->delivery_information_opc = Configuration::get(
                                'PS_LABEL_DELIVERY_TIME_OOSBOA',
                                $this->context->language->id
                            );
                        } else {
                            $cart_p->delivery_information_opc = $product->delivery_out_stock[$this->context->language->id];
                        }
                    }
                }
            }
        }

        $this->context->smarty->assign(array('cart' => $presented_cart));

        $total_free_ship = 0;
        $free_ship       = Tools::convertPrice(
            (float) Configuration::get('PS_SHIPPING_FREE_PRICE'),
            new Currency((int) $this->context->cart->id_currency)
        );

        if (empty($free_ship)) {
            $carrier = new Carrier($this->context->cart->id_carrier);

            if (Validate::isLoadedObject($carrier)) {
                if ($carrier->shipping_method == Carrier::SHIPPING_METHOD_PRICE && $carrier->is_free == 0) {
                    $total_products = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                    $ranges = RangePrice::getRanges((int)$carrier->id);
                    $id_zone = Address::getZoneById((int)$this->context->cart->id_address_delivery);

                    foreach ($ranges as $range) {
                        $query = new DbQuery();
                        $query->select('price');
                        $query->from('delivery');
                        $query->where('id_range_price = '.(int)$range['id_range_price']);
                        $query->where('id_zone = '.(int)$id_zone);
                        $query->where('id_carrier = '.(int)$carrier->id);
                        $cost_shipping = Db::getInstance()->getValue($query);

                        $delimiter1 = Tools::convertPrice($range['delimiter1'], $this->context->currency);
                        $cost_shipping = Tools::convertPrice($cost_shipping, $this->context->currency);

                        if ($cost_shipping == 0 && $total_products < $delimiter1) {
                            $free_ship = $delimiter1;
                            break;
                        }
                    }
                }
            }
        }

        if ($free_ship) {
            $discounts         = $this->context->cart->getCartRules();
            $total_discounts   = $this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
            $total_products_wt = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
            $total_free_ship   = $free_ship - ($total_products_wt - $total_discounts);

            foreach ($discounts as $discount) {
                if ($discount['free_shipping'] == 1) {
                    $total_free_ship = 0;
                    break;
                }
            }
        }

        $percent_free_shipping = ($total_free_ship > 0) ? (100-(($total_free_ship * 100) / $free_ship)) : 0;
        $total_free_ship = Tools::displayPrice($total_free_ship, $this->context->currency);

        $this->context->smarty->assign('price_0', Tools::displayPrice(0, $this->context->currency));
        $this->context->smarty->assign('free_ship', $total_free_ship);
        $this->context->smarty->assign('free_ship_preferences', Tools::displayPrice($free_ship, $this->context->currency));
        $this->context->smarty->assign('percent_free_shipping', $percent_free_shipping);

        $this->addModulesExtraFee();

        if ($set_id_customer_opc) {
            $this->context->customer = new Customer();
            $this->context->customer->logged = false;
            unset($this->context->cookie->id_customer);

            $this->context->cart->id_customer = null;
            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
        }

        $this->context->smarty->assign(array(
            'is_logged' => $this->context->customer->isLogged(),
            'is_guest' => $this->context->customer->isGuest()
        ));

        $html = '';

        // Check minimal amount
        $minimal_purchase = $this->checkMinimalPurchase();
        if (!empty($minimal_purchase)) {
            $this->context->smarty->assign('minimal_purchase', $minimal_purchase);
        }

        //Compatibilidad con modulo attributewizardpro
        if ($module = $this->core->isModuleActive('attributewizardpro')) {
            $this->context->smarty->assign('attributewizardpro', $module);
        }

        //Compatibilidad con modulo pproperties v3.0.3 by psandmore
        if ($module = $this->core->isModuleActive('pproperties')) {
            $this->context->smarty->assign('pproperties', $module);
        }

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/review.tpl')) {
            $html .= $this->context->smarty->fetch(_PS_THEME_DIR_.'/modules/onepagecheckoutps/views/templates/front/review.tpl');
        } else {
            $html .= $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/review.tpl');
        }

        /* Compatibilidad: iqitthemeeditor - V4.2.1 de IQIT-COMMERCE.COM */
        if (Module::isInstalled('iqitthemeeditor')) {
            $iqitthemeeditor = Module::getInstanceByName('iqitthemeeditor');

            $optionsData    = Configuration::get($iqitthemeeditor->cfgName . 'options');
            $options        = $iqitthemeeditor->getOptions($optionsData);

            $this->context->smarty->assign('iqitTheme', $options);
        }

        if (file_exists(_PS_THEME_DIR_.'modules/onepagecheckoutps/views/templates/front/review_footer.tpl')) {
            $html .= $this->context->smarty->fetch(_PS_THEME_DIR_.'/modules/onepagecheckoutps/views/templates/front/review_footer.tpl');
        } else {
            $html .= $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/review_footer.tpl');
        }

        return $html;
    }

    public function addModulesExtraFee()
    {
        //------------------------------------------------------------------------
        $set_id_customer_opc = false;
        if (!$this->context->cookie->id_customer) {
            $this->context->cookie->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];

            if (empty($this->context->customer->id)) {
                $this->context->customer = new Customer($this->config_vars['OPC_ID_CUSTOMER']);
                $this->context->customer->logged = true;
            }

            if (empty($this->context->cart->id_customer)) {
                $this->context->cart->id_customer = $this->config_vars['OPC_ID_CUSTOMER'];
            }

            $set_id_customer_opc = true;

            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
        }
        //------------------------------------------------------------------------

        $payment_modules_fee = array();

        //Dinamic Total Order
        $cartPresenter = new CartPresenter();
        $presented_cart = $cartPresenter->present($this->context->cart);

        if (isset($presented_cart["totals"]["total"]["amount"]) && $presented_cart["totals"]["total"]["amount"]) {
            $total = $presented_cart["totals"]["total"]["amount"];
        } else {
            $total = $this->context->cart->getOrderTotal();
        }

        $label_fee = $this->l('Additional fees for payment').':';
        $label_fee_tax = $this->l('Fee tax').':';
        $label_total = $this->l('Total + Fee');

        //comaptibilidad con modulo cashondeliveryplus
        $module = $this->core->isModuleActive('cashondeliveryplus');
        if ($module !== false) {
            $fee_tax = 0;

            if (method_exists($module, 'getFeeForCart')) {//with tax
                $fee = $module->getFeeForCart($this->context->cart->id_carrier, 0, $this->context->cart, true);
                $fee_without = $module->getFeeForCart($this->context->cart->id_carrier, 0, $this->context->cart, false);
                $fee_tax = $fee - $fee_without;
                $fee = $fee_without;
            } else {
                $fee     = Configuration::get('COD_FEE');
                $feefree = Configuration::get('COD_FEEFREE');

                if ($feefree > 0 && $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) > $feefree) {
                    $fee = 0;
                }
            }

            $payment_modules_fee['cashondeliveryplus'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_fee_tax' => $label_fee_tax,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'fee_tax' => Tools::displayPrice($fee_tax),
                'total_fee' => Tools::displayPrice($total + $fee + $fee_tax)
            );
        }

        //comaptibilidad con modulo codfeeiw - v2.1.3 - presta-apps
        $module = $this->core->isModuleActive('codfeeiw', 'getFee');
        if ($module !== false) {
            $fee = $module->getFee($this->context->cart);

            $payment_modules_fee['codfeeiw'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        //comaptibilidad con modulo paypal v7.4.2.0 - AlabazWeb Pro
        $module = $this->core->isModuleActive('paypal', 'getCost');
        if ($module !== false) {
            $fee = $module->getCost($this->context->cart, true);
            $fee_without_tax = $module->getCost($this->context->cart, false);
            $fee_taxes = $fee-$fee_without_tax;
            if ($fee > 0) {
                $payment_modules_fee['paypal'] = array(
                    'reload' => true,
                    'id' => $module->id,
                    'label_fee' => $label_fee,
                    'label_total' => $label_total,
                    'label_fee_tax' => $label_fee_tax,
                    'fee' => Tools::displayPrice($fee),
                    'fee_tax' => $fee_taxes>0?Tools::displayPrice($fee_taxes):'',
                    'total_fee' => Tools::displayPrice($total + $fee)
                );
            }
        }

        //comaptibilidad con modulo megareembolso v2.9.2 - AlabazWeb Pro
        $module = $this->core->isModuleActive('megareembolso', 'getCost');
        if ($module !== false) {
            $fee = $module->getCost($this->context->cart);

            $payment_modules_fee['megareembolso'] = array(
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        //comaptibilidad con modulo reembolsocargo - v4.0.1 - 4webs.es
        $module = $this->core->isModuleActive('reembolsocargo', 'getFee');
        if ($module !== false) {
            $fee = $module->getFee($this->context->cart);

            $payment_modules_fee['reembolsocargo'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        //compatibilidad con modulo paypalwithfee - hasta v4.2.1 - 4webs.es
        $module = $this->core->isModuleActive('paypalwithfee', 'getFee');
        if ($module !== false) {
            $fee = $module->getFee($this->context->cart);

            if (is_array($fee)) {
                $fee = (float)$fee["fee_with_tax"];
            }

            $payment_modules_fee['paypalwithfee'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        $module = $this->core->isModuleActive('codfee');
        if ($module !== false) {
            $fee = 0;
            if (method_exists($module, 'getFee') && ($module->author == 'presta-apps' || $module->author == 'Presta-Apps Solutions')) {
                $fee = $module->getFee($this->context->cart);
            } elseif (method_exists($module, 'getFeeCost') && $module->author == 'idnovate') { // v3.2.9
                include_once _PS_MODULE_DIR_.'codfee/classes/CodfeeConfiguration.php';

                $id_lang = $this->context->cart->id_lang;
                $id_shop = $this->context->cart->id_shop;
                $customer = new Customer($this->context->cart->id_customer);
                $customer_groups = $customer->getGroupsStatic($customer->id);
                $carrier = new Carrier($this->context->cart->id_carrier);
                $carrier = $carrier->id_reference;
                $address = new Address($this->context->cart->id_address_delivery);
                $country = new Country($address->id_country);
                if ($address->id_state > 0) {
                    $zone = State::getIdZone($address->id_state);
                } else {
                    if (!Validate::isLoadedObject($country)) {
                        $id_country = FieldClass::getDefaultValue('delivery', 'id_country');
                    } else {
                        $id_country = $country->id;
                    }
                    $zone = $country->getIdZone($id_country);
                }
                $manufacturers = '';
                $suppliers = '';
                $products = $this->context->cart->getProducts();
                foreach ($products as $product) {
                    $manufacturers .= $product['id_manufacturer'].';';
                    $suppliers .= $product['id_supplier'].';';
                }
                $manufacturers = explode(';', trim($manufacturers, ';'));
                $manufacturers = array_unique($manufacturers, SORT_REGULAR);
                $suppliers = explode(';', trim($suppliers, ';'));
                $suppliers = array_unique($suppliers, SORT_REGULAR);
                $order_total = $this->context->cart->getOrderTotal(true, 3);
                $codfeeconf = new CodfeeConfiguration();
                $codfeeconf = $codfeeconf->getFeeConfiguration($id_shop, $id_lang, $customer_groups, $carrier, $country, $zone, $products, $manufacturers, $suppliers, $order_total);
                $group = new Group((int)$customer->id_default_group);
                if ($group->price_display_method == '1') {
                    $price_display_method = false;
                } else {
                    $price_display_method = true;
                }
                $fee = (float)Tools::ps_round((float)$module->getFeeCost($this->context->cart, $codfeeconf, $price_display_method), 2);
            } elseif (method_exists($module, 'getCostValidated')) {
                $fee = $module->getCostValidated($this->context->cart);
            } elseif (method_exists($module, 'getCost')) {
                $fee = $module->getCost(array('cart' => $this->context->cart));
            } elseif (method_exists($module, 'getFeeCost')) {
                $fee = $module->getFeeCost($this->context->cart);
            }

            $payment_modules_fee['codfee'] = array(
                'id' => $module->id,
                'reload' => true,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        //comaptibilidad con modulo idxcodfees - v4.1.0 - innovaDeluxe
        $module = $this->core->isModuleActive('idxcodfees', 'getFeeCost');
        if ($module !== false) {
            $fee = $module->getFeeCost($this->context->cart);

            $payment_modules_fee['idxcodfees'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee),
                'total_fee' => Tools::displayPrice($total + $fee)
            );
        }

        //compatibilidad con modulo cashondeliverywithfee - v7.2.6 - de zIO_o
        $module = $this->core->isModuleActive('cashondeliverywithfee');
        if ($module !== false) {
            $fee = $module->getFees();
            $payment_modules_fee['cashondeliverywithfee'] = array(
                'reload' => true,
                'id' => $fee['fee'],
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($fee['data']['fee']),
                'total_fee' => Tools::displayPrice($total + $fee['data']['fee'])
            );
        }

        // compatibilidad con modulo codpro -  v1.9.0
        $module = $this->core->isModuleActive('codpro');
        if ($module !== false) {
            $cod_fee = $module->getChargeValue(array('cart' => $this->context->cart));
            $payment_modules_fee['codpro'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($cod_fee),
                'total_fee' => Tools::displayPrice($total + $cod_fee)
            );
        }

        //support module: cashondeliveryfeeplus - v4.5.4 - Prestalia.
        $module = $this->core->isModuleActive('cashondeliveryfeeplus');
        if ($module !== false) {
            $cod_fee = sprintf(
                "%.2f",
                Codfp::getTotalFee(
                    $this->context->cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING),
                    $this->context->shop->id
                )
            );
            $payment_modules_fee['codpro'] = array(
                'reload' => true,
                'id' => $module->id,
                'label_fee' => $label_fee,
                'label_total' => $label_total,
                'fee' => Tools::displayPrice($cod_fee),
                'total_fee' => Tools::displayPrice($total + $cod_fee)
            );
        }

        //---------------------------------------------
        if ($set_id_customer_opc) {
            $this->context->customer = new Customer();
            $this->context->customer->logged = false;
            unset($this->context->cookie->id_customer);

            $this->context->cart->id_customer = null;
            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
        }
        //---------------------------------------------

        Media::addJsDef(array('payment_modules_fee' => Tools::jsonEncode($payment_modules_fee)));

        return $payment_modules_fee;
    }

    public function checkMinimalPurchase()
    {
        $msg = '';
        $currency = Currency::getCurrency((int) $this->context->cart->id_currency);
        $minimal_purchase = Tools::convertPrice((float) Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
        $total_products = $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);

        if ($this->core->isModuleActive('syminimalpurchase')) {
            $customer = new Customer((int)($this->context->customer->id));
            $id_group = $customer->id_default_group;
            $minimal_purchase_groups = Tools::jsonDecode(Configuration::get('syminimalpurchase'));

            if ($minimal_purchase_groups && isset($minimal_purchase_groups->{$id_group})) {
                $minimal_purchase = $minimal_purchase_groups->{$id_group};
            }
        } elseif ($minimumpurchasebycg = $this->core->isModuleActive('minimumpurchasebycg')) {
            if (!$minimumpurchasebycg->hasAllowedMinimumPurchase()) {
                $minimal_purchase = $minimumpurchasebycg->minimumpurchaseallowed;
            }
        }

        if ($total_products < $minimal_purchase) {
            $msg = sprintf(
                $this->l('A minimum purchase total of %1s (tax excl.) is required to validate your order, current purchase total is %2s (tax excl.).'),
                Tools::displayPrice($minimal_purchase, $currency),
                Tools::displayPrice($total_products, $currency)
            );
        }

        return $msg;
    }

    public function placeOrder($order_controller)
    {
        $password       = '';
        $is_set_invoice = false;
        $crypto = ServiceLocator::get('\\PrestaShop\\PrestaShop\\Core\\Crypto\\Hashing');

        //check fields are sent
        if (Tools::getIsset('fields_opc')) {
            $fields                          = Tools::jsonDecode(Tools::getValue('fields_opc'));
            $id_customer                     = Tools::getValue('id_customer', null);
            $id_address_delivery             = Tools::getValue('id_address_delivery');
            $id_address_invoice              = Tools::getValue('id_address_invoice');

            if (empty($id_address_delivery)) {
                $id_address_delivery = $this->context->cart->id_address_delivery;
            }
            if (empty($id_address_invoice)) {
                $id_address_invoice = $this->context->cart->id_address_invoice;
            }

            $customer         = new Customer((int) $id_customer);
            $address_delivery = new Address((int) $id_address_delivery);
            $address_invoice  = new Address((int) $id_address_invoice);
            $custom_fields  = array();
            $old_password   = $customer->passwd;

            $this->validateFields($fields, $customer, $address_delivery, $address_invoice, $password, $is_set_invoice, $custom_fields);

            if (property_exists($customer, 'current_passwd')) {
                if (!$crypto->checkHash($customer->current_passwd, $old_password, _COOKIE_KEY_)) {
                    $this->errors[] = $this->l('The password entered is incorrect');

                    return array(
                        'hasError'  => !empty($this->errors),
                        'errors'    => $this->errors
                    );
                }
            }

            // Check minimal amount
            $minimal_purchase = $this->checkMinimalPurchase();
            if (!empty($minimal_purchase)) {
                $this->errors[] = $minimal_purchase;
            }

            $this->supportModuleDeliveryDays();

            // If some products have disappear
            if ($this->context->cart->nbProducts() > 0) {
                foreach ($this->context->cart->getProducts() as $product) {
                    $show_message_stock = true;

                    if ($show_message_stock
                        && (!$product['active']
                            || !$product['available_for_order']
                            || (!$product['allow_oosp'] && $product['stock_quantity'] < $product['cart_quantity']))
                    ) {
                        $this->errors[] = sprintf(
                            $this->l('The product "%s" is not available or does not have stock.'),
                            $product['name']
                        );
                    }
                }
            }

            if (is_array($this->errors) && !count($this->errors)) {
                if (!$this->context->cart->isVirtualCart()) {
                    Hook::exec('actionCarrierProcess', array('cart' => $this->context->cart));
                }

                if (!$this->context->customer->isLogged() && !$this->context->customer->isGuest()) {
                    Hook::exec('actionContactFormSubmitCaptcha');

                    if (is_array($this->context->controller->errors) && count($this->context->controller->errors) > 0) {
                        $this->errors = array_merge($this->errors, $this->context->controller->errors);
                    }

                    $this->createCustomer($customer, $address_delivery, $address_invoice, $password, $is_set_invoice);

                    if (is_array($this->errors) && !count($this->errors)) {
                        //support module Abandoned Cart OPC.
                        Hook::exec('actionACOPCSaveInformation', array(
                            'id_cart' => $this->context->cart->id,
                            'id_customer' => $customer->id
                        ));

                        //if the customer it is same to opc customer, then show it error message
                        if ($customer->id == $this->config_vars['OPC_ID_CUSTOMER']) {
                            $this->errors[] = $this->l('Problem occurred when processing your order, please contact us.');
                        }

                        //$this->supportModuleCheckVat($customer);

                        // Login information have changed, so we check if the cart rules still apply
                        CartRule::autoRemoveFromCart();
                        CartRule::autoAddToCart();

                        if (Tools::getIsset('message')) {
                            $checkout_session = $order_controller->getCheckoutSession();

                            if (method_exists($checkout_session, 'setMessage')) {
                                $checkout_session->setMessage(Tools::getValue('message'));
                            }
                        }

                        if (count($custom_fields) > 0) {
                            foreach ($custom_fields as $custom_field) {
                                $id_address = null;

                                if ($custom_field->object === 'delivery') {
                                    $id_address = $this->context->cart->id_address_delivery;
                                } elseif ($custom_field->object === 'invoice') {
                                    $id_address = $this->context->cart->id_address_invoice;
                                }

                                $this->saveCustomFields($custom_field, $id_address);
                            }
                        }

                        return array(
                            'hasError'            => !empty($this->errors),
                            'errors'              => $this->errors,
                            'isSaved'             => true,
                            'isGuest'             => $customer->is_guest,
                            'id_customer'         => (int) $customer->id,
                            'secure_key'          => $this->context->cart->secure_key,
                            'id_address_delivery' => $this->context->cart->id_address_delivery,
                            'id_address_invoice'  => $this->context->cart->id_address_invoice,
                            'token'               => Tools::getToken(false),
                        );
                    }
                } else {
                    if ($this->config_vars['OPC_CHOICE_GROUP_CUSTOMER']
                        && Tools::getIsset('group_customer')
                        && $group_customer = Tools::getValue('group_customer', '')
                    ) {
                        $new_groups_customer = array((int)$group_customer);
                        $groups_availables = explode(
                            ',',
                            $this->config_vars['OPC_CHOICE_GROUP_CUSTOMER_ALLOW']
                        );

                        $groups_customer = $customer->getGroups();
                        foreach ($groups_customer as $id_group_customer) {
                            if (!in_array($id_group_customer, $groups_availables)) {
                                $new_groups_customer[] = $id_group_customer;
                            }
                        }

                        $customer->id_default_group = (int)$group_customer;
                        $customer->cleanGroups();
                        $customer->addGroups($new_groups_customer);
                    }

                    if ($customer->update()) {
                        Hook::exec('actionCustomerAccountUpdate', [
                            'customer' => $customer,
                            'newCustomer' => $customer /*compatibilidad con modulo: mailchimppro - v2.0.3 - Mailchimp*/
                        ]);

                        $this->context->cookie->customer_lastname  = $customer->lastname;
                        $this->context->cookie->customer_firstname = $customer->firstname;
                        $this->context->cookie->passwd             = $customer->passwd;

                        //actualizamos las opciones newsletter y optin directamente
                        //en la base de datos, ya que prestashop no lo hace por algun bug.
                        if ((int) $customer->newsletter == 1) {
                            Db::getInstance(_PS_USE_SQL_SLAVE_)->update(
                                'customer',
                                array('newsletter' => 1),
                                'id_customer = '.(int)$customer->id
                            );
                        }

                        if ((int) $customer->optin == 1) {
                            Db::getInstance(_PS_USE_SQL_SLAVE_)->update(
                                'customer',
                                array('optin' => 1),
                                'id_customer = '.(int)$customer->id
                            );
                        }
                    } else {
                        $this->errors[] = $this->l('An error occurred while creating your account.');
                    }

                    if (is_array($this->errors) && !count($this->errors)) {
                        if (Tools::getIsset('message')) {
                            $checkout_session = $order_controller->getCheckoutSession();

                            if (method_exists($checkout_session, 'setMessage')) {
                                $checkout_session->setMessage(Tools::getValue('message'));
                            }
                        }
                    }

                    $rc_page = Tools::getValue('rc_page', null);
                    if ($rc_page !== 'identity') {
                        if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_DA']) {
                            $address_delivery->firstname = $customer->firstname;
                            $address_delivery->lastname  = $customer->lastname;
                        }
                        $address_delivery->update();

                        if ($address_delivery->id !== $address_invoice->id) {
                            if ($this->config_vars['OPC_USE_SAME_NAME_CONTACT_BA']) {
                                $address_invoice->firstname = $customer->firstname;
                                $address_invoice->lastname  = $customer->lastname;
                            }
                            $address_invoice->update();
                        }
                    }

                    if (count($custom_fields) > 0) {
                        foreach ($custom_fields as $custom_field) {
                            $id_address = null;

                            if ($custom_field->object === 'delivery') {
                                $id_address = $this->context->cart->id_address_delivery;
                            } elseif ($custom_field->object === 'invoice') {
                                $id_address = $this->context->cart->id_address_invoice;
                            }

                            $this->saveCustomFields($custom_field, $id_address);
                        }
                    }
                }
            }

            return array(
                'hasError'            => !empty($this->errors),
                'hasWarning'          => !empty($this->warnings),
                'errors'              => $this->errors,
                'warnings'            => $this->warnings,
                'secure_key'          => $this->context->cart->secure_key,
                'id_customer'         => $customer->id,
                'id_address_delivery' => $address_delivery->id,
                'id_address_invoice'  => $address_invoice->id
            );
        }
    }

    public function deleteEmptyAddressesOPC()
    {
        $query = new DbQuery();
        $query->select('id_customer');
        $query->from('customer');
        $query->where('email = "'.pSQL('noreply@'.$this->context->shop->domain).'"');
        $opc_result = Db::getInstance()->executeS($query);

        if (count($opc_result) > 0) {
            $opc_customers = array();

            foreach ($opc_result as $id_customer_opc) {
                $opc_customers[] = $id_customer_opc['id_customer'];
            }
            $query = 'DELETE FROM '._DB_PREFIX_.'address WHERE id_customer IN('.implode(',', $opc_customers).')';
        } else {
            $query = 'DELETE FROM '._DB_PREFIX_.'address WHERE id_customer = '.(int)$this->config_vars['OPC_ID_CUSTOMER'];
        }

        Db::getInstance()->execute($query);

        $query = 'DELETE FROM `'._DB_PREFIX_.'address` WHERE id_customer NOT IN (SELECT id_customer FROM `'._DB_PREFIX_.'customer`) AND id_address NOT IN (SELECT id_address_delivery AS id_address FROM `'._DB_PREFIX_.'orders` UNION SELECT id_address_invoice AS id_address FROM `'._DB_PREFIX_.'orders` )';
        Db::getInstance()->execute($query);

        $query = new DbQuery();
        $query->select('*');
        $query->from('cart');
        $query->where('id_cart NOT IN (SELECT id_cart FROM '._DB_PREFIX_.'orders)');

        $carts = Db::getInstance()->executeS($query);
        if (count($carts) > 0) {
            foreach ($carts as $cart) {
                $result = true;
                $result1 = true;
                if (!empty($cart['id_address_delivery'])) {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'address WHERE id_address = '.(int)$cart['id_address_delivery'];
                    $result = Db::getInstance()->executeS($query);
                }
                if (!empty($cart['id_address_invoice'])) {
                    $query1 = 'SELECT * FROM '._DB_PREFIX_.'address WHERE id_address = '.(int)$cart['id_address_invoice'];
                    $result1 = Db::getInstance()->executeS($query1);
                }
                $customer = new Customer((int)$cart['id_customer']);
                if (Validate::isLoadedObject($customer) && $customer->email == 'noreply@'.$this->context->shop->domain) {
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'cart WHERE id_cart = '.(int) $cart['id_cart']);
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'cart_product WHERE id_cart = '.(int) $cart['id_cart']);
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'cart_cart_rule WHERE id_cart = '.(int) $cart['id_cart']);
                } else if (!$result || !$result1) {
                    Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'cart SET id_address_delivery = 0, id_address_invoice = 0, delivery_option = "" WHERE id_cart = '.(int) $cart['id_cart']);
                }
            }
        }

        $query = new DbQuery();
        $query->select('*');
        $query->from('opc_customer_address');

        $customer_address = Db::getInstance()->executeS($query);
        if (count($customer_address) > 0) {
            foreach ($customer_address as $item) {
                $query = 'SELECT * FROM '._DB_PREFIX_.'address WHERE id_address = '.(int)$item['id_address'];
                $result = Db::getInstance()->executeS($query);
                if (!$result) {
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'opc_customer_address WHERE id_address = '.(int)$item['id_address']);
                }

                $query = 'SELECT * FROM '._DB_PREFIX_.'customer WHERE id_customer = '.(int)$item['id_customer'];
                $result = Db::getInstance()->executeS($query);
                if (!$result) {
                    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'opc_customer_address WHERE id_customer = '.(int)$item['id_customer']);
                }
            }
        }

        return array(
            'message_code' => $this->core->CODE_SUCCESS,
            'message' => $this->l('Created temporary addresses were deleted successfully.')
        );
    }

    public function getTemplateVarsOPC()
    {
        $language = $this->context->language;

        $countries = Country::getCountries($this->context->language->id, true);
        $countries_js = array();
        $countriesNeedIDNumber = array();
        $countriesNeedZipCode = array();
        $countriesIsoCode = array();

        foreach ($countries as $country) {
            $id_country = (int)$country['id_country'];
            $countriesIsoCode[$id_country] = $country['iso_code'];
            $countriesNeedIDNumber[$id_country] = (int)$country['need_identification_number'];

            if (!empty($country['zip_code_format'])) {
                $countriesNeedZipCode[$id_country] = $country['zip_code_format'];
            }

            if ($country['contains_states'] == 1 && isset($country['states']) && count($country['states']) > 0) {
                foreach ($country['states'] as $state) {
                    if ($state['active'] == 1) {
                        $countries_js[$id_country][] = array(
                            'id' => (int)$state['id_state'],
                            'name' => $state['name'],
                            'iso_code' => $state['iso_code']
                        );
                    }
                }
            }
        }

        $is_set_invoice = false;
        if (isset($this->context->cookie->is_set_invoice)) {
            $is_set_invoice = $this->context->cookie->is_set_invoice;
        }

        $date_format_language = $this->dateFormartPHPtoJqueryUI($language->date_format_lite);

        $opc_social_networks = $this->config_vars['OPC_SOCIAL_NETWORKS'];
        $opc_social_networks = Tools::jsonDecode($opc_social_networks);
        if ($opc_social_networks) {
            $is_have_some_network = false;
            foreach ($opc_social_networks as $network) {
                if (!empty($network->client_id) && !empty($network->client_secret)) {
                    $is_have_some_network = true;
                }
            }

            if (!$is_have_some_network) {
                $opc_social_networks = false;
            }
        }

        $id_country_delivery_default = FieldClass::getDefaultValue('delivery', 'id_country');
        $iso_code_country_delivery_default = Country::getIsoById($id_country_delivery_default);

        $id_country_invoice_default = FieldClass::getDefaultValue('invoice', 'id_country');
        $iso_code_country_invoice_default = Country::getIsoById($id_country_invoice_default);

        //grid steps
        $position_steps = array(
            0 => array(
                'classes' => ($this->only_register ? '' : 'col-md-4 col-sm-12').' col-xs-12 col-12',
                'rows' => array(
                    0 => array(
                        'name_step' => 'customer',
                        'classes' => 'col-xs-12 col-12'
                    )
                )
            ),
            1 => array(
                'classes' => 'col-md-8 col-sm-12 col-xs-12 col-12',
                'rows' => array(
                    0 => array(
                        'name_step' => 'carrier',
                        'classes' => 'col-xs-12 col-12 col-md-12 col-lg-6 col-xl-6'
                    ),
                    1 => array(
                        'name_step' => 'payment',
                        'classes' => 'col-xs-12 col-12 col-md-12 '.($this->context->cart->isVirtualCart() ? 'col-lg-12 col-xl-12' : 'col-lg-6 col-xl-6')
                    ),
                    2 => array(
                        'name_step' => 'review',
                        'classes' => 'col-xs-12 col-12'
                    )
                )
            )
        );

        $messageValidate = array(
            'errorGlobal'           => $this->l('This is not a valid.'),
            'errorIsName'           => $this->l('This is not a valid name.'),
            'errorIsEmail'          => $this->l('This is not a valid email address.'),
            'errorIsPostCode'       => $this->l('This is not a valid post code.'),
            'errorIsAddress'        => $this->l('This is not a valid address.'),
            'errorIsCityName'       => $this->l('This is not a valid city.'),
            'isMessage'             => $this->l('This is not a valid message.'),
            'errorIsDniLite'        => $this->l('This is not a valid document identifier.'),
            'errorIsPhoneNumber'    => $this->l('This is not a valid phone.'),
            'errorIsPasswd'         => $this->l('This is not a valid password. Minimum 5 characters.'),
            'errorisBirthDate'      => $this->l('This is not a valid birthdate.'),
            'errorisDate'           => $this->l('This is not a valid date.'),
            'badUrl'                => $this->l('This is not a valid url.').'ex: http://www.domain.com',
            'badInt'                => $this->l('This is not a valid.'),
            'notConfirmed'          => $this->l('The values do not match.'),
            'lengthTooLongStart'    => $this->l('It is only possible enter'),
            'lengthTooShortStart'   => $this->l('The input value is shorter than '),
            'lengthBadEnd'          => $this->l('characters.'),
            'requiredField'         => $this->l('This is a required field.')
        );

        $additionalCustomerFormFields = Hook::exec('additionalCustomerFormFields', array(), null, true);
        $form_fields = array();

        if (is_array($additionalCustomerFormFields)) {
            $exclude_modules = array('psgdpr', 'ps_dataprivacy', 'ps_emailsubscription');
            foreach ($additionalCustomerFormFields as $moduleName => $additionnalFormFields) {
                if (!is_array($additionnalFormFields) || in_array($moduleName, $exclude_modules)) {
                    continue;
                }

                foreach ($additionnalFormFields as $formField) {
                    $formField->moduleName = $moduleName;
                    $form_fields[$moduleName.'_'.$formField->getName()] = $formField->toArray();
                }
            }
        }

        $templateVars = array(
            'PresTeamShop' => array(
                'pts_static_token'          => Tools::encrypt('onepagecheckoutps/index'),
                'module_dir'                => $this->_path,
                'module_img'                => $this->_path.'views/img/',
                'class_name'                => 'APP'.$this->prefix_module,
                'iso_lang'                  => Language::getIsoById($this->context->language->id),
                'success_code'              => $this->core->CODE_SUCCESS,
                'error_code'                => $this->core->CODE_ERROR,
                'id_language_default'       => Configuration::get('PS_LANG_DEFAULT')
            ),
            'messageValidate'               => $messageValidate,
            'pts_static_token'              => Tools::encrypt('onepagecheckoutps/index'),
            'static_token'                  => Tools::getToken(false),
            'countriesJS'                   => $countries_js,
            'countriesNeedIDNumber'         => $countriesNeedIDNumber,
            'countriesNeedZipCode'          => $countriesNeedZipCode,
            'countriesIsoCode'              => $countriesIsoCode,
            'position_steps'                => $position_steps,
            'payment_modules_fee'           => $this->addModulesExtraFee(),
            'is_virtual_cart'               => $this->only_register ? false : (bool)$this->context->cart->isVirtualCart(),
            'hook_create_account_top'       => Hook::exec('displayCustomerAccountFormTop'),
            'hook_create_account_form'      => Hook::exec('displayCustomerAccountForm'),
            'additional_customer_form_fields' => $form_fields,
            'opc_social_networks'           => $opc_social_networks,
            'is_set_invoice'                => $is_set_invoice,
            'register_customer'             => $this->only_register,
            'id_address_delivery'         => $this->context->cart->id_address_delivery,
            'id_address_invoice'          => $this->context->cart->id_address_invoice,
            'customer_info' => $this->context->customer,
            'OnePageCheckoutPS' => array(
                'date_format_language'          => $date_format_language,
                'id_country_delivery_default'   => $id_country_delivery_default,
                'id_country_invoice_default'    => $id_country_invoice_default,
                'iso_code_country_delivery_default' => $iso_code_country_delivery_default,
                'iso_code_country_invoice_default'  => $iso_code_country_invoice_default,
                'IS_GUEST' => (bool)$this->context->customer->isGuest(),
                'IS_LOGGED' => (bool)$this->context->customer->isLogged(),
                'iso_code_country_invoice_default'  => $iso_code_country_invoice_default,
                'id_shop' => $this->context->shop->id,
                'LANG_ISO_ALLOW' => array('es', 'en', 'ca', 'br', 'eu', 'pt', 'eu', 'mx'),
                'CONFIGS' => $this->config_vars,
                'ONEPAGECHECKOUTPS_DIR' => $this->onepagecheckoutps_dir,
                'ONEPAGECHECKOUTPS_IMG' => $this->onepagecheckoutps_dir.'views/img/',
                'PRESTASHOP' => array(
                    'CONFIGS' => array (
                        'PS_TAX_ADDRESS_TYPE' => Configuration::get('PS_TAX_ADDRESS_TYPE'),
                        'PS_GUEST_CHECKOUT_ENABLED' => (int)Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
                    ),
                ),
                'Msg' => array(
                    'there_are' => $this->l('There are'),
                    'there_is' => $this->l('There is'),
                    'error' => $this->l('Error'),
                    'errors' => $this->l('Errors'),
                    'field_required' => $this->l('Required'),
                    'dialog_title' => $this->l('Confirm Order'),
                    'no_payment_modules' => $this->l('There are no payment methods available.'),
                    'validating' => $this->l('Validating, please wait'),
                    'error_zipcode' => $this->l('The Zip / Postal code is invalid'),
                    'error_registered_email' => $this->l('An account is already registered with this e-mail'),
                    'error_registered_email_guest' => $this->l('This email is already registered, you can login or fill form again.'),
                    'delivery_billing_not_equal' => $this->l('Delivery address alias cannot be the same as billing address alias'),
                    'errors_trying_process_order' => $this->l('The following error occurred while trying to process the order'),
                    'agree_terms_and_conditions' => $this->l('You must agree to the terms of service before continuing.'),
                    'agree_privacy_policy' => $this->l('You must agree to the privacy policy before continuing.'),
                    'fields_required_to_process_order' => $this->l('You must complete the required information to process your order.'),
                    'check_fields_highlighted' => $this->l('Check the fields that are highlighted and marked with an asterisk.'),
                    'error_number_format' => $this->l('The format of the number entered is not valid.'),
                    'oops_failed' => $this->l('Oops! Failed'),
                    'continue_with_step_3' => $this->l('Continue with step 3.'),
                    'email_required' => $this->l('Email address is required.'),
                    'email_invalid' => $this->l('Invalid e-mail address.'),
                    'password_required' => $this->l('Password is required.'),
                    'password_too_long' => $this->l('Password is too long.'),
                    'password_invalid' => $this->l('Invalid password.'),
                    'addresses_same' => $this->l('You must select a different address for shipping and billing.'),
                    'create_new_address' => $this->l('Are you sure you wish to add a new delivery address? You can use the current address and modify the information.'),
                    'cart_empty' => $this->l('Your shopping cart is empty. You need to refresh the page to continue.'),
                    'dni_spain_invalid' => $this->l('DNI/CIF/NIF is invalid.'),
                    'payment_method_required' => $this->l('Please select a payment method to proceed.'),
                    'shipping_method_required' => $this->l('Please select a shipping method to proceed.'),
                    'select_pickup_point' => $this->l('To select a pick up point is necessary to complete your information and delivery address in the first step.'),
                    'need_select_pickup_point' => $this->l('You need to select on shipping a pickup point to continue with the purchase.'),
                    'select_date_shipping' => $this->l('Please select a date for shipping.'),
                    'confirm_payment_method' => $this->l('Confirmation payment'),
                    'to_determinate' => $this->l('To determinate'),
                    'login_customer' => $this->l('Login'),
                    'processing_purchase' => $this->l('Processing purchase'),
                    'validate_address' => $this->l('Validate your address'),
                    'message_validate_address' => $this->l('Your order will ship to: %address%. Is the address OK?'),
                    'close' => $this->l('Close'),
                    'no_remove_address_delivery' => $this->l('It is not possible to delete this address because it is being used as a invoice address.'),
                    'no_remove_address_invoice' => $this->l('It is not possible to delete this address because it is being used as a delivery address.'),
                    'finalize_address_update' => $this->l('You need to finish adding or editing your address to complete the purchase.'),
                    'need_add_delivery_address' => $this->l('It is necessary to add a delivery address in order to finalize the purchase.'),
                    'select_delivery_address' => $this->l('Please select a delivery address.'),
                    'select_invoice_address' => $this->l('Please select a invoice address.'),
                    'confirm_remove_address' => $this->l('Are you sure you want to delete this address?'),
                    'change_carrier_embed' => $this->l('Change shipping carrier'),
                    'choose_carrier_embed' => $this->l('Choose shipping carrier')
                )
            )
        );

        /* Support module orderfees_payment - V1.8.14 de motionSeed */
        $templateVars['orderfees_payment_installed'] = ($this->core->isModuleActive('orderfees_payment')) ? true : false;
        /* Support module orderfees - V1.8.51 de motionSeed */
        $templateVars['orderfees_installed'] = ($this->core->isModuleActive('orderfees')) ? true : false;
        /* Support module zipmoneypayment - V1.0.1 de Zip */
        if ($zipmoneypayment = $this->core->isModuleActive('zipmoneypayment')) {
            $templateVars['zm_in_context'] = $zipmoneypayment->use_incontext_checkout() ? 'true' : 'false';
            $templateVars['checkoutUri'] = $this->context->link->getModuleLink($zipmoneypayment->name, 'payment', array(), true);
            $templateVars['redirectUri'] = $this->context->link->getModuleLink($zipmoneypayment->name, 'validation', array(), true);
        }

        //support module: psgdpr - v1.0.0 - PrestaShop
        if ($this->core->isModuleActive('psgdpr')) {
            $active_gdpr = GDPRConsent::getConsentActive($this->id);
            if ($active_gdpr) {
                $message_psgdpr = GDPRConsent::getConsentMessage($this->id, $this->context->language->id);
                $message_psgdpr = str_replace('<p>', '', $message_psgdpr);
                $message_psgdpr = str_replace('</p>', '', $message_psgdpr);

                $templateVars['message_psgdpr'] = $message_psgdpr;
            }
        }

        return $templateVars;
    }

    public function checkVATNumber()
    {
        $errors = array();
        $vat_number = Tools::getValue('vat_number', '');
        $id_address = $this->context->cart->id_address_delivery;

        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
            $id_address = $this->context->cart->id_address_invoice;
        }

        if (!empty($vat_number)) {
            $found_module = false;
            $address = new Address($id_address);
            $id_country = Tools::getValue('id_country', $address->id_country);

            $vatnumbercleaner = $this->core->isModuleActive('vatnumbercleaner');//v1.3.8 - MassonVincent
            if ($vatnumbercleaner) {
                $found_module = true;
                $verifications = $vatnumbercleaner->verificationsIdAddress(
                    $id_address,
                    $vat_number,
                    $address->id_country
                );
                if ($verifications['id_msg'] > 0) {
                    $errors = array($vatnumbercleaner->msgerror($verifications['id_msg']));
                }
            }

            $validatevatnumber = $this->core->isModuleActive('validatevatnumber');//v2.1.7 - ActiveDesign
            if ($validatevatnumber) {
                $found_module = true;
                $manual_mode = (int)Configuration::get('VALIDATEVATNUMBER_MANUAL_MODE');
                if ($manual_mode != 1) {
                    $result = $validatevatnumber->verifyCountryAndVat($id_country, $vat_number);
                    if ($result === true) {
                        $result = $validatevatnumber->verifyVatNumberOnline($vat_number);
                        if ($result !== true) {
                            $errors = array($this->l('Your VAT number is invalid.'));
                        }
                    } else {
                        $errors = array($result);
                    }
                }
            }

            if (!$found_module && Configuration::get('VATNUMBER_MANAGEMENT')) {
                include_once _PS_MODULE_DIR_.'vatnumber/vatnumber.php';
                if (class_exists('VatNumber', false) && Configuration::get('VATNUMBER_CHECKING')) {
                    $errors = VatNumber::WebServiceCheck($vat_number);
                }
            }
        }

        if (empty($errors)) {
            if (!empty($id_address)) {
                $address = new Address($id_address);
                $address->vat_number = $vat_number;
                $address->save();
            }
        }

        return $errors;
    }

    public function getInstanceOauthClient($social_network)
    {
        if (!class_exists('http_class')) {
            include _PS_MODULE_DIR_.'onepagecheckoutps/lib/social_network/http.php';
        }
        if (!class_exists('oauth_client_class_pts')) {
            include _PS_MODULE_DIR_.'onepagecheckoutps/lib/social_network/oauth_client.php';
        }

        $client = new oauth_client_class_pts;
        $opc_social_networks = Tools::jsonDecode($this->config_vars['OPC_SOCIAL_NETWORKS']);

        if (!empty($social_network) && !empty($opc_social_networks)) {
            $client->redirect_uri = $this->context->link->getModuleLink(
                'onepagecheckoutps',
                'login',
                array('sv' => $social_network)
            );

            $social_network = Tools::strtolower($social_network);

            $client->server             = $opc_social_networks->{$social_network}->network;
            $client->client_id          = $opc_social_networks->{$social_network}->client_id;
            $client->client_secret      = $opc_social_networks->{$social_network}->client_secret;
            $client->scope              = $opc_social_networks->{$social_network}->scope;
            $client->configuration_file = dirname(__FILE__).'/lib/social_network/oauth_configuration.json';

            switch ($client->server) {
                case 'Facebook':
                    $client->reauthenticate = false;
                    break;
                case 'Google':
                    $client->offline = true;
                    break;
                case 'Biocryptology':
                    $client->url_encode = false;
                    break;
            }
        }

        return $client;
    }

    public function callGeonamesJSON()
    {
        $method = Tools::getValue('method');
        $params = http_build_query(Tools::getValue('params'));

        $ch = curl_init('http://api.geonames.org/'.$method.'?'.$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /*
     * Matches each symbol of PHP date format standard
     * with jQuery equivalent codeword
     * @author Tristan Jahier
     */
    public function dateFormartPHPtoJqueryUI($php_format)
    {
        $symbols_matching = array(
            // Day
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'yy',
            'y' => 'y',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => '',
        );
        $jqueryui_format  = '';
        $escaping         = false;
        $size_format      = Tools::strlen($php_format);
        for ($i = 0; $i < $size_format; $i++) {
            $char = $php_format[$i];
            if ($char === '\\') { // PHP date format escaping character
                $i++;
                if ($escaping) {
                    $jqueryui_format .= $php_format[$i];
                } else {
                    $jqueryui_format .= '\''.$php_format[$i];
                }
                $escaping = true;
            } else {
                if ($escaping) {
                    $jqueryui_format .= "'";
                    $escaping = false;
                }
                if (isset($symbols_matching[$char])) {
                    $jqueryui_format .= $symbols_matching[$char];
                } else {
                    $jqueryui_format .= $char;
                }
            }
        }

        return $jqueryui_format;
    }

    public function includeTpl($tpl, $params)
    {
        $this->smarty->assign($params);

        if (file_exists(_PS_THEME_DIR_.'modules/'.$this->name.'/views/templates/front/'.$tpl)) {
            echo $this->smarty->fetch(_PS_THEME_DIR_.'modules/'.$this->name.'/views/templates/front/'.$tpl);
        } else {
            echo $this->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/front/'.$tpl);
        }
    }

    public function toolsLinkRewrite()
    {
        $value = Tools::getValue('value');

        return Tools::link_rewrite(trim($value));
    }
}
