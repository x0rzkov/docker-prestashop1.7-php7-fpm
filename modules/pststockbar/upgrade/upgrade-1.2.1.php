<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2017 Presta.Site
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_1($module)
{
    try {
        $queries = array(
            'ALTER TABLE `' . _DB_PREFIX_ . 'pststockbar_level`
              ADD `id_product` INT(11) NOT NULL DEFAULT 0,
              ADD INDEX (`id_shop`, `id_product`);',
        );
        foreach ($queries as $query) {
            Db::getInstance()->execute($query);
        }
    } catch (Exception $e) {
        // ignore
    }
    
    return true; // Return true if success.
}
