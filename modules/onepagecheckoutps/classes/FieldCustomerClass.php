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

class FieldCustomerClass extends ObjectModel
{
    public $id_field;
    public $id_customer;
    public $object;
    public $id_addres;
    public $id_option;
    public $value;
    public $date_upd;

    public static $definition = array(
        'table'          => 'opc_field_customer',
        'primary'        => 'id_field',
        'multilang'      => false,
        'multishop'      => false,
        'multilang_shop' => false,
        'fields'         => array(
            'id_customer'   => array('type' => self::TYPE_INT, 'required' => true, 'size' => 20),
            'object'        => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 20),
            'id_address'    => array('type' => self::TYPE_INT, 'required' => false, 'size' => 20),
            'id_option'     => array('type' => self::TYPE_INT, 'required' => false, 'size' => 20),
            'value'         => array('type' => self::TYPE_STRING, 'required' => false),
            'date_upd'      => array('type' => self::TYPE_DATE, 'validate' => 'isDate')
        )
    );

    public static function insertData($data)
    {
        return Db::getInstance()->insert(FieldCustomerClass::$definition['table'], $data);
    }

    public static function updateData($data, $where)
    {
        return Db::getInstance()->update(FieldCustomerClass::$definition['table'], $data, $where);
    }

    public static function getFieldValue($id_field, $id_customer = null, $id_address = null)
    {
        $sql = new DbQuery();
        $sql->select('value');
        $sql->from(FieldCustomerClass::$definition['table']);
        $sql->where('id_field = '.(int)$id_field);

        if (!is_null($id_customer)) {
            $sql->where('id_customer = '.(int)$id_customer);
        }

        if (!is_null($id_address)) {
            $sql->where('id_address = '.(int)$id_address);
        }

        return Db::getInstance()->getValue($sql);
    }

    public static function getData($id_field = null, $id_customer = null, $object = null, $id_address = null)
    {
        $sql = new DbQuery();
        $sql->select('cf.id_address, cf.id_field, cf.object, cf.value, f.name');
        $sql->from(FieldCustomerClass::$definition['table'], 'cf');
        $sql->innerJoin('opc_field', 'f', 'f.id_field = cf.id_field');

        if (!is_null($id_field) && $id_field > 0) {
            $sql->where('cf.id_field = '.(int)$id_field);
        }

        if (!is_null($id_customer) && $id_customer > 0) {
            $sql->where('cf.id_customer = '.(int)$id_customer);
        }

        if (!is_null($object)) {
            $sql->where('cf.object =  \''.pSQL($object).'\'');
        }

        if (!is_null($id_address) && $id_address > 0) {
            $sql->where('cf.id_address = '.(int)$id_address);
        }

        return Db::getInstance()->executeS($sql);
    }
}
