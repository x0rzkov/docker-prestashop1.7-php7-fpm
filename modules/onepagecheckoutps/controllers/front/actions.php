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

class OnePageCheckoutPSActionsModuleFrontController extends ModuleFrontController
{
    public $name = 'actions';

    public function init()
    {
        parent::init();

        if (!Tools::isSubmit('token')
            || Tools::encrypt($this->module->name.'/index') != Tools::getValue('token')
            || !Module::isInstalled($this->module->name)
        ) {
            die('Bad token');
        }

        if (Tools::isSubmit('action')) {
            $action = Tools::getValue('action');

            if (method_exists($this->module, $action)) {
                define('_PTS_SHOW_ERRORS_', true);

                $data_type = 'json';
                if (Tools::isSubmit('dataType')) {
                    $data_type = Tools::getValue('dataType');
                }

                switch ($data_type) {
                    case 'html':
                        die($this->module->$action());
                    case 'json':
                        $response = Tools::jsonEncode($this->module->$action());
                        die($response);
                    default:
                        die('Invalid data type.');
                }
            } else {
                die('403 Forbidden');
            }
        } else {
            die('403 Forbidden');
        }
    }
}
