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

function upgrade_module_3_0_0($object)
{
    $object = $object;

    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'opc_social_network` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) NOT NULL,
    `id_customer` int(10) NOT NULL,
    `code_network` varchar(50) NOT NULL,
    `network` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
    )
    ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');

    return true;
}
