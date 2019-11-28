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

class FieldClass extends ObjectModel
{
    public $id;
    public $name;
    public $object;
    public $description;
    public $label;
    public $type;
    public $size;
    public $type_control;
    public $default_value;
    public $group;
    public $row;
    public $col;
    public $required;
    public $is_custom = 0;
    public $active;
    public $capitalize;

    public static $definition = array(
        'table'          => 'opc_field',
        'primary'        => 'id_field',
        'multilang'      => true,
        'multishop'      => true,
        'multilang_shop' => true,
        'fields'         => array(
            'object'       => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 20),
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 50
            ),
            'type'         => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 20),
            'size'         => array('type' => self::TYPE_INT, 'required' => true),
            'type_control' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 20),
            'is_custom'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'capitalize'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            /* Shop fields */
            'default_value' => array('type' => self::TYPE_STRING, 'shop' => 'true', 'required' => false, 'size' => 255),
            'group'         => array('type' => self::TYPE_STRING, 'shop' => 'true', 'required' => true),
            'row'           => array('type' => self::TYPE_INT, 'shop' => 'true', 'required' => true),
            'col' => array('type' => self::TYPE_INT, 'shop' => 'true', 'required' => true),
            'required' => array(
                'type' => self::TYPE_BOOL,
                'shop' => 'true',
                'validate' => 'isBool',
                'required' => true
            ),
            'active' => array('type' => self::TYPE_BOOL, 'shop' => 'true', 'validate' => 'isBool', 'required' => true),
            /* Lang fields */
            'description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => false,
                'size' => 255
            ),
            'label' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'required' => false,
                'size' => 255
            )
        )
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        //create multishop assoc
        Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
        parent::__construct($id, $id_lang, $id_shop);
    }

    /**
     * Prepare fields for ObjectModel class (add, update)
     * All fields are verified (pSQL, intval...)
     *
     * @return array All object fields
     */
    public function getFields()
    {
        $this->validateFields();
        $fields = $this->formatFields(self::FORMAT_COMMON);

        // Ensure that we get something to insert
        if (!$fields && isset($this->id) && Validate::isUnsignedId($this->id)) {
            $fields[$this->def['primary']] = $this->id;
        }

        return $fields;
    }

    public static function getAllFields(
        $id_lang = null,
        $id_shop = null,
        $object = null,
        $required = null,
        $active = null,
        $name_fields = array(),
        $order_by = 'fs.group, fs.row, fs.col',
        $is_custom = false
    ) {
        if (is_null($id_shop)) {
            $id_shop = Context::getContext()->shop->id;
        }

        $order_by = 'fs.group, fs.row, fs.col';

        $str_name_fields = '';
        if (is_array($name_fields) && count($name_fields) > 0) {
            foreach ($name_fields as $field) {
                if (!empty($str_name_fields)) {
                    $str_name_fields .= ',';
                }
                $str_name_fields .= '"'.pSQL($field).'"';
            }
        }

        //get fields
        $query = new DbQuery();
        $query->select('f.'.self::$definition['primary']);
        $query->from(self::$definition['table'], 'f');
        $query->innerJoin('opc_field_shop', 'fs', 'f.'.self::$definition['primary'].' = fs.id_field AND fs.id_shop = '.(int)$id_shop);
        $query->where(!empty($object) ? 'f.object = "'.pSQL($object).'"' : '');
        $query->where(is_array($name_fields) && count($name_fields) ? 'f.name IN ('.$str_name_fields.')' : '');
        $query->where(!empty($required) ? 'fs.required = '.(int) $required : '');
        $query->where(!empty($active) ? 'fs.active = '.(int) $active : '');

        if ($is_custom) {
            $query->where('f.is_custom = 1');
//            $query->where('f.type_control in ("radio", "select")');
        }

        $query->orderBy($order_by);

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        $fields = array();

        if (is_array($result) && !empty($result)) {
            foreach ($result as $row) {
                $id_lang_tmp = $id_lang;

                if (!empty($id_lang)) {
                    $query = new DbQuery();
                    $query->from('opc_field_lang');
                    $query->where(self::$definition['primary'].' = '.(int)$row['id_field']);
                    $query->where('id_lang = '.(int)$id_lang);
                    $query->where('id_shop = '.(int)$id_shop);
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

                    if (!$result) {
                        //en caso de no encontrar el campo en el idioma solicitado,
                        //lo creamos si tenemos dicha traduccion.
                        $iso_code = Language::getIsoById($id_lang);
                        $sql_file = dirname(__FILE__).'/../translations/sql/'.$iso_code.'.sql';
                        $sql_langs = Tools::file_get_contents($sql_file);
                        if ($sql_langs) {
                            $sql_lang = str_replace('PREFIX_', _DB_PREFIX_, $sql_langs);
                            $sql_lang = str_replace('ID_LANG', $id_lang, $sql_lang);

                            $shops = Shop::getShopsCollection(false);
                            foreach ($shops as $shop) {
                                $sql_lang_shop = str_replace('ID_SHOP', $shop->id, $sql_lang);
                                $sql_lang_shop = preg_split("/;\s*[\r\n]+/", $sql_lang_shop);

                                foreach ($sql_lang_shop as $query_lang_shop) {
                                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(trim($query_lang_shop));
                                }
                            }
                        } else {
                            $id_lang_tmp = Configuration::get('PS_LANG_DEFAULT');
                        }
                    }
                }

                $fields[] = new FieldControl($row['id_field'], $id_lang_tmp, $id_shop);
            }
        }

        return $fields;
    }

    public static function getField($id_lang, $id_shop, $object, $name_field)
    {
        if (is_null($id_shop)) {
            $id_shop = Context::getContext()->shop->id;
        }

        $query = new DbQuery();
        $query->select('id_field');
        $query->from('opc_field');
        $query->where('object = "'.pSQL($object).'"');
        $query->where('name = "'.pSQL($name_field).'"');
        $id_field = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        return new FieldControl($id_field, $id_lang, $id_shop);
    }

    public static function getNameFields($object, $required, $active)
    {
        $tmp_fields = array();

        $query = new DbQuery();
        $query->select('name');
        $query->from('opc_field');

        if (!empty($object)) {
            $query->where('object = "'.pSQL($object).'"');
        }
        if (!empty($required)) {
            $query->where('required = '.(int)$required);
        }
        if (!empty($active)) {
            $query->where('active = '.(int)$active);
        }

        $fields = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        if (count($fields)) {
            foreach ($fields as $field) {
                $tmp_fields[] = $field['name'];
            }
        }

        return $tmp_fields;
    }

    public static function getCustomFields($id = null)
    {
        $query = new DbQuery();
        $query->select('id_field');
        $query->from('opc_field');
        $query->where('is_custom = 1');
        if ($id) {
            $query->where('id_field = '.(int)$id);
        }
        $result = Db::getInstance()->executeS($query);
        if ($result) {
            $id_lang       = Context::getContext()->cookie->id_lang;
            $custom_fields = array();
            foreach ($result as $id) {
                $field           = new FieldClass($id['id_field']);
                $field->options  = FieldOptionClass::getOptionsByIdField($id['id_field'], $id_lang);
                $custom_fields[] = $field;
            }

            return $custom_fields;
        }

        return $result;
    }

    public static function getDefaultValue($object, $name_field)
    {
        $context = Context::getContext();
        $id_address = null;
        $value_logged = null;

        $query = new DbQuery();
        $query->select('id_field');
        $query->from('opc_field');
        $query->where('object = "'.pSQL($object).'"');
        $query->where('name = "'.pSQL($name_field).'"');
        $id_field = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        $field = new FieldClass($id_field);

        //si el campo es desactivado y estas logueado, entonces devolvemos el valor que ya tenia el cliente logueado.
        if ($context->customer->isLogged() || $context->customer->isGuest()) {
            if (!$field->active && trim($field->default_value) === '') {
                if ($object == 'customer') {
                    $value_logged = $context->customer->{$name_field};
                } else {
                    if ($object == 'delivery') {
                        $id_address = $context->cart->id_address_delivery;
                    } else {
                        $id_address = $context->cart->id_address_invoice;
                    }

                    $address = new Address($id_address);

                    $value_logged = $address->{$name_field};
                }
            }
        }

        if (!empty($value_logged)) {
            return $value_logged;
        }

        if ($name_field == 'id_country' && Configuration::get('PS_GEOLOCATION_ENABLED')) {
            if (Context::getContext()->country->active) {
                return Context::getContext()->country->id;
            }
        }

        return $field->default_value;
    }

    /**
     * Get Las row by group
     */
    public static function getLastRowByGroup($group)
    {
        $query = new DbQuery();
        $query->select('MAX(`row`)')->from('opc_field_shop')->where('`group` = \''.pSQL($group).'\'');

        return Db::getInstance()->getValue($query);
    }
}
