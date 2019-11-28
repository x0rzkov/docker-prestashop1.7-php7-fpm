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

function upgrade_module_1_2_1()
{
    Configuration::updateValue('OPC_SUGGESTED_ADDRESS_GOOGLE', true);

    Configuration::updateValue('OPC_INSERT_ISO_CODE_IN_DELIV_DNI', 0);
    Configuration::updateValue('OPC_INSERT_ISO_CODE_IN_INVOI_DNI', 0);

    Configuration::updateValue('OPC_DEFAULT_CARRIER', '');

    Configuration::updateValue('OPC_REPLACE_AUTH_CONTROLLER', true);
    Configuration::updateValue('OPC_REPLACE_IDENTITY_CONTROLLER', true);
    Configuration::updateValue('OPC_REPLACE_ADDRESSES_CONTROLLER', true);

    Configuration::updateValue('OPC_REQUIRED_LOGIN_CUSTOMER_REGISTERED', true);

    $sql = 'UPDATE `'._DB_PREFIX_.'opc_field_shop` fs INNER JOIN `'._DB_PREFIX_.'opc_field` f ON fs.id_field = f.id_field SET fs.`group` = f.`object`';
    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
    
    return true;
}
