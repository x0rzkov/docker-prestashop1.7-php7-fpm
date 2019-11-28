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

function upgrade_module_4_0_3($object)
{
    Configuration::updateValue('OPC_REQUIRE_PP_BEFORE_BUY', 0);

    $object->registerHook('displayAdminOrder');

    return true;
}
