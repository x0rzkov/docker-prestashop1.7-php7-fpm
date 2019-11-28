<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2019 Presta.Site
 * @license   LICENSE.txt
 */

include_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');

if (Tools::getValue('ajax') == 1 && Tools::getToken(false) == Tools::getValue('token')) {
    $success = 0;

    if (Tools::getValue('action') == 'renderHook') {
        $psb = Module::getInstanceByName('pststockbar');
        $params = array();
        die($psb->hookPstStockBar($params));
    }
}
