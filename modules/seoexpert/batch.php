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
* -------------------------------------------------------------------
*
* Description :
*   Set for bypass error on config.php
*/

$_SERVER['REMOTE_ADDR'] = '';

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/seoexpert.php');

if (Tools::strtolower(php_sapi_name()) == 'cli') {
    $seo = new SeoExpert();

    $all_rules = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT SQL_BIG_RESULT msr.id_rule
	FROM `'._DB_PREFIX_.'module_seohelping_rules` msr
	LEFT JOIN `'._DB_PREFIX_.'module_seohelping_objects` mso ON (msr.id_rule = mso.id_rule)
	LEFT JOIN `'._DB_PREFIX_.'module_seohelping_patterns` msp ON (msr.id_rule = msp.id_rule)
	WHERE msr.type = "product"
	AND msr.active = 1
	AND (msp.field NOT LIKE "fb_%" AND msp.field NOT LIKE "tw_%")
	GROUP BY msr.id_rule');

    foreach ($all_rules as &$rules) {
        $seo->cronProcessGenerateRule((int)$rules['id_rule']);
    }
    unset($all_rules, $rules, $seo);
}
