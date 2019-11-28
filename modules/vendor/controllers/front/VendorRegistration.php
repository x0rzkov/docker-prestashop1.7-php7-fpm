<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class VendorVendorRegistrationModuleFrontController extends ModuleFrontController
{
    
    public $success_msg = 'none';
    
    public $msg = '';
    
    public $msg_class = 'success';
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        parent::initContent();
        
        if (!empty($this->context->cookie->vendorObj)) {
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorPanel');
            die();
        }
        $ps_base_url = $this->getPSBaseUrl();
        $slang = $this->context->language->id;
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'country_list'       => $this->getAllCountryList(),
            'msgx'               => $this->success_msg,
            'msgs'               => $this->msg,
            'msg_class'          => $this->msg_class
        ));
        //$this->setTemplate('vendorregistration.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorregistration.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            $this->success_msg = 'block';
            $firstname = Tools::getValue('customer_firstname');
            $lastname = Tools::getValue('customer_lastname');
            $email = Tools::getValue('email');
            $telephone = Tools::getValue('telephone');
            $country = Tools::getValue('country');
            $state = Tools::getValue('state');
            $passwd = Tools::getValue('passwd');
            if (!$this->checkEmailAddressAlreadyExist($email)) {
                Db::getInstance()->insert('restrurent_registration', array(
                    'firstname'     => pSQL($firstname),
                    'lastname'      => pSQL($lastname),
                    'email'         => pSQL($email),
                    'telephone'     => pSQL($telephone),
                    'country_id'    => (int)($country),
                    'state_id'      => (int)($state),
                    'password'      => pSQL($passwd)
                ));
                $this->msg = 'Created Restaurant successfully please wait for admin approve your account.';
            } else {
                $this->msg_class = 'danger';
                $this->msg = 'Your Email address already exist !!!';
            }
        } elseif (Tools::isSubmit('SubmitLogin')) {
            $this->success_msg = 'block';
            $email = Tools::getValue('email');
            $pass = Tools::getValue('passwd');
            if ($email != '' && $pass != '') {
                $login_info = (int)$this->checkLoginInfo($email, $pass);
				if ((int)$login_info == 1) {
					Tools::redirect('index.php?fc=module&module=vendor&controller=VendorPanel');
                } elseif ((int)$login_info == 3) {
                    $this->msg_class = 'danger';
                    $this->msg = 'Your account need to approve continue to dashboard';
                } elseif ((int)$login_info == 2) {
                    $this->msg_class = 'danger';
                    $this->msg = 'Your account is temporary suspended';
                } else {
                    $this->msg_class = 'danger';
                    $this->msg = 'Wrong Email address or Password';
                }
            } else {
                $this->msg_class = 'danger';
                $this->msg = 'Correct Email and Password required for login';
            }
        }
    }
    public function getAllCountryList()
    {
        $country = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'country_lang where id_lang='.(int)$this->context->language->id . ' order by name asc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $country[] = array(
                    'id_country'    => $row['id_country'],
                    'name'          => $row['name']
                );
            }
        }
        return $country;
    }
    private function checkEmailAddressAlreadyExist($email)
    {
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'restrurent_registration where email="'.pSQL($email).'"');
        if (count($result) > 0 && $result['email'] != '') {
            return true;
        }
        return false;
    }
    private function checkLoginInfo($email, $pass)
    {
        $this->context->cookie->__unset('vendorObj');
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'restrurent_registration where email="'.pSQL($email).'" and password="'.pSQL($pass).'"');
        if (count($result) > 0 && (int)$result['rid'] > 0) {
            if ((int)$result['approved']==0) {
                //account not approved
                return 3;
            } elseif ((int)$result['status']==0) {
                //account temporary stop
                return 2;
            } else {
				$loginObj = array(
					'rid'						=> $result['rid'],
					'firstname'					=> $result['firstname'],
					'lastname'					=> $result['lastname'],
					'email'						=> $result['email'],
					'telephone'					=> $result['telephone'],
					'country_id'				=> $result['country_id'],
					'state_id'					=> $result['state_id'],
					'password'					=> $result['password'],
					'store_name'				=> $result['store_name'],
					'store_grid_image'			=> $result['store_grid_image'],
					'store_banner_image'		=> $result['store_banner_image'],
					'address'					=> $result['address'],
					'zipcode'					=> $result['zipcode'],
					'facebook_link'				=> $result['facebook_link'],
					'twitter_link'				=> $result['twitter_link'],
					'google_plus_link'			=> $result['google_plus_link'],
					'store_email'				=> $result['store_email'],
					'paypal_email'				=> $result['paypal_email'],
					'approved'					=> $result['approved'],
					'commission'				=> $result['commission'],
					'status'					=> $result['status'],
					'created_date'				=> $result['created_date']
				);
				//$this->context->cookie->vendorObj = serialize($loginObj);
				$this->context->cookie->__unset('vendorObj');
				$this->context->cookie->__set('vendorObj', serialize($loginObj));
                return 1;
            }
        }
        return 0;
    }
    private function getPSBaseUrl()
    {
        $base_url = '';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');
        if (count($result) > 0) {
            $domian = $this->siteProtocol().$result['domain'];
            $physical_uri = $result['physical_uri'];
            $base_url = $domian . $physical_uri;
        }
        return $base_url;
    }
    public function siteProtocol()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return 'https://';
        } else {
            return 'http://';
        }
    }
}
