<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

class SupercheckoutConfiguration
{
    public function processPostData($data = array())
    {
        $configuration = $data;

        //Customer Personal Information
        if (!isset($configuration['customer_personal']['id_gender']['guest']['require'])) {
            $configuration['customer_personal']['id_gender']['guest']['require'] = 0;
        }
        if (!isset($configuration['customer_personal']['id_gender']['guest']['display'])) {
            $configuration['customer_personal']['id_gender']['guest']['display'] = 0;
        }

        if (!isset($configuration['customer_personal']['id_gender']['logged']['require'])) {
            $configuration['customer_personal']['id_gender']['logged']['require'] = 0;
        }
        if (!isset($configuration['customer_personal']['id_gender']['logged']['display'])) {
            $configuration['customer_personal']['id_gender']['logged']['display'] = 0;
        }

        if (!isset($configuration['customer_personal']['dob']['guest']['require'])) {
            $configuration['customer_personal']['dob']['guest']['require'] = 0;
        }
        if (!isset($configuration['customer_personal']['dob']['guest']['display'])) {
            $configuration['customer_personal']['dob']['guest']['display'] = 0;
        }
        if (!isset($configuration['customer_personal']['dob']['logged']['require'])) {
            $configuration['customer_personal']['dob']['logged']['require'] = 0;
        }
        if (!isset($configuration['customer_personal']['dob']['logged']['display'])) {
            $configuration['customer_personal']['dob']['logged']['display'] = 0;
        }

        if (!isset($configuration['customer_subscription']['newsletter']['guest']['checked'])) {
            $configuration['customer_subscription']['newsletter']['guest']['checked'] = 0;
        }
        if (!isset($configuration['customer_subscription']['newsletter']['guest']['display'])) {
            $configuration['customer_subscription']['newsletter']['guest']['display'] = 0;
        }

        if (!isset($configuration['customer_subscription']['optin']['guest']['checked'])) {
            $configuration['customer_subscription']['optin']['guest']['checked'] = 0;
        }
        if (!isset($configuration['customer_subscription']['optin']['guest']['display'])) {
            $configuration['customer_subscription']['optin']['guest']['display'] = 0;
        }

        if (!isset($configuration['use_delivery_for_payment_add']['guest'])) {
            $configuration['use_delivery_for_payment_add']['guest'] = 0;
        }
        if (!isset($configuration['use_delivery_for_payment_add']['logged'])) {
            $configuration['use_delivery_for_payment_add']['logged'] = 0;
        }

        if (!isset($configuration['show_use_delivery_for_payment_add']['guest'])) {
            $configuration['show_use_delivery_for_payment_add']['guest'] = 0;
        }
        if (!isset($configuration['show_use_delivery_for_payment_add']['logged'])) {
            $configuration['show_use_delivery_for_payment_add']['logged'] = 0;
        }

        //Payment Address
        if (!isset($configuration['payment_address']['firstname']['guest']['require'])) {
            $configuration['payment_address']['firstname']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['firstname']['guest']['display'])) {
            $configuration['payment_address']['firstname']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['firstname']['logged']['require'])) {
            $configuration['payment_address']['firstname']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['firstname']['logged']['display'])) {
            $configuration['payment_address']['firstname']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['lastname']['guest']['require'])) {
            $configuration['payment_address']['lastname']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['lastname']['guest']['display'])) {
            $configuration['payment_address']['lastname']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['lastname']['logged']['require'])) {
            $configuration['payment_address']['lastname']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['lastname']['logged']['display'])) {
            $configuration['payment_address']['lastname']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['company']['guest']['require'])) {
            $configuration['payment_address']['company']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['company']['guest']['display'])) {
            $configuration['payment_address']['company']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['company']['logged']['require'])) {
            $configuration['payment_address']['company']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['company']['logged']['display'])) {
            $configuration['payment_address']['company']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['vat_number']['guest']['require'])) {
            $configuration['payment_address']['vat_number']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['vat_number']['guest']['display'])) {
            $configuration['payment_address']['vat_number']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['vat_number']['logged']['require'])) {
            $configuration['payment_address']['vat_number']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['vat_number']['logged']['display'])) {
            $configuration['payment_address']['vat_number']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['dni']['guest']['require'])) {
            $configuration['payment_address']['dni']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['dni']['guest']['display'])) {
            $configuration['payment_address']['dni']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['dni']['logged']['require'])) {
            $configuration['payment_address']['dni']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['dni']['logged']['display'])) {
            $configuration['payment_address']['dni']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['address1']['guest']['require'])) {
            $configuration['payment_address']['address1']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['guest']['display'])) {
            $configuration['payment_address']['address1']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['logged']['require'])) {
            $configuration['payment_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['logged']['display'])) {
            $configuration['payment_address']['address1']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['address1']['guest']['require'])) {
            $configuration['payment_address']['address1']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['guest']['display'])) {
            $configuration['payment_address']['address1']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['logged']['require'])) {
            $configuration['payment_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address1']['logged']['display'])) {
            $configuration['payment_address']['address1']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['address2']['guest']['require'])) {
            $configuration['payment_address']['address2']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address2']['guest']['display'])) {
            $configuration['payment_address']['address2']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['address2']['logged']['require'])) {
            $configuration['payment_address']['address2']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['address2']['logged']['display'])) {
            $configuration['payment_address']['address2']['logged']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['postcode']['guest']['require'])) {
            $configuration['payment_address']['postcode']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['postcode']['guest']['display'])) {
            $configuration['payment_address']['postcode']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['postcode']['logged']['require'])) {
            $configuration['payment_address']['postcode']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['postcode']['logged']['display'])) {
            $configuration['payment_address']['postcode']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['city']['guest']['require'])) {
            $configuration['payment_address']['city']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['city']['guest']['display'])) {
            $configuration['payment_address']['city']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['city']['logged']['require'])) {
            $configuration['payment_address']['city']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['city']['logged']['display'])) {
            $configuration['payment_address']['city']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['id_state']['guest']['require'])) {
            $configuration['payment_address']['id_state']['guest']['require'] = 0;
        }

        if (!isset($configuration['payment_address']['id_state']['guest']['display'])) {
            $configuration['payment_address']['id_state']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['id_state']['logged']['require'])) {
            $configuration['payment_address']['id_state']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['id_state']['logged']['display'])) {
            $configuration['payment_address']['id_state']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['id_country']['guest']['require'])) {
            $configuration['payment_address']['id_country']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['id_country']['guest']['display'])) {
            $configuration['payment_address']['id_country']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['id_country']['logged']['require'])) {
            $configuration['payment_address']['id_country']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['id_country']['logged']['display'])) {
            $configuration['payment_address']['id_country']['logged']['display'] = 0;
        }

        if (!isset($configuration['payment_address']['phone']['guest']['require'])) {
            $configuration['payment_address']['phone']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['phone']['guest']['display'])) {
            $configuration['payment_address']['phone']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['phone']['logged']['require'])) {
            $configuration['payment_address']['phone']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['phone']['logged']['display'])) {
            $configuration['payment_address']['phone']['logged']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['phone_mobile']['guest']['require'])) {
            $configuration['payment_address']['phone_mobile']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['phone_mobile']['guest']['display'])) {
            $configuration['payment_address']['phone_mobile']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['phone_mobile']['logged']['require'])) {
            $configuration['payment_address']['phone_mobile']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['phone_mobile']['logged']['display'])) {
            $configuration['payment_address']['phone_mobile']['logged']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['alias']['guest']['require'])) {
            $configuration['payment_address']['alias']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['alias']['guest']['display'])) {
            $configuration['payment_address']['alias']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['alias']['logged']['require'])) {
            $configuration['payment_address']['alias']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['alias']['logged']['display'])) {
            $configuration['payment_address']['alias']['logged']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['other']['guest']['require'])) {
            $configuration['payment_address']['other']['guest']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['other']['guest']['display'])) {
            $configuration['payment_address']['other']['guest']['display'] = 0;
        }
        if (!isset($configuration['payment_address']['other']['logged']['require'])) {
            $configuration['payment_address']['other']['logged']['require'] = 0;
        }
        if (!isset($configuration['payment_address']['other']['logged']['display'])) {
            $configuration['payment_address']['other']['logged']['display'] = 0;
        }

        //Shipping Address
        if (!isset($configuration['shipping_address']['firstname']['guest']['require'])) {
            $configuration['shipping_address']['firstname']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['firstname']['guest']['display'])) {
            $configuration['shipping_address']['firstname']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['firstname']['logged']['require'])) {
            $configuration['shipping_address']['firstname']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['firstname']['logged']['display'])) {
            $configuration['shipping_address']['firstname']['logged']['display'] = 0;
        }

        if (!isset($configuration['shipping_address']['lastname']['guest']['require'])) {
            $configuration['shipping_address']['lastname']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['lastname']['guest']['display'])) {
            $configuration['shipping_address']['lastname']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['lastname']['logged']['require'])) {
            $configuration['shipping_address']['lastname']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['lastname']['logged']['display'])) {
            $configuration['shipping_address']['lastname']['logged']['display'] = 0;
        }

        if (!isset($configuration['shipping_address']['company']['guest']['require'])) {
            $configuration['shipping_address']['company']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['company']['guest']['display'])) {
            $configuration['shipping_address']['company']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['company']['logged']['require'])) {
            $configuration['shipping_address']['company']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['company']['logged']['display'])) {
            $configuration['shipping_address']['company']['logged']['display'] = 0;
        }

        if (!isset($configuration['shipping_address']['vat_number']['guest']['require'])) {
            $configuration['shipping_address']['vat_number']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['vat_number']['guest']['display'])) {
            $configuration['shipping_address']['vat_number']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['vat_number']['logged']['require'])) {
            $configuration['shipping_address']['vat_number']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['vat_number']['logged']['display'])) {
            $configuration['shipping_address']['vat_number']['logged']['display'] = 0;
        }

        if (!isset($configuration['shipping_address']['dni']['guest']['require'])) {
            $configuration['shipping_address']['dni']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['dni']['guest']['display'])) {
            $configuration['shipping_address']['dni']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['dni']['logged']['require'])) {
            $configuration['shipping_address']['dni']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['dni']['logged']['display'])) {
            $configuration['shipping_address']['dni']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['guest']['require'])) {
            $configuration['shipping_address']['address1']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['guest']['display'])) {
            $configuration['shipping_address']['address1']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['logged']['require'])) {
            $configuration['shipping_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['logged']['display'])) {
            $configuration['shipping_address']['address1']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['guest']['require'])) {
            $configuration['shipping_address']['address1']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['guest']['display'])) {
            $configuration['shipping_address']['address1']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['logged']['require'])) {
            $configuration['shipping_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address1']['logged']['display'])) {
            $configuration['shipping_address']['address1']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address2']['guest']['require'])) {
            $configuration['shipping_address']['address2']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address2']['guest']['display'])) {
            $configuration['shipping_address']['address2']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['address2']['logged']['require'])) {
            $configuration['shipping_address']['address2']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['address2']['logged']['display'])) {
            $configuration['shipping_address']['address2']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['postcode']['guest']['require'])) {
            $configuration['shipping_address']['postcode']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['postcode']['guest']['display'])) {
            $configuration['shipping_address']['postcode']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['postcode']['logged']['require'])) {
            $configuration['shipping_address']['postcode']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['postcode']['logged']['display'])) {
            $configuration['shipping_address']['postcode']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['city']['guest']['require'])) {
            $configuration['shipping_address']['city']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['city']['guest']['display'])) {
            $configuration['shipping_address']['city']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['city']['logged']['require'])) {
            $configuration['shipping_address']['city']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['city']['logged']['display'])) {
            $configuration['shipping_address']['city']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_state']['guest']['require'])) {
            $configuration['shipping_address']['id_state']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_state']['guest']['display'])) {
            $configuration['shipping_address']['id_state']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_state']['logged']['require'])) {
            $configuration['shipping_address']['id_state']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_state']['logged']['display'])) {
            $configuration['shipping_address']['id_state']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_country']['guest']['require'])) {
            $configuration['shipping_address']['id_country']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_country']['guest']['display'])) {
            $configuration['shipping_address']['id_country']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_country']['logged']['require'])) {
            $configuration['shipping_address']['id_country']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['id_country']['logged']['display'])) {
            $configuration['shipping_address']['id_country']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone']['guest']['require'])) {
            $configuration['shipping_address']['phone']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone']['guest']['display'])) {
            $configuration['shipping_address']['phone']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone']['logged']['require'])) {
            $configuration['shipping_address']['phone']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone']['logged']['display'])) {
            $configuration['shipping_address']['phone']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone_mobile']['guest']['require'])) {
            $configuration['shipping_address']['phone_mobile']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone_mobile']['guest']['display'])) {
            $configuration['shipping_address']['phone_mobile']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone_mobile']['logged']['require'])) {
            $configuration['shipping_address']['phone_mobile']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['phone_mobile']['logged']['display'])) {
            $configuration['shipping_address']['phone_mobile']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['alias']['guest']['require'])) {
            $configuration['shipping_address']['alias']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['alias']['guest']['display'])) {
            $configuration['shipping_address']['alias']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['alias']['logged']['require'])) {
            $configuration['shipping_address']['alias']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['alias']['logged']['display'])) {
            $configuration['shipping_address']['alias']['logged']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['other']['guest']['require'])) {
            $configuration['shipping_address']['other']['guest']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['other']['guest']['display'])) {
            $configuration['shipping_address']['other']['guest']['display'] = 0;
        }
        if (!isset($configuration['shipping_address']['other']['logged']['require'])) {
            $configuration['shipping_address']['other']['logged']['require'] = 0;
        }
        if (!isset($configuration['shipping_address']['other']['logged']['display'])) {
            $configuration['shipping_address']['other']['logged']['display'] = 0;
        }
        //Cart
        if (!isset($configuration['cart_options']['product_image']['guest']['display'])) {
            $configuration['cart_options']['product_image']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_image']['logged']['display'])) {
            $configuration['cart_options']['product_image']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_name']['guest']['display'])) {
            $configuration['cart_options']['product_name']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_name']['logged']['display'])) {
            $configuration['cart_options']['product_name']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_availability']['guest']['display'])) {
            $configuration['cart_options']['product_availability']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_availability']['logged']['display'])) {
            $configuration['cart_options']['product_availability']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_model']['guest']['display'])) {
            $configuration['cart_options']['product_model']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_model']['logged']['display'])) {
            $configuration['cart_options']['product_model']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_qty']['guest']['display'])) {
            $configuration['cart_options']['product_qty']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_qty']['logged']['display'])) {
            $configuration['cart_options']['product_qty']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_price']['guest']['display'])) {
            $configuration['cart_options']['product_price']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_price']['logged']['display'])) {
            $configuration['cart_options']['product_price']['logged']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_total']['guest']['display'])) {
            $configuration['cart_options']['product_total']['guest']['display'] = 0;
        }
        if (!isset($configuration['cart_options']['product_total']['logged']['display'])) {
            $configuration['cart_options']['product_total']['logged']['display'] = 0;
        }
        //Order Total
        if (!isset($configuration['order_total_option']['product_sub_total']['guest']['display'])) {
            $configuration['order_total_option']['product_sub_total']['guest']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['product_sub_total']['logged']['display'])) {
            $configuration['order_total_option']['product_sub_total']['logged']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['voucher']['guest']['display'])) {
            $configuration['order_total_option']['voucher']['guest']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['voucher']['logged']['display'])) {
            $configuration['order_total_option']['voucher']['logged']['display'] = 0;
        }
        // changes by rishabh jain
        if (!isset($configuration['order_total_option']['total_tax']['guest']['display'])) {
            $configuration['order_total_option']['total_tax']['guest']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['total_tax']['logged']['display'])) {
            $configuration['order_total_option']['total_tax']['logged']['display'] = 0;
        }
        // changes over
        if (!isset($configuration['order_total_option']['shipping_price']['guest']['display'])) {
            $configuration['order_total_option']['shipping_price']['guest']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['shipping_price']['logged']['display'])) {
            $configuration['order_total_option']['shipping_price']['logged']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['total']['guest']['display'])) {
            $configuration['order_total_option']['total']['guest']['display'] = 0;
        }
        if (!isset($configuration['order_total_option']['total']['logged']['display'])) {
            $configuration['order_total_option']['total']['logged']['display'] = 0;
        }

        //Confirm
        if (!isset($configuration['confirm']['term_condition']['guest']['display'])) {
            $configuration['confirm']['term_condition']['guest']['display'] = 0;
        }
        if (!isset($configuration['confirm']['term_condition']['logged']['display'])) {
            $configuration['confirm']['term_condition']['logged']['display'] = 0;
        }
        if (!isset($configuration['confirm']['term_condition']['guest']['checked'])) {
            $configuration['confirm']['term_condition']['guest']['checked'] = 0;
        }
        if (!isset($configuration['confirm']['term_condition']['logged']['checked'])) {
            $configuration['confirm']['term_condition']['logged']['checked'] = 0;
        }
        if (!isset($configuration['confirm']['term_condition']['guest']['require'])) {
            $configuration['confirm']['term_condition']['guest']['require'] = 0;
        }
        if (!isset($configuration['confirm']['term_condition']['logged']['require'])) {
            $configuration['confirm']['term_condition']['logged']['require'] = 0;
        }
        if (!isset($configuration['confirm']['order_comment_box']['guest']['display'])) {
            $configuration['confirm']['order_comment_box']['guest']['display'] = 0;
        }
        if (!isset($configuration['confirm']['order_comment_box']['logged']['display'])) {
            $configuration['confirm']['order_comment_box']['logged']['display'] = 0;
        }
        // changes by rishabh jain
        if (!isset($configuration['confirm']['gift_message']['guest']['display'])) {
            $configuration['confirm']['gift_message']['guest']['display'] = 0;
        }
        if (!isset($configuration['confirm']['gift_message']['logged']['display'])) {
            $configuration['confirm']['gift_message']['logged']['display'] = 0;
        }
        // changes over
        //Encode Html entities
        $configuration['html_value']['header'] = htmlentities($configuration['html_value']['header']);
        $configuration['html_value']['footer'] = htmlentities($configuration['html_value']['footer']);

        foreach ($configuration['design']['html'] as $key => $value) {
            $tmp = $value;
            $configuration['design']['html'][$key]['value'] = htmlentities(
                $configuration['design']['html'][$key]['value']
            );
            unset($tmp);
        }

        return $configuration;
    }
}
