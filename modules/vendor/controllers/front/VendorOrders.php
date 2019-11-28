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

class VendorVendorOrdersModuleFrontController extends ModuleFrontController
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
        
        if (empty($this->context->cookie->vendorObj)) {
            Tools::redirect('index.php?fc=module&module=restaurant&controller=VendorRegistration');
            die();
        }
		
		$cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
		
		//init
		$vendorCore = new ScVendor;
		
        if (Tools::getValue('token') && Tools::getValue('token') == 's') {
            $this->success_msg = 'block';
        }
        
        //here get all product for current store
        $orders = $this->getAllOrder($cookieObj['rid']);
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
            'orders'             => $orders,
            'welcome_name'       => $welcome_name,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('vendororderlist.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendororderlist.tpl');
    }
    private function getAllOrder($storeId)
    {
        $order_array = array();
        $sql = "SELECT *,o.date_add,o.reference,c.firstname,c.lastname,ca.name as carrier_name,count(ror.id_order_detail) as total_product from "._DB_PREFIX_."orders o inner join "._DB_PREFIX_."customer c on c.id_customer = o.id_customer inner join "._DB_PREFIX_."carrier ca on ca.id_carrier = o.id_carrier inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order = o.id_order where ror.`restaurant_id` = ".(int)$storeId." group by ror.id_order";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $result) {
                $order_array[] = array(
                    'order_id'      => $result['id_order'],
                    'reference'     => $result['reference'],
                    'name'          => $result['firstname'].' '.$result['lastname'],
                    'carrier_name'  => $result['carrier_name'],
                    'total_product' => $result['total_product'],
                    'order_date'    => $result['date_add']
                );
            }
        }
        return $order_array;
    }
    
    private function getAllStoreProducts($storeId)
    {
        $link = new Link();
        $products = array();
        $query='SELECT * FROM '._DB_PREFIX_.'product where restaurant_id='.(int)$storeId . ' and active = 1 order by id_product desc';
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $product = new Product($row['id_product'], false, $this->context->language->id);
                $category = new Category($row['id_category_default']);
                //$url = $link->getProductLink($product);
                $id_product = $row['id_product'];
                $image = Image::getCover($id_product);
                $imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));
                $products[] = array(
                    'id_product'    => $row['id_product'],
                    'name'          => $product->name,
                    'price'         => Tools::displayPrice($product->price),
                    'href'          => $link->getProductLink($product),
                    'stock'         => StockAvailable::getQuantityAvailableByProduct($row['id_product']),
                    'img'           => $this->getSiteProtocal().$imagePath,
                    'discount'      => $this->getDiscountPrice($row['id_product']),
                    'category_name' => $category->name[1]
                );
            }
        }
        return $products;
    }
    
    private function getDiscountPrice($product_id)
    {
        $discount = '0%';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'specific_price where id_product='.(int)$product_id);
        if (count($result) > 0 && (float)$result['reduction'] > 0) {
            $discount = (float)((float)$result['reduction'] * (int)100) . '%';
        }
        return $discount;
    }
}
