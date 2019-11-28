<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

include_once(_PS_MODULE_DIR_.'vendor/classes/ScVendor.php');

class VendorVendorProfileModuleFrontController extends ModuleFrontController
{
    private $success_msg = 'none';
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        parent::initContent();
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        if (empty($this->context->cookie->vendorObj)) {
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
            die();
        }
        
		//init
		$vendorCore = new ScVendor;
		
        $store_info = $vendorCore->getStoreInfo();
        $firstname = $store_info['firstname'];
        $lastname = $store_info['lastname'];
        $country_id = $store_info['country_id'];
        $state_id = $store_info['state_id'];
        $email = $store_info['email'];
        $password = $store_info['password'];
        $telephone = $store_info['telephone'];
        
        
        //////////////////////////////////SEND DATA TO SMARTY////////////////////////////////////
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];
        /*End*/
        $slang = $this->context->language->id;
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'welcome_name'       => $welcome_name,
            'firstname'          => $firstname,
            'lastname'           => $lastname,
            'email'              => $email,
            'password'           => $password,
            'telephone'          => $telephone,
            'country_id'         => $country_id,
            'state_id'           => $state_id,
            'country_list'       => $vendorCore->getAllCountryList(),
            'states'             => $vendorCore->getAllStateList($country_id),
            'msgx'               => $this->success_msg,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        
		$this->setTemplate('module:vendor/views/templates/front/vendorprofilesetup.tpl');
		//$this->setTemplate('vendorprofilesetup.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            $cookieObj = unserialize($this->context->cookie->vendorObj);
            $store_id = $cookieObj['rid'];
            if ($store_id != '' && (int)$store_id > 0) {
                $query = 'UPDATE '._DB_PREFIX_.'restrurent_registration SET firstname="'.pSQL(Tools::getValue('txtFirstName')).'",lastname="'.pSQL(Tools::getValue('txtLastName')).'",email="'.pSQL(Tools::getValue('txtEmail')).'", telephone="'.pSQL(Tools::getValue('txtTelephone')).'",country_id="'.(int)(Tools::getValue('ddlcountry')).'",state_id="'.(int)(Tools::getValue('ddlstate')).'",password="'.pSQL(Tools::getValue('txtPassword')).'" WHERE rid="'.(int)$store_id.'"';
                Db::getInstance()->Execute($query);
                $this->success_msg = 'block';
            }
        }
    }
}
