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

function upgrade_module_1_1_6($object)
{
    $object->deleteEmptyAddressesOPC();

    //-------------------------------
    // Pasa todas las direcciones a la tabla de opc_customer_address.
    // Si son mas los productos virtuales, se pasan las direcciones con tipo 'invoice', de lo contrario con 'delivery'
    $sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'product WHERE is_virtual = 1';
    $virtual_products = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

    $sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'product WHERE is_virtual = 0';
    $no_virtual_products = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

    $object_address = 'delivery';
    if ($virtual_products > $no_virtual_products) {
        $object_address = 'invoice';
    }

    $sql = 'INSERT INTO '._DB_PREFIX_.'opc_customer_address SELECT id_customer, id_address, \''.$object_address.'\' FROM '._DB_PREFIX_.'address WHERE active = 1 AND deleted = 0 AND id_customer != 0 AND id_address NOT IN (SELECT id_address FROM '._DB_PREFIX_.'opc_customer_address)';
    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
    //-------------------------------

    $object->registerHook('actionObjectAddressAddAfter');
    $object->registerHook('actionObjectAddressDeleteAfter');

    return true;
}
