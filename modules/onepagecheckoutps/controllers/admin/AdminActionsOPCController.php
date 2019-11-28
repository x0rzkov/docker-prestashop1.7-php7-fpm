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

class AdminActionsOPCController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!Validate::isLoadedObject($this->context->employee) || !$this->context->employee->isLoggedBack()) {
            die('You do not have permission to make this request');
        }
        
        $module_name = 'onepagecheckoutps';

        if (!Tools::isSubmit('token')
            || Tools::encrypt($module_name.'/index') != Tools::getValue('token')
            || !Module::isInstalled($module_name)
        ) {
            $params = array(
                'token' => Tools::getAdminTokenLite('AdminModules'),
                'configure' => $module_name
            );
            $url = Dispatcher::getInstance()->createUrl('AdminModules', $this->context->language->id, $params);

            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('action')) {
            $action = Tools::getValue('action');
            $module = Module::getInstanceByName($module_name);
            $existMethod = false;
            $existMethodCore = false;

            if (!method_exists($module, $action)) {
                if (method_exists($module->core, $action)) {
                    $existMethodCore = true;
                }
            } else {
                $existMethod = true;
            }

            if ($existMethod || $existMethodCore) {
                define('_PTS_SHOW_ERRORS_', true);

                $data_type = 'json';
                if (Tools::isSubmit('dataType')) {
                    $data_type = Tools::getValue('dataType');
                }

                switch ($data_type) {
                    case 'html':
                        if ($existMethodCore) {
                            die($module->core->$action());
                        }

                        die($module->$action());
                    case 'json':
                        if ($existMethodCore) {
                            $response = Tools::jsonEncode($module->core->$action());
                        } else {
                            $response = Tools::jsonEncode($module->$action());
                        }

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
