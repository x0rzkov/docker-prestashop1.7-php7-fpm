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

function upgrade_module_1_1_2($object)
{
    //support module: m4gdpr - v1.1.2 - PrestaAddons
    if ($object->core->isModuleActive('m4gdpr')) {
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("INSERT INTO `"._DB_PREFIX_."m4_gdpr_location` (`id_location`, `name`, `file_name`, `selector`) VALUES (99, 'Module: One Page Checkout PS', 'checkout', 'onepagecheckoutps')");
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("INSERT INTO `"._DB_PREFIX_."m4_gdpr_location_consent` (`id_location_consent`, `id_location`, `id_consent`, `position`, `required`) VALUES (NULL, 99, 2, 0, 1), (NULL, 99, 1, 1, 1)");
    }

    return true;
}
