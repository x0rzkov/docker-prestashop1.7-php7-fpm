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

class VendorVendorQuantityAlertListModuleFrontController extends ModuleFrontController
{
    
    private $limit = 5;
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        $this->display_column_left = false;
        
        $this->display_column_right = false;
        
        parent::initContent();
        
        if (empty($this->context->cookie->vendorObj)) {
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
            die();
        }
		
		//init
		$vendorCore = new ScVendor;
		
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];
        /*End*/
		
        $slang = $this->context->language->id;
        $products = array();
        $store_alert_products = $this->getStoreAlertProduct($cookieObj['rid']);
        foreach ($store_alert_products as $pro) {
            $qty = StockAvailable::getQuantityAvailableByProduct($pro['id_product']);
            if ((int)$qty <= (int)$this->limit) {
                $products[] = array(
                    'id_product'    => $pro['id_product'],
                    'name'          => $pro['name'],
                    'quantity'      => $qty,
                    'price'         => Tools::displayPrice($pro['price'])
                );
            }
        }
        $this->context->smarty->assign(array(
            'slang'                 => $slang,
            'ps_base_url'           => $ps_base_url,
            'products'              => $products,
            'xqty'                  => $vendorCore->lessQuantityAlert($cookieObj['rid']),
            'welcome_name'          => $welcome_name,
            'store_rating'          => $rating,
            'total_rating'          => $total_rating,
            'rating_image'          => $rating_image
        ));
        //$this->setTemplate('vendorquantityalertlist.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorquantityalertlist.tpl');
    }
    private function getPSBaseUrl()
    {
        $base_url = '';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');
        if (count($result) > 0) {
            $domian = $this->getSiteProtocal().$result['domain'];
            $physical_uri = $result['physical_uri'];
            $base_url = $domian . $physical_uri;
        }
        return $base_url;
    }
    public function getSiteProtocal()
    {
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return 'https://';
        } else {
            return 'http://';
        }
    }
    public function getStoreAlertProduct($storeId)
    {
        $query = 'SELECT * FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product inner join '._DB_PREFIX_.'product_lang pl on pl.id_product = p.id_product where pvr.restaurant_id='.(int)$storeId;
        $results = Db::getInstance()->ExecuteS($query);
        return $results;
    }
}
