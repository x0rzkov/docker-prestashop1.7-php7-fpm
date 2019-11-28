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

class VendorVendorOrderDetailsModuleFrontController extends ModuleFrontController
{
    private $success_msg = 'none';
    private $order_id = 0;
    
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
        
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
		
		//init
		$vendorCore = new ScVendor;
		
		if (Tools::getValue('id_order') && Tools::getValue('id_order') != '' && (int)Tools::getValue('id_order') > 0) {
            $this->order_id = Tools::getValue('id_order');
        }
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
            'shipping'           => $this->getOrderShippingInfoByOrderId(Tools::getValue('id_order')),
            'invoice'            => $this->getOrderInvoiceInfoByOrderId(Tools::getValue('id_order')),
            'products'           => $this->currentOrderDetails(Tools::getValue('id_order'), $cookieObj['rid']),
            'ps_base_url'        => $ps_base_url,
            'order_id'           => $this->order_id,
            'welcome_name'       => $welcome_name,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('vendororderdetails.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendororderdetails.tpl');
    }
    private function currentOrderDetails($order_id, $store_id)
    {
        $vendorCore = new ScVendor;
		$link = new Link();
        $product_arr = array();
        $sql = "SELECT * FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail  where ror.id_order = ".(int)$order_id." AND ror.restaurant_id = ".(int)$store_id." order by od.id_order_detail asc";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $result) {
                $product = new Product($result['product_id'], false, $this->context->language->id);
                $image = Image::getCover($result['product_id']);
                $imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));
                $total = (float)((int)$result['product_quantity'] * (float)$result['product_price']);
                $url = $link->getProductLink($product);
                $product_arr[] = array(
                    'img'               => $vendorCore->getSiteProtocal().$imagePath,
                    'product_id'        => $result['product_id'],
                    'href'              => $url,
                    'name'              => $result['product_name'],
                    'product_price'     => Tools::displayPrice($result['product_price']),
                    'product_quantity'  => $result['product_quantity'],
                    'total'             => Tools::displayPrice($total)
                );
            }
        }
        return $product_arr;
    }
    //here ger current order shipping info by order id
    private function getOrderShippingInfoByOrderId($order_id)
    {
        $shipping_arr = array();
        $sql = "SELECT *,s.name as state_name,cl.name as country_name FROM "._DB_PREFIX_."address a inner join "._DB_PREFIX_."orders o on o.id_address_delivery = a.id_address inner join "._DB_PREFIX_."state s on s.id_state = a.id_state inner join "._DB_PREFIX_."country_lang cl on cl.id_country = a.id_country where o.id_order=".(int)$order_id;
        if ($result = Db::getInstance()->getRow($sql)) {
            $shipping_arr = $result;
        }
        return $shipping_arr;
    }
    private function getOrderInvoiceInfoByOrderId($order_id)
    {
        $invoice_arr = array();
        $sql = "SELECT *,s.name as state_name,cl.name as country_name FROM "._DB_PREFIX_."address a inner join "._DB_PREFIX_."orders o on o.id_address_invoice = a.id_address inner join "._DB_PREFIX_."state s on s.id_state = a.id_state inner join "._DB_PREFIX_."country_lang cl on cl.id_country = a.id_country where o.id_order=".(int)$order_id;
        if ($result = Db::getInstance()->getRow($sql)) {
            $invoice_arr = $result;
        }
        return $invoice_arr;
    }
    
    private function getAllOrder($storeId)
    {
        $order_array = array();
        $sql = "SELECT *,o.date_add FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order where restaurant_id=".(int)$storeId.' order by id_order_detail';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $result) {
                $order_array[] = array(
                    'order_id'      => $result['id_order'],
                    'product_name'  => $result['product_name'],
                    'product_price' => Tools::displayPrice($result['product_price']),
                    'product_qty'   => $result['product_quantity'],
                    'order_date'    => $result['date_add'],
                );
            }
        }
        return $order_array;
    }
}
