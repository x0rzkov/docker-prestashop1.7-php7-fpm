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

function upgrade_module_4_0_2($object)
{
    $object->unregisterHook('displayAdminOrder');

    $object->registerHook('actionObjectAddressUpdateAfter');
    $object->registerHook('actionObjectCustomerAddAfter');
    $object->registerHook('actionObjectCustomerUpdateAfter');
    $object->registerHook('actionAdminCustomersFormModifier');
    $object->registerHook('actionAdminAddressesFormModifier');

    $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'opc_field_customer` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `id_field` int(10) NOT NULL,
        `id_customer` int(10) NOT NULL,
        `object` varchar(9) NOT NULL,
        `id_address` int(10) NULL,
        `id_option` int(10) NULL,
        `value` varchar(255) NULL,
        `date_upd` DATETIME NOT NULL,
        PRIMARY KEY (`id`)
    )
    ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;';

    Db::getInstance()->execute($sql);

    $json_networks = Tools::jsonDecode(Configuration::get('OPC_SOCIAL_NETWORKS'));
    $json_networks->facebook->class_icon = 'facebook-official';
    foreach ($json_networks as $network) {
        $network->enable = 0;
        if (!empty($network->client_id) && !empty($network->client_secret)) {
            $network->enable = 1;
        }
    }
    Configuration::updateValue('OPC_SOCIAL_NETWORKS', Tools::jsonEncode($json_networks));

    Configuration::updateValue('OPC_REQUIRE_PP_BEFORE_BUY', false);

    Db::getInstance()->execute('ALTER TABLE '._DB_PREFIX_.'opc_payment ADD test_mode TINYINT(1)');
    Db::getInstance()->execute('ALTER TABLE '._DB_PREFIX_.'opc_payment ADD test_ip varchar(300)');
    
    return true;
}
