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

function upgrade_module_1_2_3($object)
{
    Configuration::updateValue('OPC_SHIPPING_COMPATIBILITY', false);
    Configuration::updateValue('OPC_REQUIRED_LOGIN_CUSTOMER', true);

    $json_networks = Configuration::get('OPC_SOCIAL_NETWORKS');
    $json_networks = Tools::jsonDecode($json_networks);

    $json_networks->biocryptology = array(
        'network'       => 'Biocryptology',
        'name_network'  => 'Biocryptology',
        'client_id'     => '',
        'client_secret' => '',
        'scope'         => 'openid+profile+email+address',
        'class_icon'    => 'biocryptology'
    );

    Configuration::updateValue('OPC_SOCIAL_NETWORKS', Tools::jsonEncode($json_networks));

    $object->registerHook('actionCustomerLogoutAfter');

    return true;
}
