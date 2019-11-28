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

function upgrade_module_4_0_0($object)
{
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'admin_recherche`');
    Db::getInstance()->execute(
        'RENAME TABLE `' . _DB_PREFIX_ . 'admin_status` TO `' . _DB_PREFIX_ . 'dmuadminrecherche_status`'
    );
    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'tab` SET class_name = \'AdminDmuAdminRecherche\'
     WHERE module = \'dmuadminrecherche\'');
    Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'dmuadminrecherche_status`
     CHANGE COLUMN `statut` `status` TINYINT(1) UNSIGNED NOT NULL AFTER `id_product`');

    Configuration::updateValue(
        Tools::strtoupper($object->name) . '_CONF',
        Configuration::get('DMUADMRECHERCHE_CONF', null, 0, 0),
        false,
        0,
        0
    );
    Configuration::deleteByName('DMUADMRECHERCHE_CONF');
    Configuration::deleteByName('DMUADMRECHERCHE_VERSION');

    $html_cookie = '<pre>' . print_r(Context::getContext()->cookie, true) . '</pre>';
    preg_match_all("`admin_rechercher_(.+)] =>`", $html_cookie, $matches);

    if (isset($matches[1])) {
        if ($matches[1]) {
            foreach ($matches[1] as $f) {
                $name = 'admin_rechercher_' . $f;
                unset(Context::getContext()->cookie->$name);
            }
        }
    }

    @unlink(_PS_MODULE_DIR_ . $object->name . '/admindmuadminrecherche.php');
    @unlink(_PS_MODULE_DIR_ . $object->name . '/fr.php');
    @unlink(_PS_MODULE_DIR_ . $object->name . '/en.php');

    return true;
}
