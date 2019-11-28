<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL DREAM ME UP
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL DREAM ME UP is strictly forbidden.
 *
 *   .--.
 *   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
 *   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
 *   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
 *        w w w . d r e a m - m e - u p . f r       '
 *
 * @author    Dream me up <prestashop@dream-me-up.fr>
 * @copyright 2007 - 2016 Dream me up
 * @license   All Rights Reserved
 */

class DmuAdminRecherche extends Module
{

    public static $default_values = array(
        'affichage_ttc' => 1
    );

    public function __construct()
    {
        $this->name = 'dmuadminrecherche';
        $this->tab = 'administration';
        $this->version = '4.1.3';
        $this->author = 'Dream me up';
        $this->module_key = '';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DMU Quick administration of Product');
        $this->description = $this->l('You can search, filter and edit your products quickly from back-office');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        // Nom de l'onglet
        $this->menu_parent = 'AdminCatalog';
        $this->menu_controller = 'AdminDmuAdminRecherche';
        $this->menu_name = 'Quick administration';
        $this->menu_name_fr = 'Administration rapide';

        // Onglets à afficher dans la configuration (mettre un tableau vide si pas de config)
        $this->config_tabs = array(
            'Configuration' => array(
                'name' => $this->l('Configuration'),
                'is_helper' => true
            )
        );
    }

    public function install()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dmuadminrecherche_status` (
				`id_product` INT(10) UNSIGNED NOT NULL,
	            `status` TINYINT(1) UNSIGNED NOT NULL,
                `comment` TEXT NOT NULL,
                PRIMARY KEY (`id_product`),
                INDEX `status` (`status`)
				) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql)) {
            $this->_errors[] = Db::getInstance()->getMsgError();
            return false;
        }

        if (!Configuration::updateValue('DMUADMINRECHERCHE_CONF', serialize(self::$default_values), false, 0, 0)) {
            return false;
        }

        $id_lang = Language::getIdByIso('en');
        if (!$id_lang) {
            $id_lang = $this->context->language->id;
        }
        $id_lang_fr = Language::getIdByIso('fr');
        $id_tab = Tab::getIdFromClassName($this->menu_parent);

        if (!$this->installModuleTab(
            $this->menu_controller,
            array($id_lang => $this->menu_name, $id_lang_fr => $this->menu_name_fr),
            $id_tab
        )) {
            return false;
        }

        return parent::install();
    }

    protected function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $tab = new Tab();

        $id_lang = Language::getIdByIso('en');
        if (!$id_lang) {
            $id_lang = $this->context->language->id;
        }
        $langues = Language::getLanguages(false);
        foreach ($langues as $langue) {
            if (!isset($tabName[$langue['id_lang']])) {
                $tabName[$langue['id_lang']] = $tabName[$id_lang];
            }
        }

        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;
        $id_tab = $tab->save();
        if (!$id_tab) {
            return false;
        }

        $position = (int)Db::getInstance()->getValue('
            SELECT `position`
            FROM `' . _DB_PREFIX_ . 'tab`
            WHERE `id_parent` = ' . (int)$idTabParent . '
            ORDER BY `position`');
        $tab->updatePosition(0, $position);

        return true;
    }

    public function uninstall()
    {
        Configuration::deleteByName('DMUADMINRECHERCHE_CONF');

        $this->uninstallModuleTab($this->menu_controller);

        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dmuadminrecherche_status`');

        return parent::uninstall();
    }

    protected function uninstallModuleTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();

            return true;
        }

        return false;
    }

    public function getContent()
    {
        $return = $this->postProcess();

        $this->context->smarty->assign(array(
                'config_tabs' => $this->config_tabs,
                'version_prestashop' => _PS_VERSION_,
                'version_module' => $this->version,
                'nom_module' => $this->displayName,
                'path_module' => '../modules/' . $this->name,
                'form_id' => (Tools::getValue('form_id') != '') ? Tools::getValue('form_id') : '',
                'path_documentation' => 'documentation_' . ($this->context->language->iso_code == 'fr' ?
                        $this->context->language->iso_code : 'en') . '.pdf',
                'content_html' => array(
                    // Il faut ajouter ici les différentes fonctions pour chaque onglet
                    'Configuration' => $this->getConfigurationForm(),
                ),
            ));

        return $return . $this->context->smarty->fetch(dirname(__FILE__) . '/views/templates/admin/configure.tpl');
    }

    /* Fonction à personnaliser en fonction des besoins en configuration */
    public function getConfigurationForm()
    {
        $config = unserialize(Configuration::get('DMUADMINRECHERCHE_CONF', null, 0, 0));
        if (!$config) {
            $config = self::$default_values;
        }

        $fields_form = array();
        $fields_form[0]['form'] = array();

        // Champs du formulaire
        $fields_form[0]['form']['legend'] = array(
            'title' => $this->l('Module configuration'),
            'icon' => 'icon-cogs'
        );

        $fields_form[0]['form']['input'] = array(
            array('type' => 'hidden', 'name' => 'form_id'),
            array(
                'type' => 'radio',
                'label' => $this->l('Combination price display'),
                'name' => 'affichage_ttc',
                'is_bool' => true,
                'desc' => $this->l('The one-click edition of the price will be based on this parameter'),
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Tax incl.')
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Tax excl.')
                    )
                ),
            )
        );

        $fields_form[0]['form']['submit'] = array(
            'title' => $this->l('Save'),
            'name' => 'submitConfig',
            'class' => 'btn btn-default pull-right'
        );

        // Module, token and currentIndex
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;
        $helper->toolbar_scroll = false;

        $helper->fields_value = $config;
        $helper->fields_value['form_id'] = 'Configuration';

        return $helper->generateForm($fields_form);
    }

    /*  Gestion des processus en post */
    public function postProcess()
    {
        if (Tools::isSubmit('submitConfig')) {
            $config = array(
                'affichage_ttc' => (int)Tools::getValue('affichage_ttc')
            );

            Configuration::updateValue('DMUADMINRECHERCHE_CONF', serialize($config), false, 0, 0);

            return $this->displayConfirmation($this->l('The configuration have been updated.'));
        }
    }
}
