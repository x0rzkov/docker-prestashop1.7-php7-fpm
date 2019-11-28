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

function upgrade_module_1_1_4($object)
{
    $override = 'override/controllers/front/OrderController.php';
    if ($object->core->existOverride($override, '/KEY_'.$object->prefix_module.'/')) {
        $path_origin = _PS_ROOT_DIR_.'/'.$override;
        $path_destination = _PS_ROOT_DIR_.'/'.$override.'_BK-'.$object->prefix_module.'-PTS_'.date('Y-m-d');

        rename($path_origin, $path_destination);

        Tools::generateIndex();

        $object->addOverride('OrderController');
    }

    return true;
}
