<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

function classLoader($class_name)
{
    $phpEx = '.'.Tools::substr(strrchr(__FILE__, '.'), 1);
    $class_name = trim(ucwords($class_name));

    if (Tools::file_exists_cache(dirname(__FILE__).'/classes/'.$class_name.$phpEx)) {
        include_once(dirname(__FILE__).'/classes/'.$class_name.$phpEx);
    }
}

spl_autoload_register(null, false);
spl_autoload_extensions('.php');
spl_autoload_register('classLoader');
