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

function upgrade_module_1_1_3($object)
{
    $object = $object;
    
    $create_table = '
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'opc_customer_address` (
            `id_customer` int(10) NOT NULL,
            `id_address` int(10) NOT NULL,
            `object` varchar(10) NOT NULL,
            PRIMARY KEY (`id_customer`, `id_address`, `object`)
        )
        ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($create_table);

    return true;
}
