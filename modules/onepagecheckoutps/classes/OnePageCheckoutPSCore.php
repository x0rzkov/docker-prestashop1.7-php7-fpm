<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2019 PresTeamShop
 * @license   see file: LICENSE.txt
 *
 * @category  PrestaShop
 * @category  Module
 * @revision  23
 */

class OnePageCheckoutPSCore
{
    const NAME_MODULE = 'OnePageCheckoutPS';
    const NAME_CLASS = 'OnePageCheckoutPSCore';

    public $CODE_ERROR = -1;
    public $CODE_SUCCESS = 0;

    protected $module;

    public function __construct($module, $context)
    {
        $this->module = $module;
        $this->context = $context;

        $this->module->override_css = $this->module->prefix_module . '_OVERRIDE_CSS';
        $this->module->override_js = $this->module->prefix_module . '_OVERRIDE_JS';
        $this->module->path = _MODULE_DIR_ . $this->module->name . '/';
        $this->module->translations_path = _PS_MODULE_DIR_ . $this->module->name . '/translations/';

        $this->fillGlobalVars();
        $this->fillConfigVars();
    }

    public function install()
    {
        foreach ($this->module->configure_vars as $config) {
            if (!Configuration::updateValue($config['name'], $config['default_value'], $config['is_html'])) {
                return false;
            }
        }

        $this->installTab();

        if (!$this->executeFileSQL('install')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        foreach ($this->module->configure_vars as $config) {
            Configuration::deleteByName($config['name']);
        }

        if ($id_tab = Tab::getIdFromClassName('AdminActions' . $this->module->prefix_module)) {
            $tab = new Tab((int) $id_tab);
            $tab->delete();
        }

        if (!$this->executeFileSQL('uninstall')) {
            return false;
        }

        if (isset($this->context->smarty->registered_plugins['modifier']['escape'])) {
            $this->context->smarty->unregisterPlugin('modifier', 'escape');
        }

        //clear compile templates
        $this->context->smarty->clearCompiledTemplate();

        return true;
    }

    public function installTab()
    {
        $id_tab = Tab::getIdFromClassName('AdminActions' . $this->module->prefix_module);
        if (!empty($id_tab)) {
            $tab = new Tab((int) $id_tab);
            $tab->delete();
        }

        if (!Tab::getIdFromClassName('AdminActions' . $this->module->prefix_module)) {
            $name_tab = array();
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $name_tab[$lang['id_lang']] = $this->module->displayName;
            }

            $tab = new Tab();
            $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentModulesSf');
            $tab->class_name = 'AdminActions' . $this->module->prefix_module;
            $tab->module = $this->module->name;
            $tab->name = $name_tab;
            $tab->save();
        }
    }

    private function fillGlobalVars()
    {
        $this->module->globals->type_control = (object) array(
            'select' => 'select',
            'textbox' => 'textbox',
            'textarea' => 'textarea',
            'radio' => 'radio',
            'checkbox' => 'checkbox',
        );

        $this->module->globals->lang = new stdClass();
        $this->module->globals->lang->type_control = array(
            'select' => $this->module->l('List', self::NAME_CLASS),
            'textbox' => $this->module->l('Textbox', self::NAME_CLASS),
            'textarea' => $this->module->l('Textarea', self::NAME_CLASS),
            'radio' => $this->module->l('Radio button', self::NAME_CLASS),
            'checkbox' => $this->module->l('Checkbox', self::NAME_CLASS),
        );
    }

    public function getContentUpgrade()
    {
        //update version module
        //---------------------------------------------------------------------------
        $registered_version = Configuration::get($this->module->prefix_module.'_VERSION');
        if ($registered_version != $this->module->version) {
            $overrides = $this->searchOverrides();
            if ($overrides) {
                $this->context->smarty->assign(array(
                    'overrides' => $overrides,
                    'message_override_files' => $this->module->l('The overrides found are the following:', self::NAME_CLASS),
                    'message_override_text' => $this->module->l('We have found that there are overrides of the module in your store, it is necessary to rename these files or their folders to update the module version. Once you have updated you can pass these changes back to the module respecting the changes that the files have had in the version.', self::NAME_CLASS)
                ));
            } else {
                $this->installTab();

                $this->context->smarty->assign(array(
                    'token' => Tools::encrypt($this->module->name.'/index'),
                    'module_name' => $this->module->displayName,
                    'module_version' => $this->module->version,
                    'url_call' => $this->context->link->getAdminLink('AdminActions'.$this->module->prefix_module),
                    'message_updated' => $this->module->l('We have detected you uploaded the new version', self::NAME_CLASS),
                    'message_module' => $this->module->l('of our module', self::NAME_CLASS),
                    'message_click' => $this->module->l('To proceed with the update, you need to click here', self::NAME_CLASS),
                    'message_now' => $this->module->l('Update now', self::NAME_CLASS)
                ));
            }

            $this->module->html = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/helper/update_version.tpl');
        }
    }

    //removeIf(addons)
    public function getContent()
    {
        if (!function_exists('curl_init')
            && !function_exists('curl_setopt')
            && !function_exists('curl_exec')
            && !function_exists('curl_close')
        ) {
            $this->module->errors[] = $this->module->l('CURL functions not available for registration module.', self::NAME_CLASS);
        } else {
            $id_shop_group = 0;
            $id_shop = 0;

            $params = array(
                'server' => array('SERVER_NAME' => $_SERVER['SERVER_NAME']),
                'module_name' => $this->module->name,
                'version_module' => Configuration::get($this->module->prefix_module . '_VERSION'),
                'ps_version' => _PS_VERSION_,
                'url_store' => $this->context->shop->getBaseURL(),
            );

            if (Tools::isSubmit('validate_license')) {
                $params['license_number'] = Tools::getValue('license_number');

                if (Tools::getIsset('license_number') && !empty($params['license_number'])) {
                    $response = Tools::jsonDecode($this->sendRequest($params));

                    if (is_object($response)) {
                        if ($response->code == $this->CODE_ERROR) {
                            $this->module->errors[] = $response->message;
                        } elseif ($response->code == $this->CODE_SUCCESS) {
                            Configuration::deleteByName($this->module->prefix_module . '_DOMAIN');
                            Configuration::deleteByName($this->module->prefix_module . '_RM');

                            if (version_compare(_PS_VERSION_, '1.5') >= 0) {
                                Configuration::updateValue(
                                    $this->module->prefix_module . '_DOMAIN',
                                    $response->domain,
                                    $id_shop_group,
                                    $id_shop
                                );

                                Configuration::updateValue(
                                    $this->module->prefix_module . '_RM',
                                    '1',
                                    $id_shop_group,
                                    $id_shop
                                );
                            } else {
                                Configuration::updateValue($this->module->prefix_module . '_DOMAIN', $response->domain);
                                Configuration::updateValue($this->module->prefix_module . '_RM', '1');
                            }

                            $this->fillConfigVars();

                            $this->module->html .= $this->module->displayConfirmation($response->message);
                        }
                    }
                } else {
                    $this->module->errors[] = $this->module->l('Please enter the license to do the validation of the module.');
                }
            }
        }
    }

    //endRemoveIf(addons)
    public function createMD5OfFileContent($url)
    {
        $content = Tools::file_get_contents($url);
        return md5($content);
    }

    public function searchThemeOverrides($dir, &$overrides)
    {
        if (file_exists($dir)
            && ($sub_dirs = array_diff(scandir($dir), array('.', '..', '.svn', 'index.php')))
        ) {
            foreach ($sub_dirs as $d) {
                if (is_dir($dir.'/'.$d)) {
                    $this->searchThemeOverrides($dir.'/'.$d, $overrides);
                } else {
                    $arr_ext = explode('.', $d);
                    $ext     = end($arr_ext);

                    if (in_array($ext, array('tpl', 'css', 'js'))) {
                        $module_file    = _PS_MODULE_DIR_.str_replace(_PS_THEME_DIR_.'modules', '', $dir).'/'.$d;
                        $md5_file       = $this->createMD5OfFileContent($module_file);
                        $md5_override   = $this->createMD5OfFileContent($dir.'/'.$d);

                        if ($md5_file !== $md5_override) {
                            $file_dir       = str_replace(_PS_ROOT_DIR_, '', $dir);
                            $overrides[]    = $file_dir.'/'.$d;
                        }
                    }
                }
            }
        }
    }

    public function searchOverrides()
    {
        $overrides = array();
        if (file_exists(_PS_OVERRIDE_DIR_.'modules/'.$this->module->name.'/'.$this->module->name.'.php')) {
            $md5_file       = $this->createMD5OfFileContent(_PS_MODULE_DIR_.$this->module->name.'/'.$this->module->name.'.php');
            $md5_override   = $this->createMD5OfFileContent(_PS_OVERRIDE_DIR_.'modules/'.$this->module->name.'/'.$this->module->name.'.php');

            if ($md5_file !== $md5_override) {
                $overrides[] = '/override/modules/'.$this->module->name.'/'.$this->module->name.'.php';
            }
        }

        $theme_module_dir = _PS_THEME_DIR_.'modules/'.$this->module->name.'/views';
        $this->searchThemeOverrides($theme_module_dir, $overrides);

        return (is_array($overrides) && count($overrides) > 0) ? $overrides : false;
    }
    
    public function displayForm()
    {
        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/jquery/plugins/growl/jquery.growl.css',
            'all'
        );
        $this->context->controller->addJS(
            $this->module->path . 'views/js/lib/jquery/plugins/growl/jquery.growl.js?v=' . $this->module->version,
            'all'
        );

        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/simple-switch/simple-switch.css',
            'all'
        );
        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/bootstrap/pts/pts-bootstrap.css',
            'all'
        );

        //back
        $this->context->controller->addJS(
            $this->module->path . 'views/js/admin/configure.js?v=' . $this->module->version
        );
        $this->context->controller->addJS(
            $this->module->path . 'views/js/lib/pts/tools.js?v=' . $this->module->version
        );

        $this->context->controller->addCSS(
            $this->module->path . 'views/css/admin/configure.css',
            'all'
        );
        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/pts/tools.css',
            'all'
        );
        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/pts/pts-menu.css',
            'all'
        );
        $this->context->controller->addCSS(
            $this->module->path . 'views/css/lib/font-awesome/font-awesome.css',
            'all'
        );

        $default_lang = Configuration::get('PS_LANG_DEFAULT');
        $iso = $this->context->language->iso_code;

        $server_name = Tools::strtolower($_SERVER['SERVER_NAME']);
        $server_name = str_ireplace('www.', '', $server_name);

        $url_store = $this->getUrlStore() . $this->context->shop->getBaseURI() . 'modules/' . $this->module->name;

        $query_string = str_replace('&conf=12', '', $_SERVER['QUERY_STRING']);
        $action_url = Tools::safeOutput($_SERVER['PHP_SELF']) . '?' . $query_string;

        $this->module->params_back = array_merge(
            array(
                'ACTIONS_CONTROLLER_URL' => $this->context->link->getAdminLink(
                    'AdminActions' . $this->module->prefix_module
                ),
                'MODULE_DIR' => $this->module->path,
                'MODULE_IMG' => $this->module->path . 'views/img/',
                'MODULE_NAME' => $this->module->name,
                'MODULE_VERSION' => $this->module->version,
                'MODULE_TPL' => _PS_ROOT_DIR_ . '/modules/' . $this->module->name . '/',
                'CONFIGS' => $this->module->config_vars,
                'ISO_LANG' => $iso,
                'GLOBALS' => $this->module->globals,
                'VERSION' => $this->module->version,
                'SUCCESS_CODE' => $this->CODE_SUCCESS,
                'ERROR_CODE' => $this->CODE_ERROR,
                'SERVER_NAME' => $server_name,
                'MODULE_PATH_ABSOLUTE' => realpath(dirname(__FILE__) . '/../'),
                'URL_STORE' => $url_store,
                'ACTION_URL' => $action_url,
                'ERRORS' => $this->module->errors,
                'WARNINGS' => $this->module->warnings,
                'CODE_EDITORS' => $this->codeEditors(),
                'TRANSLATIONS' => $this->translations('get'),
                'ISO_LANG_BACKOFFICE_SHOP' => Language::getIsoById($this->context->employee->id_lang),
                $this->module->prefix_module . '_STATIC_TOKEN' => Tools::encrypt($this->module->name . '/index'),
            ),
            $this->module->params_back
        );

        Media::addJsDef(
            array(
                self::NAME_MODULE => $this->module->params_back,
                'PresTeamShop' => array(
                    'pts_static_token' => Tools::encrypt($this->module->name . '/index'),
                    'module_dir' => $this->module->params_back['MODULE_DIR'],
                    'module_img' => $this->module->params_back['MODULE_IMG'],
                    'class_name' => 'APP' . $this->module->prefix_module,
                    'iso_lang' => $this->module->params_back['ISO_LANG'],
                    'success_code' => $this->CODE_SUCCESS,
                    'error_code' => $this->CODE_ERROR,
                    'actions_controller_url' => $this->context->link->getAdminLink(
                        'AdminActions' . $this->module->prefix_module
                    ),
                    'iso_lang_backoffice_shop' => Language::getIsoById($this->context->employee->id_lang),
                    'id_language_default' => $default_lang,
                ),
            )
        );

        $this->context->smarty->assign('paramsBack', $this->module->params_back);
    }

    private function executeFileSQL($file_name)
    {
        if (!file_exists(dirname(__FILE__) . '/../sql/' . $file_name . '.sql')) {
            return true;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__) . '/../sql/' . $file_name . '.sql')) {
            return false;
        }

        $sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
        $sql = str_replace('MYSQL_ENGINE', _MYSQL_ENGINE_, $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                return false;
            }
        }

        return true;
    }

    public function fillConfigVars()
    {
        if (!Module::isInstalled($this->module->name)) {
            return false;
        }

        $languages = Language::getLanguages(false);
        foreach ($this->module->configure_vars as $config) {
            if (isset($config['is_bool']) && $config['is_bool']) {
                $this->module->config_vars[$config['name']] = (bool) Configuration::get($config['name']);
            } else {
                $this->module->config_vars[$config['name']] = Configuration::get($config['name']);

                if (isset($config['is_lang']) && $config['is_lang']) {
                    $this->module->config_vars[$config['name']] = array();
                    foreach ($languages as $language) {
                        $this->module->config_vars[$config['name']][$language['id_lang']] = Configuration::get(
                            $config['name'],
                            $language['id_lang']
                        );
                    }
                }
            }
        }

        $this->module->config_vars[$this->module->prefix_module . '_RM'] = Configuration::get(
            $this->module->prefix_module . '_RM'
        );
    }

    public function sendEmail(
        $email,
        $subject,
        $values = array(),
        $template_name = 'default',
        $email_from = null,
        $to_name = null,
        $lang = null,
        $file_attachment = null
    ) {
        if ($lang == null) {
            $lang = (int) Configuration::get('PS_LANG_DEFAULT');
        }
        if ($email_from == null) {
            $email_from = (string) Configuration::get('PS_SHOP_EMAIL');
        }

        return Mail::Send(
            $lang,
            $template_name,
            $subject,
            $values,
            $email,
            $to_name,
            $email_from,
            null,
            $file_attachment,
            null,
            _PS_MODULE_DIR_ . $this->module->name . '/mails/'
        );
    }

    public function updateVersion()
    {
        $registered_version = Configuration::get($this->module->prefix_module . '_VERSION');

        if ($registered_version != $this->module->version) {
            $list = array();

            $upgrade_path = _PS_MODULE_DIR_ . $this->module->name . '/upgrades/';

            // Check if folder exist and it could be read
            if (file_exists($upgrade_path) && ($files = scandir($upgrade_path))) {
                // Read each file name
                foreach ($files as $file) {
                    if (!in_array($file, array('.', '..', '.svn', 'index.php'))) {
                        $tab = explode('-', $file);
                        $file_version = basename($tab[1], '.php');
                        // Compare version, if minor than actual, we need to upgrade the module
                        if (count($tab) == 2 && version_compare($registered_version, $file_version) < 0) {
                            $list[] = array(
                                'file' => $upgrade_path . $file,
                                'version' => $file_version,
                                'upgrade_function' => 'upgrade_module_' . str_replace('.', '_', $file_version), );
                        }
                    }
                }
            }
            usort($list, array($this, 'moduleVersionSort'));
            foreach ($list as $num => $file_detail) {
                include $file_detail['file'];

                // Call the upgrade function if defined
                if (function_exists($file_detail['upgrade_function'])) {
                    $file_detail['upgrade_function']($this->module);
                }

                unset($list[$num]);
            }

            Configuration::updateValue($this->module->prefix_module . '_VERSION', $this->module->version);

            $this->fillConfigVars();
            $this->saveContentCodeEditors();

            Tools::clearSmartyCache();
            Tools::clearCache();
        }

        return 'OK';
    }

    public function checkModulePTS()
    {
        //removeIf(addons)
        $server_name = Tools::strtolower($_SERVER['SERVER_NAME']);
        $server_name = str_ireplace('www.', '', $server_name);

        $match = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
        $is_ip = preg_match($match, $server_name);

        $tmp_valid = false;
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            if (!$is_ip && $server_name != 'localhost') {
                $shops = Shop::getShops();

                $is_find_domain = false;
                foreach ($shops as $shop) {
                    $domain_multishop = Tools::strtolower($shop['domain']);
                    $domain_multishop = str_ireplace('www.', '', $domain_multishop);

                    if ($domain_multishop == $server_name) {
                        $is_find_domain = true;

                        break;
                    }
                }

                if ($is_find_domain) {
                    $tmp_valid = true;
                }
            } else {
                $tmp_valid = true;
            }
        } else {
            if (!$is_ip && $server_name != 'localhost') {
                $opc_domain = Configuration::get($this->module->prefix_module . '_DOMAIN');

                if ($opc_domain == md5($server_name . 't3mp0r4l')) {
                    $tmp_valid = true;
                }
            } else {
                $tmp_valid = true;
            }
        }

        if (!$tmp_valid) {
            Configuration::updateValue($this->module->prefix_module . '_RM', '0');

            return false;
        }
        //endRemoveIf(addons)
        return true;
    }

    public function isVisible()
    {
        $display_module = true;
        $enable_debug = $this->module->config_vars[$this->module->prefix_module . '_ENABLE_DEBUG'];

        if ($enable_debug) {
            $display_module = false;
            $my_ip = Tools::getRemoteAddr();
            $ip_debug = $this->module->config_vars[$this->module->prefix_module . '_IP_DEBUG'];
            $array_ips_debug = explode(',', $ip_debug);

            if (in_array($my_ip, $array_ips_debug)) {
                $display_module = true;
            }
        }

        if ($display_module) {
            $registered_version = Configuration::get($this->module->prefix_module . '_VERSION');
            if ($registered_version != $this->module->version) {
                $display_module = false;
            }
        }

        return $display_module;
    }

    //removeIf(addons)
    private function sendRequest($params)
    {
        $ch = curl_init();

        $params = array('params' => Tools::jsonEncode($params));
        $url = 'http://www.presteamshop.com/pts_rm.php';

        if (Configuration::get('PS_SSL_ENABLED')) {
            $url = 'https://www.presteamshop.com/pts_rm.php';
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    //endRemoveIf(addons)
    public function copyOverride($file)
    {
        $source = _PS_MODULE_DIR_ . $this->module->name . '/public/' . $file;
        $dest = _PS_ROOT_DIR_ . '/' . $file;

        $path_dest = dirname($dest);

        if (!is_dir($path_dest)) {
            if (!mkdir($path_dest, 0777, true)) {
                return false;
            }
        }

        if (@copy($source, $dest)) {
            $path_cache_file = _PS_ROOT_DIR_ . '/cache/class_index.php';
            if (file_exists($path_cache_file)) {
                unlink($path_cache_file);
            }

            return true;
        }

        return false;
    }

    public function existOverride($filename, $key = false)
    {
        $file = _PS_ROOT_DIR_ . '/' . $filename;

        if (file_exists($file)) {
            if ($key) {
                $file_content = Tools::file_get_contents($file);
                if (preg_match($key, $file_content) > 0) {
                    return true;
                }

                return false;
            }

            return true;
        }

        return false;
    }

    public function isModuleActive($name_module, $function_exist = false)
    {
        if (Module::isInstalled($name_module)) {
            $module = Module::getInstanceByName($name_module);
            if (Validate::isLoadedObject($module) && $module->active) {
                $sql = new DbQuery();
                $sql->from('module_shop', 'm');
                $sql->where('m.id_module = ' . (int) $module->id);
                $sql->where('m.enable_device & ' . (int) Context::getContext()->getDevice());
                $sql->where('m.id_shop = ' . (int) Context::getContext()->shop->id);

                $active_device = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

                if ($active_device) {
                    if ($function_exist) {
                        if (method_exists($module, $function_exist)) {
                            return $module;
                        } else {
                            return false;
                        }
                    }

                    return $module;
                }
            }
        }

        return false;
    }

    public function getUrlStore()
    {
        return Configuration::get('PS_SSL_ENABLED') ? Tools::getShopDomainSsl(true) : Tools::getShopDomain(true);
    }

    public function getServerIpAddress()
    {
        $server_addr = $_SERVER['SERVER_ADDR'];
        if ($server_addr === '::1') {
            $hostname = php_uname('n');
            $server_addr = gethostbyname($hostname);
        }

        return $server_addr;
    }

    private function moduleVersionSort($a, $b)
    {
        return version_compare($a['version'], $b['version']);
    }

    /**
     * Customize save data from form.
     *
     * @param type $option
     * @param string $config_var_value
     */
    private function saveCustomConfigValue($option, &$config_var_value)
    {
        switch ($option['name']) {
            case 'custom':
                $config_var_value = '';
                break;
        }
    }

    /**
     * @internal This method is not editable, use <b>saveCustomConfigValue</b> if necessary
     *
     * @param type $option
     */
    private function saveConfigValue($option)
    {
        $config_var_name = $this->module->prefix_module . '_' . $option['name'];
        $config_var_name = Tools::strtoupper($config_var_name);

        if (array_key_exists($config_var_name, $this->module->config_vars)) {
            if (isset($option['multilang'])) {
                $languages = Language::getLanguages(false);
                $config_var_value = array();

                foreach ($languages as $language) {
                    $config_var_value[$language['id_lang']] = Tools::getValue(
                        $option['name'] . '_' . $language['id_lang']
                    );
                }
            } else {
                $config_var_value = Tools::getValue($option['name'], null);
            }

            switch ($option['type']) {
                case $this->module->globals->type_control->checkbox:
                    $config_var_value = (int) ((is_null($config_var_value) || empty($config_var_value)) ? false : true);
                    break;
                case $this->module->globals->type_control->select:
                    if (isset($option['multiple']) && $option['multiple']) {
                        if (is_array($config_var_value) && count($config_var_value)) {
                            $config_var_value = implode(',', $config_var_value);
                        } else {
                            $config_var_value = '';
                        }
                    }
                    break;
                default:
                    $config_var_value = (is_null($config_var_value)) ? '' : $config_var_value;
                    break;
            }

            //call function to save some options by custom restrictions or data treatment
            if (method_exists($this->module, 'saveCustomConfigValue')) {
                $this->module->saveCustomConfigValue($option, $config_var_value);
            }

            //save value
            $is_html = $option['type'] === 'wysiwyg' ? true : false;
            if (!Configuration::updateValue($config_var_name, $config_var_value, $is_html)) {
                $this->module->errors[] = $this->module->l('An error occurred while trying update', self::NAME_CLASS) . ': ' . $option['label'];
            }

            //if dependencies
            if (isset($option['depends']) && is_array($option['depends']) && count($option['depends'])) {
                foreach ($option['depends'] as $dependency_option) {
                    $this->saveConfigValue($dependency_option);
                }
            }
        }
    }

    /**
     * Save data configuration from post form.
     *
     * @param type $form
     */
    public function saveFormData($form)
    {
        if (isset($form['options']) && is_array($form['options']) && count($form['options'])) {
            foreach ($form['options'] as $option) {
                $this->saveConfigValue($option);
            }
            $this->fillConfigVars();
        }
    }

    public function writeLog($error = null)
    {
        $name_error = Tools::getValue('name_error', 'Internal error');
        $code_error = Tools::getValue('code_error', '000');
        $error = Tools::getValue('error', $error);
        $data_sent = Tools::getValue('data_sent');

        $name_log = date('Ymd') . '_error.log';

        $message = '[' . $code_error . '] ' . $name_error . "\n" . $error . "\n\n" . $data_sent . "\n";
        $message .= '----------------------------------------------------------------' . "\n\n";

        $file_log = fopen(dirname(__FILE__) . '/../log/' . $name_log, 'a+');
        fwrite($file_log, $message);
        fclose($file_log);

        if (class_exists('PrestaShopLogger')) {
            PrestaShopLogger::addLog($message, 3, $code_error, self::NAME_MODULE);
        }

        return 'An internal error has occurred. Please inform the administrator of the store, thank you.';
    }

    public function truncateChars($text, $limit, $ellipsis = '...')
    {
        if (Tools::strlen($text) > $limit) {
            $text = trim(Tools::substr($text, 0, $limit)) . $ellipsis;
        }

        return $text;
    }

    public function getCacheId($extra_params = array())
    {
        $cacheId = '';
        $cache_array = array();

        $cache_array[] = $this->module->name;
        $cache_array[] = (int) $this->context->shop->id;
        $cache_array[] = (int) $this->context->language->id;
        $cache_array[] = (int) $this->context->currency->id;

        $cacheId = implode('|', $cache_array);
        $cacheId .= '|' . implode('|', $extra_params);

        return $cacheId;
    }

    public function getFormatedName($name)
    {
        $theme_name = Context::getContext()->shop->theme_name;
        $name_without_theme_name = str_replace(array('_' . $theme_name, $theme_name . '_'), '', $name);

        //check if the theme name is already in $name if yes only return $name
        if (strstr($name, $theme_name) && self::getByNameNType($name)) {
            return $name;
        } elseif (self::getByNameNType($name_without_theme_name . '_' . $theme_name)) {
            return $name_without_theme_name . '_' . $theme_name;
        } elseif (self::getByNameNType($theme_name . '_' . $name_without_theme_name)) {
            return $theme_name . '_' . $name_without_theme_name;
        } else {
            return $name_without_theme_name . '_default';
        }
    }

    public static function getByNameNType($name, $type = null)
    {
        $results = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'image_type`');

        $types = array('products', 'categories', 'manufacturers', 'suppliers', 'scenes', 'stores');
        $total = count($types);
        foreach ($results as $result) {
            foreach ($result as $value) {
                $value = $value;
                for ($i = 0; $i < $total; ++$i) {
                    if ($name === $result['name'] && $types[$i] === $type) {
                        return $result;
                    }
                }
            }
        }

        return false;
    }

    /* global tabs */
    public function codeEditors()
    {
        $override_css = Configuration::get($this->module->override_css);
        /*$override_css = str_replace('{', '\{', $override_css);*/
        /*$override_css = str_replace('}', '\}', $override_css);*/

        $override_js = Configuration::get($this->module->override_js);
        $override_js = html_entity_decode($override_js, ENT_QUOTES, 'UTF-8');
        /*$override_js = str_replace('{', '\{', $override_js);*/
        /*$override_js = str_replace('}', '\}', $override_js);*/

        $code_editors = array(
            'css' => array(
                array(
                    'filepath' => realpath(_PS_MODULE_DIR_ . $this->module->name . '/views/css/front/override.css'),
                    'filename' => 'override',
                    'content' => $override_css,
                ),
            ),
            'javascript' => array(
                array(
                    'filepath' => realpath(_PS_MODULE_DIR_ . $this->module->name . '/views/js/front/override.js'),
                    'filename' => 'override',
                    'content' => $override_js,
                ),
            ),
        );

        return $code_editors;
    }

    public function saveContentCodeEditors()
    {
        $content = Tools::getValue('content');
        $filepath = urldecode(Tools::getValue('filepath'));

        if (!file_exists($filepath)) {
            touch($filepath);
        }

        if (is_writable($filepath)) {
            $filetype = pathinfo($filepath);
            if ($filetype['extension'] === 'css') {
                Configuration::updateValue($this->module->override_css, $content);
            } elseif ($filetype['extension'] === 'js') {
                Configuration::updateValue($this->module->override_js, $content);
            }

            $this->fillConfigVars();

            $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
            file_put_contents($filepath, $content);

            return array(
                'message_code' => $this->CODE_SUCCESS,
                'message' => $this->module->l('The code was successfully saved', self::NAME_CLASS),
            );
        }

        return array(
            'message_code' => $this->CODE_ERROR,
            'message' => $this->module->l('Failed to save changes', self::NAME_CLASS),
        );
    }

    public function getTranslations($iso_code = '')
    {
        if (empty($iso_code)) {
            if (isset($this->context->cookie->id_lang)) {
                $id_lang = $this->context->cookie->id_lang;
            } else {
                $id_lang = Configuration::get('PS_LANG_DEFAULT');
            }

            $iso_code = Language::getIsoById($id_lang);
        }

        $array_translate_lang_selected = $this->readFile($this->module->name, $iso_code);

        if (Tools::isSubmit('iso_code')) {
            return array('message_code' => $this->CODE_SUCCESS, 'data' => $array_translate_lang_selected);
        }

        $array_translate = $this->readFile($this->module->name, 'en', true);

        if (sizeof($array_translate)) {
            $array_translate_lang_selected = $this->readFile($this->module->name, $iso_code);

            foreach ($array_translate as $key_page => $translate_en) {
                foreach ($translate_en as $md5 => $label) {
                    $label = $label;
                    if (!empty($md5) && !empty($key_page)) {
                        $array_translate[$key_page][$md5]['lang_selected'] = '';

                        if (isset($array_translate_lang_selected[$key_page][$md5])) {
                            $array_translate[$key_page][$md5]['lang_selected']
                                = $array_translate_lang_selected[$key_page][$md5];
                        } elseif (!isset($array_translate[$key_page]['empty_elements'])) {
                            $array_translate[$key_page]['empty_elements'] = true;
                        }
                    }
                }
            }
        }

        return $array_translate;
    }

    public function readFile($module, $iso_code, $default = false)
    {
        $file_name = $iso_code . '.php';
        $file_path = realpath($this->module->translations_path . $file_name);

        if (!file_exists($file_path)) {
            return array();
        }

        $file = fopen($file_path, 'r') or exit($this->module->l('Unable to open file', self::NAME_CLASS));

        $array_translate = array();

        while (!feof($file)) {
            $line = fgets($file);
            $line_explode = explode('=', $line);

            $search_string = strpos($line_explode[0], '<{' . $module . '}prestashop>');

            if (array_key_exists(1, $line_explode) && $search_string) {
                $file_md5 = str_replace('$' . "_MODULE['<{" . $module . '}prestashop>', '', $line_explode[0]);
                $file_md5 = str_replace("']", '', trim($file_md5));

                $explode_file_md5 = explode('_', $file_md5);
                $md5 = array_pop($explode_file_md5);
                $file_name = join('_', $explode_file_md5);

                $label_title = $file_name;
                $description_lang = trim($line_explode[1]);
                $description_lang = str_replace('\';', '', $description_lang);
                $description_lang = Tools::substr($description_lang, 1);

                $description_lang = str_replace("\'", "'", $description_lang);

                if ($default) {
                    $array_translate[$label_title][$md5] = array(
                        $iso_code => $description_lang,
                    );
                } else {
                    $array_translate[$label_title][$md5] = $description_lang;
                }
            }
        }

        fclose($file);

        return $array_translate;
    }

    public function downloadFileTranslation($iso_code)
    {
        $file_name = $iso_code . '.php';
        $file_path = realpath($this->module->translations_path . $file_name);

        if (file_exists($file_path)) {
            header('Content-Disposition: attachment; filename=' . $iso_code . '.php');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        }
    }

    public function shareTranslation($iso_code)
    {
        $file_name = $iso_code . '.php';
        $file_path = realpath($this->module->translations_path . $file_name);

        if (file_exists($file_path)) {
            $file_attachment = array();
            $file_attachment['content'] = Tools::file_get_contents($file_path);
            $file_attachment['name'] = $iso_code . '.php';
            $file_attachment['mime'] = 'application/octet-stream';

            $id_lang = Configuration::get('PS_LANG_DEFAULT');

            $send = Mail::Send(
                $id_lang,
                'test',
                $this->module->l('he shared a translation with you', self::NAME_CLASS)
                    . ' - PS:' . _PS_VERSION_ . ' - MOD:' . $this->module->version,
                array(),
                'info@presteamshop.com',
                null,
                null,
                null,
                $file_attachment,
                null,
                _PS_MAIL_DIR_,
                null,
                $this->context->shop->id
            );

            if ($send) {
                return array(
                    'message_code' => $this->CODE_SUCCESS,
                    'message' => $this->module->l('Your translation has been sent, we will consider it for future upgrades of the module', self::NAME_CLASS),
                );
            }
        }

        return array(
            'message_code' => $this->CODE_ERROR,
            'message' => $this->module->l('An error has occurred to attempt send the translation', self::NAME_CLASS),
        );
    }

    public function saveTranslations($iso_code)
    {
        $data_translation = Tools::getValue('array_translation');
        $file_name = $iso_code . '.php';

        if (!file_exists($this->module->translations_path . $file_name)) {
            touch($this->module->translations_path . $file_name);
        }

        $file_path = realpath($this->module->translations_path . $file_name);

        if (is_writable($file_path)) {
            $line = '';

            $line .= '<?php' . "\n";
            $line .= 'global $_MODULE;' . "\n";
            $line .= '$_MODULE = array();' . "\n";

            foreach ($data_translation as $key => $value) {
                foreach ($value as $data) {
                    $data['key_translation'] = trim($data['key_translation']);
                    $data['value_translation'] = trim($data['value_translation']);

                    if (empty($data['value_translation'])) {
                        continue;
                    }

                    $line .= '$_MODULE[\'<{' . $this->module->name . '}prestashop>' . $key . '_';
                    $line .= $data['key_translation'] . '\']  = \'';
                    $line .= str_replace("'", "\'", $data['value_translation']) . '\';' . "\n";
                }
            }

            if (!file_put_contents($file_path, $line)) {
                return array(
                    'message_code' => $this->CODE_ERROR,
                    'message' => $this->module->l('An error has occurred while attempting to save the translations', self::NAME_CLASS),
                );
            }

            $path_file_template = dirname(__FILE__) . '/../../themes/' . _THEME_NAME_
                . '/modules/' . $this->module->name . '/translations/' . $iso_code . '.php';
            if (file_exists($path_file_template)) {
                unlink($path_file_template);
            }

            return array(
                'message_code' => $this->CODE_SUCCESS,
                'message' => $this->module->l('The translations have been successfully saved', self::NAME_CLASS),
            );
        }

        return array(
            'message_code' => $this->CODE_ERROR,
            'message' => $this->module->l('An error has occurred while attempting to save the translations', self::NAME_CLASS),
        );
    }

    public function translations($type = null)
    {
        $type = Tools::getValue('type', $type);
        $iso_code = Tools::getValue('iso_code');

        switch ($type) {
            case 'get':
                $result = $this->getTranslations($iso_code);
                break;
            case 'save':
                $result = $this->saveTranslations($iso_code);
                break;
            case 'share':
                $result = $this->shareTranslation($iso_code);
                break;
            case 'download':
                $result = $this->downloadFileTranslation($iso_code);
                break;
        }

        return $result;
    }
}
