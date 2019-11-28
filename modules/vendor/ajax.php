<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle
*  @copyright 2007-2015 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'../../../classes/Context.php');
require_once(dirname(__FILE__).'../../../config/settings.inc.php');
$method = Tools::getValue('method');
$result = array();
if ($method == 'deleteres') {
     $return = array(
        'hasError' => true,
        'error'    => 'direct access not allow',
        'data'     => '-1'
    );
    if (Tools::getValue('rand') != '') {
        Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "restrurent_registration WHERE rid = ".Tools::getValue('rid')."");
        $return = array(
            'hasError' => false,
            'error'    => 'success',
            'data'     => '-1'
        );
        die(Tools::jsonEncode($result));
    }
} elseif ($method == 'resapprove') {
     $return = array(
        'hasError' => true,
        'error'    => 'direct access not allow',
        'data'     => '-1'
    );
    if (Tools::getValue('rand') != '') {
        Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "restrurent_registration set approved = 1 WHERE rid = ".Tools::getValue('rid')."");
        $return = array(
            'hasError' => false,
            'error'    => 'success',
            'data'     => '-1'
        );
        die(Tools::jsonEncode($result));
    }
} elseif ($method == 'proapprove') {
	$config = new Configuration();
	$search = new Search();
	$return = array(
        'hasError' => true,
        'error'    => 'direct access not allow',
        'data'     => '-1'
    );
    if (Tools::getValue('rand') != '') {
		Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "product set active = 1 WHERE id_product = ".Tools::getValue('pid')."");
        Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "product_shop set active = 1 WHERE id_product = ".Tools::getValue('pid')."");
		if ($config->get('PS_SEARCH_INDEXATION')) {
        	$search->indexation(false, Tools::getValue('pid'));
        }
        $return = array(
            'hasError' => false,
            'error'    => 'success',
            'data'     => '-1'
        );
    }
	die(Tools::jsonEncode($return));
} elseif ($method == 'storeStatus') {
     $return = array(
        'hasError' => true,
        'error'    => 'direct access not allow',
        'data'     => '-1'
    );
    if (Tools::getValue('rand') != '') {
        Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "restrurent_registration set status = ".Tools::getValue('status')." WHERE rid = ".Tools::getValue('rid')."");
        $return = array(
            'hasError' => false,
            'error'    => 'success',
            'data'     => '-1'
        );
        die(Tools::jsonEncode($result));
    }
} elseif ($method == 'loadStateByCountry') {
     $return = array(
        'hasError' => true,
        'error'    => 'direct access not allow',
        'data'     => '-1'
    );
    if (Tools::getValue('country_id') != '') {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'state WHERE id_country = '.(int)Tools::getValue('country_id') . ' order by name ASC';
        $results = Db::getInstance()->ExecuteS($sql);
        $return = array(
            'hasError' => false,
            'error'    => 'success',
            'data'     => $results
        );
        die(Tools::jsonEncode($return));
    }
} elseif ($method == 'attributeValue') {
	$context = Context::getContext();
	$attr_groups_values = AttributeGroup::getAttributes($context->language->id, Tools::getValue('agid'));
	sort_array_of_array($attr_groups_values, 'name');
	header('Content-Type: application/json');
	die(Tools::jsonEncode($attr_groups_values));
} elseif ($method == 'getComInformation') {
	 
} elseif ($method == 'customVariation') {
    $result = createCustomVariation(Tools::getValue('did'), Tools::getValue('id_product'), Tools::getValue('lang_id'), Tools::getValue('custom_price'));
    //add custom info
    if (Tools::getValue('options')) {
        $ipa = $result['data'];
        foreach (Tools::getValue('options') as $key => $value) {
            $group_text = '';
            $option_text = '';
            $group_info = getOptionTextById($key, 'group');
            if ($group_info != '') {
                $x = explode('~', $group_info);
                $group_text = trim($x[0]);
                if (trim($x[1]) == 'Textbox') {
                    $option_text = $value[0];
                } else {
                    foreach ($value as $val) {
                        $option_text .= getOptionTextById($val, '') . ',';
                    }
                }
            }
            $option_text = trim($option_text, ',');
            Db::getInstance()->insert('product_cart_custom_variation', array(
                'token'                 => pSQL(Tools::getValue('did')),
                'id_cart'               => (int)$ipa,
                'id_product'            => (int)Tools::getValue('id_product'),
                'option_group_name'     => pSQL($group_text),
                'option_value_name'     => pSQL($option_text),
                'group_id'              => (int)$key
            ));
        }
    }
    die(Tools::jsonEncode($result));
} else {
	echo 'Invalid Request';
	exit();
}

function createCustomVariation($design_id, $id_product, $id_lang, $custom_price)
{
    $attribute_group_id = 0;
    $attribute_id = 0;
    $paId = 0;
    $design_id = trim($design_id);
    $custom_design_price = 0.00;
    if ($custom_price != '' && (float)$custom_price > 0) {
        $custom_design_price = $custom_price;
    }
    if ($design_id != '') {
        $Query1 = "select * from " ._DB_PREFIX_. "attribute_group_lang
        where name = 'custom' and public_name = 'custom'";
        if ($result1 = Db::getInstance()->getRow($Query1)) {
            $attribute_group_id  = $result1['id_attribute_group'];
        } else {
            Db::getInstance()->insert('attribute_group', array(
                'is_color_group'    => (int)(0),
                'position'          => (int)(100)
            ));
            $attribute_group_id = Db::getInstance()->Insert_ID();
            Db::getInstance()->insert('attribute_group_lang', array(
                'id_attribute_group'    => (int)$attribute_group_id,
                'id_lang'               => (int)$id_lang,
                'name'                  => pSQL('custom'),
                'public_name'           => pSQL('custom')
            ));
        }
        if ($attribute_group_id > 0) {
            Db::getInstance()->insert('attribute', array(
                'id_attribute_group'    => (int)$attribute_group_id,
                'position'              => (int)(100)
            ));
            $attribute_id = Db::getInstance()->Insert_ID();
            if ($attribute_id > 0) {
                Db::getInstance()->insert('attribute_lang', array(
                    'id_attribute'      => (int)$attribute_id,
                    'id_lang'           => (int)$id_lang,
                    'name'              => pSQL($design_id)
                ));
            }
        }
        if ($attribute_id > 0) {
            Db::getInstance()->insert('product_attribute', array(
                'id_product'            => (int)$id_product,
                'wholesale_price'       => (float)(5.23),
                'price'                 => (float)(5.23),
                'quantity'              => (int)(100000)
            ));
            $paId = Db::getInstance()->Insert_ID();
        }
        if ($paId > 0) {
            Db::getInstance()->insert('product_attribute_combination', array(
                'id_attribute'              => (int)$attribute_id,
                'id_product_attribute'      => (int)$paId
            ));
            Db::getInstance()->insert('stock_available', array(
                'id_product'                => (int)$id_product,
                'id_product_attribute'      => (int)$paId,
                'id_shop'                   => (int)(1),
                'id_shop_group'             => (int)(0),
                'quantity'                  => (int)(10000),
                'depends_on_stock'          => (int)(0),
                'out_of_stock'              => (int)(2)
            ));
            Db::getInstance()->insert('product_attribute_shop', array(
                'id_product'                => (int)$id_product,
                'id_product_attribute'      => (int)$paId,
                'id_shop'                   => (int)(1),
                'price'                     => (float)$custom_design_price
            ));
        }
    }
    $return = array(
        'hasError' => false,
        'error'    => '',
        'data'     => $paId
    );
    return $return;
}

function sort_array_of_array(&$array, $subfield)
{
    $sortarray = array();
    foreach ($array as $key => $row)
    {
        $sortarray[$key] = $row[$subfield];
    }

    array_multisort($sortarray, SORT_ASC, $array);
}

function getOptionTextById($id, $type)
{
    if ($type == 'group') {
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'custom_variation_group WHERE cvgid='.$id);
        if (count($result) > 0 && !empty($result)) {
            return $result['group_name'].'~'.$result['attubute_type'];
        } else {
            return '';
        }
    } else {
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'custom_variation_value WHERE vid='.(int)$id);
        if (count($result) > 0 && !empty($result)) {
            return $result['variation_name'];
        } else {
            return '';
        }
    }
}

exit;
