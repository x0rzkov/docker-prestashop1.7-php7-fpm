<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2019 Presta.Site
 * @license   LICENSE.txt
 */

class PstStockBarLevel extends ObjectModel
{
    public $id_shop;
    public $max_qty;
    public $color;
    public $text;
    public $id_product;

    public static $definition = array(
        'table' => 'pststockbar_level',
        'primary' => 'id_pststockbar_level',
        'multilang' => true,
        'fields' => array(
            // Classic fields
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'max_qty' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'color' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            // Lang fields
            'text' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true),
        ),
    );

    public function validateAllFields()
    {
        $errors = array();

        $valid = $this->validateFields(false, true);
        if ($valid !== true) {
            $errors[] = $valid . "\n";
        }
        $valid_lang = $this->validateFieldsLang(false, true);
        if ($valid_lang !== true) {
            $errors[] = $valid_lang . "\n";
        }

        return $errors;
    }

    public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = true)
    {
        return parent::validateField($field, $value, $id_lang, $skip, $human_errors);
    }

    public static function getCollection($class, $id_lang)
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection($class, $id_lang);
        } else {
            $collection = new Collection($class, $id_lang);
        }

        return $collection;
    }

    public static function getLevelsBO($id_product = null)
    {
        $context = Context::getContext();

        $levels = self::getCollection('PstStockBarLevel', null);
        $levels->where('id_shop', '=', $context->shop->id);
        $levels->orderBy('max_qty', 'ASC');
        // get general level settings or per-product if necessary
        if ($id_product && self::getCountProductLevels($id_product, $context->shop->id)) {
            $levels->where('id_product', '=', $id_product);
        } else {
            $levels->where('id_product', '=', 0);
        }

        return $levels;
    }

    public static function getLevelsFO()
    {
        $context = Context::getContext();
        $levels = self::getCollection('PstStockBarLevel', null);
        $levels->where('id_shop', '=', $context->shop->id);
        $levels->orderBy('max_qty', 'ASC');

        return $levels;
    }

    public static function getIdLevelByQty($qty, $id_product = null)
    {
        $context = Context::getContext();
        if ($id_product) {
            $product_levels = self::getCountProductLevels($id_product, $context->shop->id);
            if (!$product_levels) {
                $id_product = null;
            }
        }

        $id_level = Db::getInstance()->getValue(
            'SELECT `id_pststockbar_level`
             FROM `'._DB_PREFIX_.'pststockbar_level`
             WHERE `max_qty` >= '.(int)$qty.'
             AND `id_shop` = '.(int)$context->shop->id.'
             '.($id_product ? ' AND `id_product` = '.(int)$id_product : ' AND `id_product` = 0 ').'
             ORDER BY `max_qty` ASC'
        );
        
        if (!$id_level) {
            // most likely qty is greater than the last range
            $id_level = Db::getInstance()->getValue(
                'SELECT `id_pststockbar_level`
                 FROM `'._DB_PREFIX_.'pststockbar_level`
                 WHERE  `id_shop` = '.(int)$context->shop->id.'
                 '.($id_product ? ' AND `id_product` = '.(int)$id_product : ' AND `id_product` = 0 ').'
                 ORDER BY `max_qty` DESC'
            );
        }

        return $id_level;
    }

    public static function getTextByQty($qty, $id_product = null)
    {
        $context = Context::getContext();
        $id_level = self::getIdLevelByQty($qty, $id_product);
        $level = new PstStockBarLevel($id_level, $context->language->id);

        if (Validate::isLoadedObject($level)) {
            return $level->text;
        }

        return '';
    }

    public static function getCountProductLevels($id_product, $id_shop)
    {
        $count = Db::getInstance()->getValue(
            'SELECT COUNT(`id_pststockbar_level`)
             FROM `'._DB_PREFIX_.'pststockbar_level`
             WHERE `id_product` = '.(int)$id_product.'
              AND `id_shop` = '.(int)$id_shop
        );

        return $count;
    }
}
