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

function upgrade_module_1_2_0($module)
{
    try {
        $queries = array(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pststockbar_level` (
                `id_pststockbar_level` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_shop` INT(11) UNSIGNED NOT NULL,
                `max_qty` INT(11) UNSIGNED NOT NULL,
                `color` VARCHAR(65),
                PRIMARY KEY (`id_pststockbar_level`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pststockbar_level_lang` (
                `id_pststockbar_level` INT(11) UNSIGNED NOT NULL,
                `id_lang` INT(11) UNSIGNED NOT NULL,
                `text` VARCHAR(255),
                UNIQUE (`id_pststockbar_level`, `id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',
        );
        foreach ($queries as $query) {
            Db::getInstance()->execute($query);
        }

        $module->initLevels();
        $module->registerHook('actionUpdateQuantity');
    } catch (Exception $e) {
        // ignore
    }
    
    return true; // Return true if success.
}
