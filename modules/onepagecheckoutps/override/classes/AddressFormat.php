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

class AddressFormat extends AddressFormatCore
{
    public static function generateAddress(Address $address, $patternRules = array(), $newLine = "\r\n", $separator = ' ', $style = array())
    {
        $addressFields = AddressFormat::getOrderedAddressFields($address->id_country);

        $opc = Module::getInstanceByName('onepagecheckoutps');
        if (Validate::isLoadedObject($opc)) {
            if ($opc->active && $opc->core->isVisible() && $opc->core->checkModulePTS()) {
                $custom_fields = FieldCustomerClass::getData(null, $address->id_customer, null, $address->id);

                if ($custom_fields) {
                    foreach ($custom_fields as &$custom_field) {
                        $custom_field['name']   = str_replace('-', '_', $custom_field['name']);
                        $addressFields[]        = $custom_field['name'];
                        $address->{$custom_field['name']} = $custom_field['value'];
                    }
                }
            }
        }

        $addressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($address, $addressFields);
        
        $addressText = '';
        foreach ($addressFields as $line) {
            if (($patternsList = preg_split(self::_CLEANING_REGEX_, $line, -1, PREG_SPLIT_NO_EMPTY))) {
                $tmpText = '';
                foreach ($patternsList as $pattern) {
                    if ((!array_key_exists('avoid', $patternRules)) ||
                                (is_array($patternRules) && array_key_exists('avoid', $patternRules) && !in_array($pattern, $patternRules['avoid']))) {
                        $tmpText .= (isset($addressFormatedValues[$pattern]) && !empty($addressFormatedValues[$pattern])) ?
                                (((isset($style[$pattern])) ?
                                    (sprintf($style[$pattern], $addressFormatedValues[$pattern])) :
                                    $addressFormatedValues[$pattern]).$separator) : '';
                    }
                }
                $tmpText = trim($tmpText);
                $addressText .= (!empty($tmpText)) ? $tmpText.$newLine: '';
            }
        }

        $addressText = preg_replace('/'.preg_quote($newLine, '/').'$/i', '', $addressText);
        $addressText = rtrim($addressText, $separator);

        return $addressText;
    }
}
