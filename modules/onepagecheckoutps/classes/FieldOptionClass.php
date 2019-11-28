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

class FieldOptionClass extends ObjectModel
{
    public $id;
    public $id_field;
    public $value;
    public $description;
   
    public static $definition = array(
        'table'          => 'opc_field_option',
        'primary'        => 'id_field_option',
        'multilang'      => true,
        'multilang_shop' => false,
        'fields'         => array(
            'id_field' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true, 'size' => 10),
            'value' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => false,
                'size' => 50
            ),
            /* Lang fields */
            'description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => false,
                'size' => 255
            )
        )
    );

    public static function getIdOptionByIdFieldAndValue($id_field, $value)
    {
        $query = new DbQuery();
        $query->select('id_field_option')->from('opc_field_option');
        $query->where('id_field='.(int)$id_field)->where('value = "'.pSQL($value).'"');

        return (int)Db::getInstance()->getValue($query);
    }

    public static function getOptionsByIdField($id_field, $id_lang = null)
    {
        $query = new DbQuery();

        $query->select('fo.id_field_option')->from('opc_field_option', 'fo')->where('fo.id_field = '.(int)$id_field);

        $id_options = Db::getInstance()->executeS($query);
        $options    = array();

        foreach ($id_options as $option) {
            $field_option = new FieldOptionClass($option['id_field_option'], $id_lang);

            $options[] = array(
                'id'          => $field_option->id,
                'value'       => $field_option->value,
                'description' => $field_option->description
            );
        }

        return $options;
    }
}
