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

class FieldControl extends FieldClass
{
    public $options;
    public $id_control;
    public $name_control;
    public $error_message;
    public $help_message;
    public $classes;
    public $value;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        $this->classes       = $this->object.($this->required ? ' required' : '');
        $this->id_control    = (!empty($this->object) ? $this->object.'_' : '').$this->name;
        $this->name_control  = (!empty($this->object) ? $this->object.'_' : '').$this->name;
        $this->error_message = '';
        $this->help_message  = '';
        $this->options       = array();
        $this->value         = '';

        $context = Context::getContext();

        if ($this->name == 'id' && ($this->object == 'delivery' || $this->object == 'invoice')) {
            $this->options = array(
                'empty_option' => true
            );

            if (Validate::isLoadedObject($context->customer) && $context->customer->isLogged()) {
                $address_customer = $context->customer->getAddresses($id_lang);

                if (empty($address_customer)) {
                    $this->default_value = '';
                }

                $this->options = array(
                    'empty_option' => true,
                    'value'        => 'id_address',
                    'description'  => 'alias',
                    'data'         => $address_customer
                );
            }
        } elseif ($this->is_custom && ($this->type_control === 'radio' || $this->type_control === 'select')) {
            //search in options custom
            $data = FieldOptionClass::getOptionsByIdField($this->id, $id_lang);

            $this->options = array(
                'empty_option' => true,
                'value'        => 'value',
                'description'  => 'description',
                'data'         => $data
            );
        }
    }
}
