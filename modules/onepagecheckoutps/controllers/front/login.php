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

class OnePageCheckoutPSLoginModuleFrontController extends ModuleFrontController
{
    public $ssl                 = true;
    public $display_column_left = false;
    public $name                = 'login';

    public function initContent()
    {
        parent::initContent();

        $headers     = array();
        $return_data = array();
        $social_network = Tools::strtolower(Tools::getValue('sv'));

        $client = $this->module->getInstanceOauthClient(Tools::getValue('sv'));

        if (($success = $client->Initialize())) {
            if (($success = $client->Process())) {
                if (Tools::strlen($client->access_token)) {
                    switch ($client->server) {
                        case 'Facebook':
                            $success = $client->CallAPI(
                                'https://graph.facebook.com/v2.3/me?fields=email,first_name,last_name',
                                'GET',
                                array(),
                                array('FailOnAccessError' => true),
                                $return_data
                            );

                            $headers['facebook'] = array(
                                'firstname' => 'first_name',
                                'lastname'  => 'last_name',
                                'email'     => 'email'
                            );
                            break;
                        case 'Google':
                            $success = $client->CallAPI(
                                'https://www.googleapis.com/oauth2/v1/userinfo',
                                'GET',
                                array(),
                                array('FailOnAccessError' => true),
                                $return_data
                            );

                            $headers['google'] = array(
                                'firstname' => 'given_name',
                                'lastname'  => 'family_name',
                                'email'     => 'email'
                            );
                            break;
                        case 'Paypal':
                            $success = $client->CallAPI(
                                'https://api.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid',
                                'GET',
                                array(),
                                array('FailOnAccessError' => true),
                                $return_data
                            );

                            //fullname explode to create customer
                            if (isset($return_data->name)) {
                                $full_name = str_replace("  ", " ", $return_data->name);
                                $full_name = explode(" ", $full_name);
                                $return_data->first_name = $full_name[0];
                                $return_data->last_name  = $full_name[1];
                            } else {
                                echo 'Mark the field "full name" in personal information, in the PayPal application';
                                die();
                            }
                            //fullname explode to create customer

                            $headers['paypal'] = array(
                                'firstname' => 'first_name',
                                'lastname'  => 'last_name',
                                'email'     => 'email',
                                'birthday'  => 'birthday',
                                'address'   => array(
                                    'address1' => 'street_address',
                                    'city' => 'locality',
                                    'postcode' => 'postal_code'
                                )
                            );
                            break;
                        case 'Biocryptology':
                            $success = $client->CallAPI(
                                'https://is.biocryptology.com/V1/userinfo',
                                'GET',
                                array(),
                                array('FailOnAccessError' => true),
                                $return_data
                            );

                            if (!empty($return_data)) {
                                $return_data->id = $return_data->sub;

                                $headers['biocryptology'] = array(
                                    'firstname' => 'given_name',
                                    'lastname'  => 'family_name',
                                    'email'     => 'email'/*,
                                    'address'   => array(
                                        'address1' => 'street_address',
                                        'city' => 'locality',
                                        'postcode' => 'postal_code'
                                    )*/
                                );
                            }
                            break;
                    }
                }
            }

            $success = $client->Finalize($success);
        }

        if ($success && !empty($return_data) && property_exists($return_data, 'email')) {
            $delivery_address = null;
            $invoice_address  = null;
            $customer = new Customer();
            $customer->getByEmail($return_data->email);

            if (!Validate::isLoadedObject($customer)) {
                foreach ($headers[$social_network] as $field => $value) {
                    if ($field == 'address' && is_array($value)) {
                        $id_address = $this->module->createAddress('delivery');
                        $delivery_address = new Address($id_address);

                        foreach ($value as $field_da => $value_da) {
                            if (isset($return_data->address->{$value_da})) {
                                $field_value = $return_data->address->{$value_da};

                                if (!empty($field_value) && $field_value != "Doesn't apply") {
                                    $delivery_address->{$field_da} = $field_value;
                                }
                            }
                        }
                    } else {
                        if (property_exists($return_data, $value)) {
                            $customer->{$field} = $return_data->{$value};
                        }
                    }
                }

                $password = Tools::passwdGen();
                $customer->passwd = md5(pSQL(_COOKIE_KEY_.$password));
                $customer->newsletter = (int)FieldClass::getDefaultValue('customer', 'newsletter');

                $this->module->createCustomer($customer, $delivery_address, $invoice_address, $password, false);
            } else {
                if ($customer->active == 0) {
                    echo $this->module->l('It is not possible to login with your account. Please contact the store administrator for more details.', 'login');
                    die();
                }

                $this->module->singInCustomer($customer);
            }

            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert(
                'opc_social_network',
                array(
                    'id_shop' => (int)$this->context->shop->id,
                    'id_customer' => (int)$customer->id,
                    'code_network' => pSQL($return_data->id),
                    'network' => pSQL($social_network)
                )
            );
        } else {
            if (!array_key_exists('email', $return_data)) {
                echo 'Email not found.';
                die();
            }

            echo $client->error;
            die();
        }

        $redirect_url = $this->context->link->getPageLink('my-account');
        if ($this->context->cart->nbProducts()) {
            if ($this->module->config_vars['OPC_REDIRECT_DIRECTLY_TO_OPC']) {
                $redirect_url = $this->context->link->getPageLink(
                    'order',
                    true,
                    $this->context->language->id,
                    array('checkout' => '1')
                );
            } else {
                $redirect_url = $this->context->link->getPageLink('order');
            }
        } else {
            $address_customer = $this->context->customer->getAddresses($this->context->cookie->id_lang);
            if (empty($address_customer)) {
                $redirect_url = $this->context->link->getPageLink('addresses');
            }
        }

        $this->context->smarty->assign('redirect_url', $redirect_url);
        $this->setTemplate('module:onepagecheckoutps/views/templates/front/login.tpl');
    }
}
