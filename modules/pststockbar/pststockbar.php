<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2018 Presta.Site
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
    require_once(_PS_MODULE_DIR_ . 'pststockbar/classes/PstStockBarModule16.php');
} else {
    require_once(_PS_MODULE_DIR_ . 'pststockbar/classes/PstStockBarModule17.php');
}
require_once(_PS_MODULE_DIR_ . 'pststockbar/classes/PstStockBarLevel.php');

class PstStockBar extends PstStockBarModule
{
    protected $html;
    protected $errors = array();
    public $settings_prefix = 'PSB_';

    public $position;
    public $position_list;
    public $default_max_qty;
    public $color_stock_high;
    public $color_stock_medium;
    public $color_stock_low;
    public $max_width;
    public $hide_empty_stock;
    public $hide_full_stock;
    public $show_proofs;
    public $show_proofs_list;
    public $stats_period;
    public $highlight_block;
    public $custom_css;
    public $theme;
    public $sections;
    public $show_qty;
    public $hide_default_qty;
    public $categories;
    public $qty_with_cart;
    public $custom_stock_levels;
    public $stock_levels;

    public function __construct()
    {
        $this->name = 'pststockbar';
        $this->tab = 'front_office_features';
        $this->version = '1.2.3';
        $this->ps_versions_compliancy = array('min' => '1.5.0.0', 'max' => '1.7.99.99');
        $this->author = 'PrestaSite';
        $this->bootstrap = true;
        $this->module_key = '84d8170a64df6e43d02620a3ab80eb40';

        parent::__construct();
        $this->loadSettings();

        $this->displayName = $this->l('Product Availability Indicator');
        $this->description = $this->l('A module for displaying stock indicators for products.');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('header')
            || !$this->registerHook('pstStockBar')
            || !$this->registerHook('displayProductDeliveryTime')
            || !$this->registerHook('displayProductListReviews')
            || !$this->registerHook('displayLeftColumnProduct')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('displayProductAdditionalInfo')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('actionUpdateQuantity')
        ) {
            return false;
        }

        $install_queries = array(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pststockbar_product` (
                `id_pststockbar_product` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_product` INT(11) UNSIGNED NOT NULL,
                `id_shop` INT(11) UNSIGNED NOT NULL,
                `max_qty` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`id_pststockbar_product`),
                UNIQUE (`id_product`, `id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pststockbar_level` (
                `id_pststockbar_level` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_shop` INT(11) UNSIGNED NOT NULL,
                `max_qty` INT(11) UNSIGNED NOT NULL,
                `color` VARCHAR(65),
                `id_product` INT(11) NOT NULL DEFAULT 0,
                INDEX (`id_shop`, `id_product`),
                PRIMARY KEY (`id_pststockbar_level`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pststockbar_level_lang` (
                `id_pststockbar_level` INT(11) UNSIGNED NOT NULL,
                `id_lang` INT(11) UNSIGNED NOT NULL,
                `text` VARCHAR(255),
                UNIQUE (`id_pststockbar_level`, `id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',
        );
        foreach ($install_queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        //default values:
        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }
            if (isset($item['default']) && (Configuration::get($this->settings_prefix . $item['name']) === false)) {
                if (isset($item['lang']) && $item['lang']) {
                    $lang_value = array();
                    foreach (Language::getLanguages() as $lang) {
                        $lang_value[$lang['id_lang']] = $item['default'];
                    }
                    if (sizeof($lang_value)) {
                        Configuration::updateValue($this->settings_prefix . $item['name'], $lang_value, true);
                    }
                } else {
                    Configuration::updateValue($this->settings_prefix . $item['name'], $item['default']);
                }
            }
        }

        $this->loadSettings();
        $this->regenerateCSS();
        $this->initLevels();

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $tables = array(
            'pststockbar_product',
            'pststockbar_level',
            'pststockbar_level_lang',
        );

        // Drop the module tables
        foreach ($tables as $table) {
            Db::getInstance()->execute(
                'DROP TABLE IF EXISTS `'._DB_PREFIX_.pSQL($table).'`'
            );
        }

        // Delete all the module settings
        $ids_conf = Db::getInstance()->executeS(
            'SELECT * FROM `'._DB_PREFIX_.'configuration`  WHERE `name` LIKE "'.pSQL($this->settings_prefix).'%"'
        );
        foreach ($ids_conf as $id_conf) {
            $id_conf = $id_conf['id_configuration'];
            Db::getInstance()->execute(
                'DELETE FROM `'._DB_PREFIX_.'configuration_lang` WHERE `id_configuration` = '.(int)$id_conf
            );
            Db::getInstance()->execute(
                'DELETE FROM `'._DB_PREFIX_.'configuration` WHERE `id_configuration` = '.(int)$id_conf
            );
        }

        return true;
    }

    public function getContent()
    {
        // Check if this is an ajax call / PS1.5
        if ($this->getPSVersion() < 1.6 && Tools::getIsset('ajax')
            && Tools::getValue('ajax') && Tools::getValue('action')) {
            if (is_callable(array($this, 'ajaxProcess'.Tools::getValue('action')))) {
                call_user_func(array($this, 'ajaxProcess' . Tools::getValue('action')));
            }
            die();
        }

        $this->html = '';
        $this->html .= $this->postProcess();
        $this->html .= $this->renderForm();
        $this->html .= $this->displayCustomHookInfo();

        return $this->html;
    }

    protected function postProcess()
    {
        $html = '';
        $settings_updated = false;
        $this->errors = array();

        // Check if this is an ajax call / PS1.5
        if ($this->getPSVersion() < 1.6 && Tools::getIsset('ajax')
            && Tools::getValue('ajax') && Tools::getValue('action')) {
            if (is_callable(array($this, 'ajaxProcess'.Tools::getValue('action')))) {
                call_user_func(array($this, 'ajaxProcess' . Tools::getValue('action')));
            }
            die();
        }

        if (Tools::isSubmit('submitModule')) {
            //saving settings:
            $settings = $this->getSettings();
            foreach ($settings as $item) {
                if ($item['type'] == 'html' || $item['type'] == 'checkbox' || $item['type'] == 'stock_levels'
                    || (isset($item['lang']) && $item['lang'] == true)) {
                    continue;
                }
                if (Tools::isSubmit($item['name']) || $item['type'] == 'categories') {
                    $value = Tools::getValue($item['name']);
                    $validated = true;
                    $val_method = (isset($item['validate']) ? $item['validate'] : '');

                    if ($item['type'] == 'categories' && $value && is_array($value)) {
                        $value = implode(',', $value);
                    }

                    if (Tools::strlen($value)) {
                        // Validation:
                        if (Tools::strlen($val_method) && is_callable(array('Validate', $val_method))) {
                            $validated =
                                call_user_func(array('Validate', $val_method), $value);
                        }
                    }
                    if ($validated) {
                        Configuration::updateValue(
                            $this->settings_prefix . $item['name'],
                            $value,
                            true
                        );
                        $settings_updated = true;
                    } else {
                        $label = trim($item['label'], ':');
                        $this->errors[] = sprintf($this->l('The "%s" field is invalid'), $label);
                    }
                }
            }

            // Checkboxes
            foreach ($settings as $item) {
                if ($item['type'] == 'checkbox') {
                    $value = '';
                    $validated = true;
                    $val_method = (isset($item['validate']) ? $item['validate'] : '');

                    foreach ($item['values']['query'] as $val_array) {
                        if (Tools::isSubmit($item['name'].'_'.$val_array['id_option'])) {
                            $value .= $val_array['id_option'];
                        }
                    }

                    if (Tools::strlen($value)) {
                        // Validation:
                        if (Tools::strlen($val_method) && is_callable(array('Validate', $val_method))) {
                            $validated =
                                call_user_func(array('Validate', $val_method), $value);
                        }
                    }
                    if ($validated) {
                        Configuration::updateValue(
                            $this->settings_prefix . $item['name'],
                            $value,
                            true
                        );
                        $settings_updated = true;
                    } else {
                        $label = trim($item['label'], ':');
                        $this->errors[] = sprintf($this->l('The "%s" field is invalid'), $label);
                    }
                }
            }

            // Custom stock levels
            foreach ($settings as $item) {
                if ($item['type'] == 'stock_levels') {
                    $lvls = Tools::getValue('lvl');
                    foreach ($lvls as $key => $lvl_data) {
                        $id = null;
                        $id_shop = null;
                        // for existing levels get ID, for newly created levels get id_shop and set it later
                        if (isset($lvl_data['id'])) {
                            $id = $lvl_data['id'];
                            unset($lvl_data['id']);
                        } else {
                            $id_shop = $this->context->shop->id;
                        }

                        // skip tpl row and empty rows
                        if (!$id && $lvl_data['max_qty'] === '' && $lvl_data['color'] === ''
                            && !array_filter($lvl_data['text'])) {
                            unset($lvls[$key]);
                            continue;
                        }

                        $level = new PstStockBarLevel($id);

                        if (isset($lvl_data['delete']) && $lvl_data['delete']) {
                            $level->delete();
                            continue;
                        }

                        if ($id_shop) {
                            $level->id_shop = $id_shop;
                        }
                        // assign props
                        foreach ($lvl_data as $prop => $data) {
                            if (property_exists($level, $prop)) {
                                $level->$prop = $data;
                            }
                        }

                        // validate
                        $field_errors = $level->validateAllFields();
                        // if no errors
                        if (!(is_array($field_errors) && count($field_errors))) {
                            $level->save();
                        } else {
                            $this->errors += $field_errors;
                        }
                    }
                }
            }

            //update lang fields:
            $languages = Language::getLanguages();
            foreach ($settings as $item) {
                if (!(isset($item['lang']) && $item['lang'])) {
                    continue;
                }
                $val_method = (isset($item['validate']) ? $item['validate'] : '');
                $lang_value = array();
                foreach ($languages as $lang) {
                    if (Tools::isSubmit($item['name'] . '_' . $lang['id_lang'])) {
                        $validated = true;
                        if (Tools::strlen(Tools::getValue($item['name'] . '_' . $lang['id_lang']))) {
                            // Validation:
                            if (Tools::strlen($val_method) && is_callable(array('Validate', $val_method))) {
                                $validated =
                                    call_user_func(
                                        array('Validate', $val_method),
                                        Tools::getValue($item['name'] . '_' . $lang['id_lang'])
                                    );
                            }
                        }
                        if ($validated) {
                            $lang_value[$lang['id_lang']] = Tools::getValue($item['name'] . '_' . $lang['id_lang']);
                            $settings_updated = true;
                        } else {
                            $label = trim($item['label'], ':');
                            $this->errors[] = sprintf($this->l('The "%s" field is invalid'), $label);
                        }
                    }
                }
                if (sizeof($lang_value)) {
                    Configuration::updateValue($this->settings_prefix . $item['name'], $lang_value, true);
                }
            }
        }

        $this->loadSettings();

        // regen custom css
        $this->regenerateCSS();

        if ($settings_updated && !sizeof($this->errors)) {
            $this->setLastSaveTime();
            $token = Tools::getAdminTokenLite('AdminModules');
            $redirect_url = 'index.php?tab=AdminModules&configure=' . $this->name . '&token=' . $token . '&conf=6';
            Tools::redirectAdmin($redirect_url);
        } elseif (sizeof($this->errors)) {
            foreach ($this->errors as $err) {
                $html .= $this->displayError($err);
            }
        }

        return $html;
    }

    protected function renderForm()
    {
        $field_forms = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Settings'),
                        'icon' => 'icon-cogs'
                    ),
                    'input' => $this->getSettings(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
        );

        foreach ($field_forms as &$field_form) {
            if ($this->getPSVersion() == 1.5) {
                $field_form['form']['submit']['class'] = 'button';
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') :
                0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
            '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'psv' => $this->getPSVersion(),
            'psvd' => $this->getPSVersion(true),
            'pststockbar' => $this,
        );
        $helper->module = $this;

        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }
            if (isset($item['lang']) && $item['lang']) {
                foreach (Language::getLanguages() as $language) {
                    $helper->tpl_vars['fields_value'][$item['name']][$language['id_lang']] = Configuration::get(
                        $this->settings_prefix . $item['name'],
                        $language['id_lang']
                    );
                }
            } elseif ($item['type'] == 'checkbox') {
                $val = Configuration::get($this->settings_prefix . $item['name']);
                foreach ($item['values']['query'] as $val_array) {
                    if (strpos($val, $val_array['id_option']) !== false) {
                        $helper->tpl_vars['fields_value'][$item['name'].'_'.$val_array['id_option']] = true;
                    }
                }
            } else {
                $helper->tpl_vars['fields_value'][$item['name']] = Configuration::get(
                    $this->settings_prefix .
                    $item['name']
                );
            }
            if ($item['name'] == 'CUSTOM_CSS') {
                $helper->tpl_vars['fields_value'][$item['name']] = html_entity_decode(
                    Configuration::get($this->settings_prefix . $item['name'])
                );
            }
        }

        return $helper->generateForm($field_forms);
    }

    public function getSettings()
    {
        $settings = array(
            array(
                'type' => 'theme',
                'name' => 'THEME',
                'label' => $this->l('Theme:'),
                'class' => 't',
                'values' => $this->getThemesOptions(),
                'default' => '1-bar.css',
                'col' => 8,
                'validate' => 'isString',
            ),
            'position' => array(
                'type' => 'select',
                'name' => 'POSITION',
                'label' => $this->l('Position at the product page:'),
                'hint' => $this->l('Some of positions may be unavailable in your theme.'),
                'class' => 't',
                'options' => array(
                    'query' => $this->getProductPageSelectOptions(),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'displayProductButtons',
                'validate' => 'isString',
            ),
            'position_list' => array(
                'type' => 'select',
                'name' => 'POSITION_LIST',
                'label' => $this->l('Position in the product list:'),
                'hint' => $this->l('Some of positions may be unavailable in your theme.'),
                'class' => 't',
                'options' => array(
                    'query' => $this->getProductListSelectOptions(),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => '',
                'validate' => 'isString',
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'CUSTOM_STOCK_LEVELS',
                'label' => $this->l('Custom stock levels:'),
                'hint' => $this->l('Enable this option to manually configure stock levels'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'custom_stock_levels_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'custom_stock_levels_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 0,
                'validate' => 'isInt',
            ),
            array(
                'type' => 'text',
                'name' => 'DEFAULT_MAX_QTY',
                'label' => $this->l('Default full stock quantity'),
                'hint' => $this->l('Maximum product quantity in the stock bar.'),
                'default' => 10,
                'class' => 'fixed-width-sm',
                'validate' => 'isInt',
                'form_group_class' => 'psb_regular_levels_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'stock_levels',
                'name' => 'STOCK_LEVELS',
                'label' => $this->l('Stock levels'),
                'levels' => PstStockBarLevel::getLevelsBO(),
                'validate' => 'isString',
                'desc' => $this->l('Out of range behavior: will be applied the highest defined range'),
                'default' => '',
                'form_group_class' => 'psb_custom_levels_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'text',
                'name' => 'COLOR_STOCK_HIGH',
                'label' => $this->l('High stock color:'),
                'hint' => $this->l('Stock bar color for high stock'),
                'class' => 'psbColorPickerInput',
                'validate' => 'isString',
                'default' => '#7db9e8',
                'form_group_class' => 'psb_regular_levels_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'text',
                'name' => 'COLOR_STOCK_MEDIUM',
                'label' => $this->l('Medium stock color:'),
                'hint' => $this->l('Stock bar color for medium stock'),
                'class' => 'psbColorPickerInput',
                'validate' => 'isString',
                'default' => '#ffce1b',
                'form_group_class' => 'psb_regular_levels_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'text',
                'name' => 'COLOR_STOCK_LOW',
                'label' => $this->l('Low stock color:'),
                'hint' => $this->l('Stock bar color for low stock'),
                'class' => 'psbColorPickerInput',
                'validate' => 'isString',
                'default' => '#ff5722',
                'form_group_class' => 'psb_regular_levels_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'html',
                'name' => '',
                'label' => '',
                'html_content' => $this->context->smarty->fetch(
                    $this->local_path . 'views/templates/admin/_more_options_btn.tpl'
                ),
            ),
            array(
                'type' => 'text',
                'name' => 'MAX_WIDTH',
                'label' => $this->l('Max width:'),
                'hint' => $this->l('Leave empty to use auto width'),
                'class' => 'fixed-width-xs',
                'validate' => 'isInt',
                'default' => '',
                'suffix' => 'px',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'text',
                'name' => 'SECTIONS',
                'label' => $this->l('Number of sections'),
                'hint' => $this->l('Number of sections in 2nd and 3rd themes. Default value: 5'),
                'default' => 5,
                'class' => 'fixed-width-sm',
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'HIDE_FULL_STOCK',
                'label' => $this->l('Hide the indicator when stock is full'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'hide_full_stock_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'hide_full_stock_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 0,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'HIDE_EMPTY_STOCK',
                'label' => $this->l('Hide the indicator when stock is empty'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'hide_empty_stock_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'hide_empty_stock_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 0,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'SHOW_PROOFS',
                'label' => $this->l('Show statistics at the product page'),
                'hint' => $this->l('Show how many people bought the product etc.'),
                'validate' => 'isCleanHtml',
                'values' => array(
                    'query' => $this->getStatsOptions(),
                    'id' => 'id_option',
                    'name' => 'name'
                ),
                'required' => false,
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'SHOW_PROOFS_LIST',
                'label' => $this->l('Show statistics in the product list'),
                'hint' => $this->l('Show how many people bought the product etc.'),
                'validate' => 'isCleanHtml',
                'values' => array(
                    'query' => $this->getStatsOptions(),
                    'id' => 'id_option',
                    'name' => 'name'
                ),
                'required' => false,
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'select',
                'name' => 'STATS_PERIOD',
                'label' => $this->l('Show statistics for a period:'),
                'class' => 't',
                'options' => array(
                    'query' => $this->getStatsPeriodSelectOptions(),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'displayProductButtons',
                'validate' => 'isString',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'HIGHLIGHT_BLOCK',
                'label' => $this->l('Highlight the module block'),
                'hint' => $this->l('Highlights the block at the product page: white background, padding etc.'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'highlight_block_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'highlight_block_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 0,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'SHOW_QTY',
                'label' => $this->l('Snow quantity information'),
                'hint' => $this->l('The "Only 1 item left in stock" text and so on.'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'show_qty_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'show_qty_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 1,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'HIDE_DEFAULT_QTY',
                'label' => $this->l('Hide default quantity information'),
                'hint' => $this->l('Hide the quantity information at the product page that PrestaShop displays by default.'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'hide_default_qty_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'hide_default_qty_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 1,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => $this->getPSVersion() == 1.5 ? 'radio' : 'switch',
                'name' => 'QTY_WITH_CART',
                'label' => $this->l('Consider quantity in the cart'),
                'hint' => $this->l('Quantity at the product page will be displayed considering quantity in the cart (total available quantity minus cart quantity)'),
                'class' => 't',
                'values' => array(
                    array(
                        'id' => 'qty_with_cart_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'qty_with_cart_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'default' => 0,
                'validate' => 'isInt',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            'categories' => array(
                'type' => 'categories',
                'label' => $this->l('Show only in these categories:'),
                'hint' => $this->l('Show stock indicators only for products from chosen categories. Uncheck to display everywhere.'),
                'name' => 'CATEGORIES',
                'required' => false,
                'validate' => 'isString',
                'tree' => array(
                    'root_category' => $this->context->shop->id_category,
                    'id' => 'id_category',
                    'name' => 'categoryBox',
                    'selected_categories' => explode(',', $this->categories),
                    'use_checkbox' => true
                ),
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
            array(
                'type' => 'textarea',
                'name' => 'CUSTOM_CSS',
                'label' => $this->l('Custom CSS:'),
                'hint' => $this->l('Add your styles directly into this field without editing files'),
                'validate' => 'isCleanHtml',
                'resize' => true,
                'cols' => '',
                'rows' => '',
                'form_group_class' => 'psb_more_options_row ps'.$this->getPSVersion(true),
            ),
        );

        if ($this->getPSVersion() < 1.6) {
            foreach ($settings as &$item) {
                $desc = isset($item['desc']) ? $item['desc'] : '';
                $hint = isset($item['hint']) ? $item['hint'] . '<br/>' : '';
                $item['desc'] = $hint . $desc;
                $item['hint'] = '';
            }

            // PS1.5 Category input
            $root_category = Category::getRootCategory();
            if (!$root_category->id) {
                $root_category->id = 0;
                $root_category->name = $this->l('Root');
            }
            $root_category = array('id_category' => (int)$root_category->id, 'name' => $root_category->name);
            $trads = array(
                'Root' => $root_category,
                'selected' => $this->l('Selected'),
                'Check all' => $this->l('Check all'),
                'Check All' => $this->l('Check All'),
                'Uncheck All'  => $this->l('Uncheck All'),
                'Collapse All' => $this->l('Collapse All'),
                'Expand All' => $this->l('Expand All'),
                'search' => $this->l('Search a category')
            );
            $settings['categories']['values'] = array(
                'trads' => $trads,
                'selected_cat' => explode(',', $this->categories),
                'input_name' => 'CATEGORIES[]',
                'use_radio' => false,
                'use_search' => true,
                'disabled_categories' => array(),
                'top_category' => Category::getTopCategory(),
                'use_context' => true,
            );
        }

        return $settings;
    }

    protected function loadSettings()
    {
        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }

            $name = Tools::strtolower($item['name']);
            if (isset($item['lang']) && $item['lang']) {
                $this->$name = array();
                foreach (Language::getLanguages() as $language) {
                    $this->{$name}[$language['id_lang']] = Configuration::get(
                        $this->settings_prefix . $item['name'],
                        $language['id_lang']
                    );
                }
            } else {
                $this->$name = Configuration::get(
                    $this->settings_prefix .
                    $item['name']
                );
            }
        }
    }

    protected function getPSVersion($without_dots = false)
    {
        $ps_version = _PS_VERSION_;
        $ps_version = Tools::substr($ps_version, 0, 3);

        if ($without_dots) {
            $ps_version = str_replace('.', '', $ps_version);
        }

        return (float)$ps_version;
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');

        // Register theme CSS
        if ($this->theme) {
            $this->context->controller->addCSS(
                $this->_path . 'views/css/themes/' . $this->theme
            );
        }

        $this->context->controller->addCSS($this->_path . 'views/css/settings.css');

        $this->context->smarty->assign(array(
            'psb_color_high1' => $this->color_stock_high,
            'psb_color_high2' => $this->adjustColor($this->color_stock_high),
            'psb_color_medium1' => $this->color_stock_medium,
            'psb_color_medium2' => $this->adjustColor($this->color_stock_medium),
            'psb_color_low1' => $this->color_stock_low,
            'psb_color_low2' => $this->adjustColor($this->color_stock_low),
            'psb_max_width' => $this->max_width,
            'psb_custom_css' => html_entity_decode($this->custom_css),
            'psb_hide_full_stock' => $this->hide_full_stock,
            'psb_hide_empty_stock' => $this->hide_empty_stock,
            'psb_sections' => $this->sections,
            'psb_theme' => $this->getThemeName(),
            'psb_show_qty' => $this->show_qty,
            'psb_hide_default_qty' => $this->hide_default_qty,
            'psb_psv' => $this->getPSVersion(),
            'psb_ajax_url' => $this->_path.'ajax.php',
            'psb_token' => Tools::getToken(false),
            'psb_custom_levels' => $this->custom_stock_levels,
            'psb_levels' => PstStockBarLevel::getLevelsFO(),
            'psb' => $this,
        ));

        return $this->display(__FILE__, 'header.tpl');
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        $html = '';
        // check whether it's a product page or the module's page
        if ($this->context->controller->controller_name == 'AdminProducts'
            || Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS(array(
                $this->_path.'views/css/jquery.spectrum.css',
                $this->_path.'views/css/admin.css',
            ));
            $this->context->controller->addJquery();
            // at the product page PS1.7 loads scripts before jQuery
            if ($this->getPSVersion() < 1.7 || Tools::getValue('configure') == $this->name) {
                $this->context->controller->addJS(array(
                    $this->_path . 'views/js/jquery.spectrum.min.js',
                    $this->_path . 'views/js/admin_product.js',
                    $this->_path . 'views/js/admin.js',
                ));
            }

            $token = Tools::getAdminTokenLite('AdminModules');
            $ajax_url = 'index.php?tab=AdminModules&configure=' . $this->name . '&token=' . $token;

            $this->context->smarty->assign(array(
                'psv' => $this->getPSVersion(),
                'ajax_url' => $ajax_url,
            ));

            $html = $this->context->smarty->fetch($this->local_path . 'views/templates/hook/admin_header.tpl');
        }

        return $html;
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (isset($params['id_product']) && $params['id_product']) {
            $id_product = $params['id_product'];
        } else {
            $id_product = (int)Tools::getValue('id_product');
        }

        if (!$id_product) {
            return $this->adminDisplayWarning($this->l('You must save this product before using this module.'));
        }

        $token = Tools::getAdminTokenLite('AdminModules');
        $ajax_url = 'index.php?tab=AdminModules&configure=' . $this->name . '&token=' . $token;
        $psb_data = Db::getInstance()->getRow(
            'SELECT *
             FROM `'._DB_PREFIX_.'pststockbar_product`
             WHERE `id_product` = '.(int)$id_product.'
              AND `id_shop` = '.$this->context->shop->id
        );

        if (Validate::isLoadedObject($product = new Product($id_product))) {
            $this->context->smarty->assign(array(
                'psv' => $this->getPSVersion(),
                'psvd' => $this->getPSVersion(true),
                'module_name' => $this->name,
                'psb_display_name' => $this->displayName,
                'languages' => Language::getLanguages(),
                'link' => $this->context->link,
                'ajax_url' => $ajax_url,
                'psb_data' => $psb_data,
                'psb_show_proofs' => ((is_array($psb_data) && isset($psb_data['show_proofs']))
                    ? $psb_data['show_proofs'] : $this->show_proofs),
                'psb_max_qty' => ((is_array($psb_data) && isset($psb_data['max_qty']))
                    ? $psb_data['max_qty'] : $this->default_max_qty),
                'psb_custom_stock_levels' => $this->custom_stock_levels,
                'pststockbar' => $this,
                'psb_sl_input' => array(
                    'type' => 'stock_levels',
                    'name' => 'stock_levels',
                    'label' => $this->l('Stock levels'),
                    'levels' => PstStockBarLevel::getLevelsBO($id_product),
                    'validate' => 'isString',
                    'desc' => $this->l('Out of range behavior: will be applied the highest defined range'),
                    'default' => '',
                    'form_group_class' => 'psb_custom_levels_row ps'.$this->getPSVersion(true),
                ),
                'psb_id_product' => $id_product,
            ));

            // at the product page PS1.7 loads scripts before jQuery
            if ($this->getPSVersion() == 1.7) {
                $this->context->smarty->assign(array(
                    'psb_scripts' => array(
                        $this->_path . 'views/js/jquery.spectrum.min.js',
                        $this->_path . 'views/js/admin_product.js',
                        $this->_path . 'views/js/admin.js',
                    ),
                ));
            }

            return $this->display(__FILE__, 'admin_products_extra'.$this->getPSVersion(true).'.tpl');
        }
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = null;
        if (isset($params['id_product'])) {
            $id_product = $params['id_product'];
        } elseif (isset($params['product']) && is_object($params['product'])) {
            $product = $params['product'];
            $id_product = $product->id;
        } elseif (isset($params['product']) && is_array($params['product'])) {
            $product = $params['product'];
            $id_product = (isset($product['id_product']) ? $product['id_product'] : null);
        }
        if (!$id_product) {
            return false;
        }

        //process only if it's a product page
        if (Tools::isSubmit($this->name . '-submit')) {
            $max_qty = Tools::getValue('max_qty');

            Db::getInstance()->execute(
                'INSERT INTO `' . _DB_PREFIX_ . 'pststockbar_product`
                 (`id_product`, `id_shop`, `max_qty`)
                 VALUES
                 (' . (int)$id_product . ', ' . (int)$this->context->shop->id . ', ' . (int)$max_qty . ')
                 ON DUPLICATE KEY UPDATE
                 `max_qty` = ' . (int)$max_qty
            );
        }
    }

    public function hookPstStockBar($params)
    {
        $params['hook'] = (isset($params['hook']) && $params['hook'] ? $params['hook'] : 'custom');

        return $this->renderWidget($params['hook'], $params);
    }

    public function hookDisplayProductDeliveryTime($params)
    {
        $params['hook'] = 'displayProductDeliveryTime';

        if ($this->context->controller->php_self != 'product' && $this->position_list != $params['hook']
            || $this->context->controller->php_self == 'product' && $this->position != $params['hook']) {
            return false;
        }

        if ($this->context->controller->php_self != 'product' && $this->position_list == $params['hook']) {
            $params['psb_list'] = true;
        }

        return $this->hookPstStockBar($params);
    }

    public function hookDisplayProductListReviews($params)
    {
        $params['hook'] = 'displayProductListReviews';
        $params['psb_list'] = true;

        if ($this->position_list == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookDisplayLeftColumnProduct($params)
    {
        $params['hook'] = 'displayLeftColumnProduct';

        if ($this->position == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookDisplayRightColumnProduct($params)
    {
        $params['hook'] = 'displayRightColumnProduct';

        if ($this->position == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookDisplayFooterProduct($params)
    {
        $params['hook'] = 'displayFooterProduct';

        if ($this->position == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookDisplayProductButtons($params)
    {
        $params['hook'] = 'displayProductButtons';

        if ($this->position == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $params['hook'] = 'displayProductAdditionalInfo';

        if ($this->position == $params['hook']) {
            return $this->hookPstStockBar($params);
        }
    }

    public function hookActionUpdateQuantity($params)
    {
        $this->setLastSaveTime();
    }

    protected function getProductPageSelectOptions()
    {
        $hooks = array(
            array(
                'id_option' => 'no',
                'name' => $this->l('-- (custom hook only) --'),
            ),
            array(
                'id_option' => 'displayProductDeliveryTime',
                'name' => $this->l('Delivery info'),
            ),
            array(
                'id_option' => 'displayProductAdditionalInfo',
                'name' => $this->l('Additional info'),
            ),
            array(
                'id_option' => 'displayProductButtons',
                'name' => $this->l('Product buttons'),
            ),
            array(
                'id_option' => 'displayLeftColumnProduct',
                'name' => $this->l('Extra left'),
            ),
            array(
                'id_option' => 'displayRightColumnProduct',
                'name' => $this->l('Extra right'),
            ),
            array(
                'id_option' => 'displayFooterProduct',
                'name' => $this->l('Product footer'),
            ),
        );

        return $hooks;
    }

    protected function getProductListSelectOptions()
    {
        $hooks = array(
            array(
                'id_option' => 'no',
                'name' => $this->l('-- (custom hook only) --'),
            ),
            array(
                'id_option' => 'displayProductDeliveryTime',
                'name' => $this->l('Delivery info'),
            ),
            array(
                'id_option' => 'displayProductListReviews',
                'name' => $this->l('Reviews'),
            ),
        );

        return $hooks;
    }

    protected function getStatsPeriodSelectOptions()
    {
        $results = array(
            array(
                'id_option' => '',
                'name' => $this->l('All'),
            ),
            array(
                'id_option' => 'year',
                'name' => $this->l('Year'),
            ),
            array(
                'id_option' => 'month',
                'name' => $this->l('Month'),
            ),
            array(
                'id_option' => 'week',
                'name' => $this->l('Week'),
            ),
            array(
                'id_option' => 'day',
                'name' => $this->l('Day'),
            ),
        );

        return $results;
    }

    protected function getDefaultIdProductAttribute($id_product)
    {
        if (!Combination::isFeatureActive()) {
            return 0;
        }

        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT pa.`id_product_attribute`
		     FROM `'._DB_PREFIX_.'product_attribute` pa
			 '.Shop::addSqlAssociation('product_attribute', 'pa').'
			 WHERE pa.`id_product` = '.(int)$id_product.'
			  AND product_attribute_shop.default_on = 1'
        );
    }

    protected function getMaxQty($id_product)
    {
        if ($this->custom_stock_levels) {
            $product_levels = PstStockBarLevel::getCountProductLevels($id_product, $this->context->shop->id);

            $max_qty = Db::getInstance()->getValue(
                'SELECT `max_qty`
                 FROM `'._DB_PREFIX_.'pststockbar_level`
                 WHERE `id_shop` = '.(int)$this->context->shop->id.'
                 '.($product_levels ? ' AND `id_product` = '.(int)$id_product : ' AND `id_product` = 0 ').'
                 ORDER BY `max_qty` DESC'
            );

            return ($max_qty ? $max_qty : $this->default_max_qty);
        } else {
            $max_product_qty = Db::getInstance()->getValue(
                'SELECT `max_qty`
                 FROM `' . _DB_PREFIX_ . 'pststockbar_product`
                 WHERE `id_product` = ' . (int)$id_product . '
                  AND `id_shop` = ' . $this->context->shop->id
            );
            if ($max_product_qty) {
                return $max_product_qty;
            }

            return $this->default_max_qty;
        }
    }

    public function adjustColor($color)
    {
        $color = Tools::strtolower($color);
        $color_array = array();

        // determine color format
        if ($color[0] == '#') {
            // hex
            $color_array = sscanf($color, "#%02x%02x%02x");
            $color_array[3] = '1';
        } elseif (strpos($color, 'rgba') !== false) {
            // rgba
            $color_array = sscanf($color, "rgba(%d, %d, %d, %f)");
        } elseif (strpos($color, 'rgb') !== false) {
            // rgb
            $color_array = sscanf($color, "rgb(%d, %d, %d)");
            $color_array[3] = '1';
        }
        
        if (count($color_array)) {
            list($r, $g, $b, $a) = $color_array;

            $c = 50;
            if ($r == max($color_array)) {
//                $r = min(255, $r + $c);
                $g = max(0, $g - $c);
                $b = max(0, $b - $c);
            } elseif ($g == max($color_array)) {
                $r = max(0, $r - $c);
//                $g = min(255, $g + $c);
                $b = max(0, $b - $c);
            } elseif ($b == max($color_array)) {
                $r = max(0, $r - $c);
                $g = max(0, $g - $c);
//                $b = min(255, $b + $c);
            }

            $color = "rgba($r, $g, $b, $a)";
        }

        return $color;
    }

    public function getProductSalesNumber($id_product, $id_product_attribute = 0)
    {
        if ($this->stats_period) {
            $and_date = '';
            switch ($this->stats_period) {
                case 'day':
                    $and_date = ' AND DATE(o.`date_add`) = CURDATE() ';
                    break;
                case 'week':
                    $and_date = ' AND (o.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW())';
                    break;
                case 'month':
                    $and_date = ' AND (o.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW())';
                    break;
                case 'year':
                    $and_date = ' AND (o.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW())';
                    break;
            }
            $result = Db::getInstance()->getValue(
                'SELECT DISTINCT COUNT(od.`id_order`)
                 FROM `' . _DB_PREFIX_ . 'order_detail` od
                 LEFT JOIN `'._DB_PREFIX_.'orders` o ON od.`id_order` = o.`id_order`
                 WHERE od.`product_id` = ' . (int)$id_product . '
                  AND od.`product_attribute_id` = ' . (int)$id_product_attribute .
                  ($and_date ? pSQL($and_date) : '')
            );
        } else {
            $result = Db::getInstance()->getValue(
                'SELECT DISTINCT COUNT(od.`id_order`)
                 FROM `' . _DB_PREFIX_ . 'order_detail` od
                 WHERE od.`product_id` = ' . (int)$id_product . '
                  AND od.`product_attribute_id` = ' . (int)$id_product_attribute
            );
        }

        return $result;
    }


    public function getCountCartsWithProduct($id_product, $id_product_attribute)
    {
        if ($this->stats_period) {
            $and_date = '';
            switch ($this->stats_period) {
                case 'day':
                    $and_date = ' AND DATE(cp.`date_add`) = CURDATE() ';
                    break;
                case 'week':
                    $and_date = ' AND (cp.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW())';
                    break;
                case 'month':
                    $and_date = ' AND (cp.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW())';
                    break;
                case 'year':
                    $and_date = ' AND (cp.`date_add` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW())';
                    break;
            }
            $carts_count = Db::getInstance()->getValue(
                'SELECT COUNT(cp.`id_cart`)
                 FROM `' . _DB_PREFIX_ . 'cart_product` cp
                 WHERE cp.`id_product` = ' . (int)$id_product . '
                  AND cp.`id_product_attribute` = ' . (int)$id_product_attribute . '
                  AND cp.`id_cart` NOT IN (
                      SELECT `id_cart` FROM `' . _DB_PREFIX_ . 'orders`
                  ) '.
                 ($and_date ? pSQL($and_date) : '')
            );
        } else {
            $carts_count = Db::getInstance()->getValue(
                'SELECT COUNT(cp.`id_cart`)
                 FROM `' . _DB_PREFIX_ . 'cart_product` cp
                 WHERE cp.`id_product` = ' . (int)$id_product . '
                  AND cp.`id_product_attribute` = ' . (int)$id_product_attribute . '
                  AND cp.`id_cart` NOT IN (
                      SELECT `id_cart` FROM `' . _DB_PREFIX_ . 'orders`
                  )'
            );
        }

        return $carts_count;
    }

    protected function getThemesOptions()
    {
        $options = array();

        foreach ($this->getThemes() as $theme) {
            $options[] = array(
                'id' => $theme['file'],
                'value' => $theme['file'],
                'label' => $theme['name'],
                'img' => $this->_path.'views/img/themes/'.$theme['name'].'.png',
            );
        }

        return $options;
    }

    protected function getThemes()
    {
        $themes = array();

        if (file_exists(_PS_MODULE_DIR_ . $this->name . '/views/css/themes/')) {
            $themes_files = scandir(_PS_MODULE_DIR_ . $this->name . '/views/css/themes/');
            natsort($themes_files);
            foreach ($themes_files as $file) {
                if (strpos($file, '.css') !== false) {
                    $pos = strpos($file, '.css');
                    $themes[] = array('file' => $file, 'name' => Tools::substr($file, 0, $pos),);
                }
            }
        }

        return $themes;
    }

    public function getThemeName()
    {
        return trim(str_replace('.css', '', $this->theme));
    }

    public function getStatePeriodTxt()
    {
        $return = '';

        if ($this->stats_period == 'day') {
            $return = $this->l('today');
        } elseif ($this->stats_period == 'week') {
            $return = $this->l('this week');
        } elseif ($this->stats_period == 'month') {
            $return = $this->l('this month');
        }

        return $return;
    }

    public function displayCustomHookInfo()
    {
        $this->context->smarty->assign(array(
            'psv' => $this->getPSVersion(),
            'psvd' => $this->getPSVersion(true),
            'psb_context' => $this->context,
        ));

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/custom_hook_info.tpl');
    }

    public function getStatsOptions()
    {
        $hooks = array(
            array(
                'id_option' => 'order',
                'name' => $this->l('Purchased'),
            ),
            array(
                'id_option' => 'cart',
                'name' => $this->l('Added to cart'),
            ),
        );

        return $hooks;
    }

    public function renderWidget($hookName, array $params)
    {
        $id_product = $this->getParamsIdProduct($params);
        $id_product_attribute = $this->getParamsIdProductAttribute($id_product);
        $cache_key = $hookName . '-' . $this->getLastSaveTime() . '-' . $id_product . '-' .
            $id_product_attribute . '-' . $this->context->language->id;
        $cache_id = $this->getCacheId($cache_key);

        // Category restrictions
        if ($this->categories) {
            $categories = explode(',', $this->categories);
            if (is_array($categories) && count($categories)) {
                if ($id_category = Tools::getValue('id_category')) {  // if it's a category page
                    // stop if the category is not enabled
                    if (!in_array($id_category, $categories)) {
                        return null;
                    }
                } else {  // else get categories from product
                    $product_categories = Product::getProductCategories($id_product);
                    $intercect = array_intersect($product_categories, $categories);
                    // stop if the category is not enabled
                    if (!(is_array($intercect) && count($intercect))) {
                        return null;
                    }
                }
            }
        }

        if ($this->getPSVersion() == 1.7) {
            $tpl_file = 'module:' . $this->name . '/views/templates/hook/hook.tpl';
        } else {
            $tpl_file = 'hook.tpl';
        }

        if (!$this->isCached($tpl_file, $this->getCacheId($cache_key))) {
            $this->context->smarty->assign($this->getWidgetVariables($hookName, $params));
        }

        if ($this->getPSVersion() == 1.7) {
            return $this->fetch($tpl_file, $cache_id);
        } else {
            return $this->display(__FILE__, $tpl_file, $cache_id);
        }
    }

    public function getWidgetVariables($hookName, array $params)
    {
        $id_product = $this->getParamsIdProduct($params);
        if (!$id_product) {
            return null;
        }

        // Get combination
        $id_product_attribute = $this->getParamsIdProductAttribute($id_product);
        $cart_products = $this->context->cart->getProducts();

        // Current quantity
        $current_qty = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute);
        // Display available qty taking into account the cart qty
        if ($this->qty_with_cart) {
            $cart_qty = 0;
            foreach ($cart_products as $cart_product) {
                if ($cart_product['id_product'] == $id_product
                    && $cart_product['id_product_attribute'] == $id_product_attribute) {
                    $cart_qty = $cart_product['cart_quantity'];
                }
            }

            $current_qty = $current_qty - $cart_qty;
        }
        if ($current_qty < 0) {
            $current_qty = 0;
        }
        if (!$current_qty && $this->hide_empty_stock) {
            return false;
        }

        // Max quantity
        $max_qty = $this->getMaxQty($id_product);
        if ($max_qty <= 0) {
            return false;
        }
        $width = 100 * $current_qty / $max_qty;
        $width = min(100, $width); // can't be > 100
        if ($width == 100 && $this->hide_full_stock) {
            return false;
        }

        $sales_number = $this->getProductSalesNumber($id_product, $id_product_attribute);
        $carts_number = $this->getCountCartsWithProduct($id_product, $id_product_attribute);

        $combinations = Product::getProductAttributesIds($id_product, true);
        foreach ($combinations as &$combination) {
            $combination['qty'] = StockAvailable::getQuantityAvailableByProduct(
                $id_product,
                $combination['id_product_attribute']
            );
            if ($this->qty_with_cart) {
                $cart_qty = 0;
                foreach ($cart_products as $cart_product) {
                    if ($cart_product['id_product'] == $id_product
                        && $cart_product['id_product_attribute'] == $combination['id_product_attribute']) {
                        $cart_qty = $cart_product['cart_quantity'];
                    }
                }
                $combination['qty'] -= $cart_qty;
            }
            if ($combination['qty'] < 0) {
                $combination['qty'] = 0;
            }
            $combination['sales_number'] = $this->getProductSalesNumber(
                $id_product,
                $combination['id_product_attribute']
            );
            $combination['carts_number'] = $this->getCountCartsWithProduct(
                $id_product,
                $combination['id_product_attribute']
            );
            $combi_width = 100 * $combination['qty'] / $max_qty;
            $combi_width = min(100, $combi_width); // can't be > 100
            $combination['width'] = $combi_width;
            $combination['bar_class'] = $this->getBarClass($id_product, $combination['qty'], $combi_width);
        }

        $show_proofs = $this->show_proofs;
        if (isset($params['psb_list']) && $params['psb_list']) {
            $show_proofs = $this->show_proofs_list;
        }

        // get the bar class depending on stock level
        $bar_class = $this->getBarClass($id_product, $current_qty, $width);

        return array(
            'psb_current_qty' => $current_qty,
            'psb_width' => $width,
            'psb_bar_class' => $bar_class,
            'psb_hide_full_stock' => $this->hide_full_stock,
            'psb_hide_empty_stock' => $this->hide_empty_stock,
            'psb_sales_number' => $sales_number,
            'psb_carts_number' => $carts_number,
            'psb_show_proofs_orders' => (strpos($show_proofs, 'order') !== false ? true : false),
            'psb_show_proofs_carts' => (strpos($show_proofs, 'cart') !== false ? true : false),
            'psb_stats_period' => $this->stats_period,
            'psb_stats_period_txt' => $this->getStatePeriodTxt(),
            'psb_highlight_block' => $this->highlight_block,
            'psb_theme' => $this->getThemeName(),
            'psb_sections' => $this->sections,
            'psb_max_qty' => $max_qty,
            'psb_combinations' => $combinations,
            'psb_content_tpl' => _PS_MODULE_DIR_.$this->name.'/views/templates/hook/_content.tpl',
            'psb_id_combination_default' => $id_product_attribute,
            'psb_show_qty' => $this->show_qty,
            'psb_psv' => $this->getPSVersion(),
            'psb_custom_levels' => $this->custom_stock_levels,
            'psb_id_product' => $id_product,
        );
    }

    protected function getCacheId($name = null)
    {
        $cache_array = array();
        $cache_array[] = $name !== null ? $name : $this->name;
        if (Configuration::get('PS_SSL_ENABLED')) {
            $cache_array[] = (int)Tools::usingSecureMode();
        }
        if (Shop::isFeatureActive()) {
            $cache_array[] = (int)$this->context->shop->id;
        }
        if (Group::isFeatureActive() && isset($this->context->customer)) {
            $cache_array[] = (int)Group::getCurrent()->id;
            $cache_array[] = implode('_', Customer::getGroupsStatic($this->context->customer->id));
        }
        if (Language::isMultiLanguageActivated()) {
            $cache_array[] = (int)$this->context->language->id;
        }
        if (method_exists('Currency', 'isMultiCurrencyActivated')) {
            if (Currency::isMultiCurrencyActivated()) {
                $cache_array[] = (int)$this->context->currency->id;
            }
        }
        $cache_array[] = (int)$this->context->country->id;
        return implode('|', $cache_array);
    }

    public function initLevels()
    {
        $levels = array(
            array(
                'max_qty' => 0,
                'text' => $this->l('Out of stock'),
                'color' => '#ff5722',
            ),
            array(
                'max_qty' => 1,
                'text' => $this->l('Last product'),
                'color' => '#ff5722',
            ),
            array(
                'max_qty' => 4,
                'text' => $this->l('Low stock'),
                'color' => '#ff5722',
            ),
            array(
                'max_qty' => 8,
                'text' => $this->l('Medium stock'),
                'color' => '#ffce1b',
            ),
            array(
                'max_qty' => '10',
                'text' => $this->l('High stock'),
                'color' => '#7db9e8',
            ),
        );

        // reset tables
        Db::getInstance()->execute(
            'TRUNCATE TABLE `'._DB_PREFIX_.'pststockbar_level`;
             TRUNCATE TABLE `'._DB_PREFIX_.'pststockbar_level_lang`;'
        );

        foreach ($levels as $level) {
            foreach (Shop::getShops() as $shop) {
                $lvl = new PstStockBarLevel();
                $lvl->id_shop = $shop['id_shop'];
                $lvl->max_qty = $level['max_qty'];
                $lvl->color = $level['color'];
                $lvl->text = array();
                foreach (Language::getLanguages() as $language) {
                    $lvl->text[$language['id_lang']] = $level['text'];
                }
                $lvl->save();
            }
        }
    }

    public function generateInput($params)
    {
        if ($params) {
            $this->context->smarty->assign(array(
                'params' => $params,
                'psv' => $this->getPSVersion(),
                'languages' => Language::getLanguages(),
                'id_lang_default' => Configuration::get(
                    'PS_LANG_DEFAULT',
                    null,
                    $this->context->shop->id_shop_group,
                    $this->context->shop->id
                ),
            ));

            return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/input.tpl');
        }
    }

    public function getParamsIdProduct($params)
    {
        // Get id_product
        if (isset($params['id_product']) && $params['id_product'] > 0) {
            $id_product = $params['id_product'];
        } elseif (isset($params['product']) && $params['product']) {
            $product = $params['product'];
            if (is_array($product) && isset($product['id_product'])) {
                $id_product = $product['id_product'];
            } elseif (is_object($product)) {
                $id_product = $product->id;
            } else {
                return false;
            }
        } else {
            $id_product = Tools::getValue('id_product');
        }

        if (!$id_product) {
            return null;
        }

        return $id_product;
    }

    public function getParamsIdProductAttribute($id_product)
    {
        $id_product_attribute = null;
        if (Tools::getValue('group')) {
            $groups = Tools::getValue('group');

            if (!empty($groups) && method_exists('Product', 'getIdProductAttributesByIdAttributes')) {
                $id_product_attribute = (int) Product::getIdProductAttributesByIdAttributes(
                    $id_product,
                    $groups
                );
            }
        }
        if (!$id_product_attribute) {
            $id_product_attribute = Tools::getValue(
                'id_product_attribute',
                $this->getDefaultIdProductAttribute($id_product)
            );
        }

        return $id_product_attribute;
    }

    public function setLastSaveTime()
    {
        Configuration::updateValue($this->settings_prefix.'LAST_SAVE', time()); //used for cache
    }

    public function getLastSaveTime()
    {
        return (string)Configuration::get($this->settings_prefix.'LAST_SAVE');
    }

    protected function regenerateCSS()
    {
        $this->context->smarty->assign(array(
            'psb_color_high1' => $this->color_stock_high,
            'psb_color_high2' => $this->adjustColor($this->color_stock_high),
            'psb_color_medium1' => $this->color_stock_medium,
            'psb_color_medium2' => $this->adjustColor($this->color_stock_medium),
            'psb_color_low1' => $this->color_stock_low,
            'psb_color_low2' => $this->adjustColor($this->color_stock_low),
            'psb_max_width' => $this->max_width,
            'psb_custom_css' => html_entity_decode($this->custom_css),
            'psb_hide_full_stock' => $this->hide_full_stock,
            'psb_hide_empty_stock' => $this->hide_empty_stock,
            'psb_sections' => $this->sections,
            'psb_theme' => $this->getThemeName(),
            'psb_show_qty' => $this->show_qty,
            'psb_hide_default_qty' => $this->hide_default_qty,
            'psb_psv' => $this->getPSVersion(),
            'psb_custom_levels' => $this->custom_stock_levels,
            'psb_levels' => PstStockBarLevel::getLevelsFO(),
            'psb' => $this,
        ));

        $code = $this->context->smarty->fetch($this->local_path . 'views/templates/hook/css.tpl');

        $css_dir = _PS_MODULE_DIR_.$this->name.'/views/css/';
        $css_file = $css_dir.'settings.css';
        if (is_writable($css_dir)) {
            file_put_contents($css_file, $code);
        } else {
            $this->errors[] = $this->l('Please make the "css" directory writable:')
                .' /modules/'.$this->name.'/views/css/';
        }
    }

    public function getBarClass($id_product, $qty, $width)
    {
        if ($this->custom_stock_levels) {
            $product_levels = PstStockBarLevel::getCountProductLevels($id_product, $this->context->shop->id);

            if ($product_levels) {
                $id_level = PstStockBarLevel::getIdLevelByQty($qty, $id_product);
            } else {
                $id_level = PstStockBarLevel::getIdLevelByQty($qty);
            }
            $bar_class = 'psb-lvl-'.$id_level;
        } else {
            if ($width > 66) {
                $bar_class = 'psb-high';
            } elseif ($width > 33) {
                $bar_class = 'psb-medium';
            } else {
                $bar_class = 'psb-low';
            }
        }

        return $bar_class;
    }

    public function ajaxProcessSaveProductLevels()
    {
        $id_product = Tools::getValue('id_product');
        $lvls = Tools::getValue('lvl');
        $success = false;

        if (Tools::isSubmit('max_qty')) {
            $max_qty = Tools::getValue('max_qty');
            Db::getInstance()->execute(
                'INSERT INTO `' . _DB_PREFIX_ . 'pststockbar_product`
                 (`id_product`, `id_shop`, `max_qty`)
                 VALUES
                 (' . (int)$id_product . ', ' . (int)$this->context->shop->id . ', ' . (int)$max_qty . ')
                 ON DUPLICATE KEY UPDATE
                 `max_qty` = ' . (int)$max_qty
            );
            $this->setLastSaveTime();
        }

        foreach ($lvls as $key => $lvl_data) {
            $id = null;
            $id_shop = null;
            // for existing levels get ID, for newly created levels get id_shop and set it later
            if (isset($lvl_data['id'])) {
                $id = $lvl_data['id'];
                unset($lvl_data['id']);
            } else {
                $id_shop = $this->context->shop->id;
            }

            // skip tpl row and empty rows
            if (!$id && $lvl_data['max_qty'] === '' && $lvl_data['color'] === ''
                && !array_filter($lvl_data['text'])) {
                unset($lvls[$key]);
                continue;
            }

            $level = new PstStockBarLevel($id);
            // if it's a general level then just duplicate it for this product
            if ($id && $level->id_product != $id_product) {
                $level = new PstStockBarLevel();
                $id_shop = $this->context->shop->id;
            }
            $level->id_product = $id_product;

            if (isset($lvl_data['delete']) && $lvl_data['delete']) {
                $level->delete();
                continue;
            }

            if ($id_shop) {
                $level->id_shop = $id_shop;
            }
            // assign props
            foreach ($lvl_data as $prop => $data) {
                if (property_exists($level, $prop)) {
                    $level->$prop = $data;
                }
            }

            // validate
            $field_errors = $level->validateAllFields();
            // if no errors
            if (!(is_array($field_errors) && count($field_errors))) {
                $level->save();
                $success = true;
            } else {
                $this->errors += $field_errors;
            }
        }

        if ($success) {
            $this->regenerateCSS();
            $this->setLastSaveTime();
            die('1');
        } else {
            foreach ($this->errors as $error) {
                echo $error;
            }
            exit;
        }
    }

    public function ajaxProcessRenderProductLevels()
    {
        $id_product = Tools::getValue('id_product');

        $this->context->smarty->assign(array(
            'psv' => $this->getPSVersion(),
            'psvd' => $this->getPSVersion(true),
            'module_name' => $this->name,
            'psb_display_name' => $this->displayName,
            'languages' => Language::getLanguages(),
            'link' => $this->context->link,
            'psb_id_product' => $id_product,
            'psb_custom_stock_levels' => $this->custom_stock_levels,
            'pststockbar' => $this,
            'input' => array(
                'type' => 'stock_levels',
                'name' => 'stock_levels',
                'label' => $this->l('Stock levels'),
                'levels' => PstStockBarLevel::getLevelsBO($id_product),
                'validate' => 'isString',
                'desc' => $this->l('Out of range behavior: will be applied the highest defined range'),
                'default' => '',
                'form_group_class' => 'psb_custom_levels_row ps'.$this->getPSVersion(true),
            ),
        ));

        $html = $this->context->smarty->fetch(
            $this->local_path . 'views/templates/admin/_configure/helpers/form/_stock_levels.tpl'
        );

        die($html);
    }
}
