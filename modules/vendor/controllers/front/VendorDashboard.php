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

class VendorVendorDashboardModuleFrontController extends ModuleFrontController
{
    
    public $success_msg = 'none';
    
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
        if (Tools::getValue('method') != '' && Tools::getValue('method')=='logout') {
            $this->context->cookie->__unset('vendorObj');
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
            die();
        }
        
        $vendorCore = new ScVendor;
		
		$market_active = Configuration::get('PS_VENDOR_RES_DISABLE_MARKET');
        $market_msg = Configuration::get('PS_VENDOR_RES_DISABLE_MESSAGE');
        
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		//$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		/*$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];*/
		
		$rating = '';
        $rating_image = '';
        $total_rating = '';
        
        $slang = $this->context->language->id;
        $products = $this->getVendorTotalProduct($cookieObj['rid']);
        $total_sale = $this->getStoreTotalSale($cookieObj['rid']);
        //$page_view = $this->getVendorProductPageTotalView($cookieObj['rid']);
        $commission = $this->getVendorPercent($cookieObj['rid']);
        $vendor_total_amount = $this->getVendorTotalSale($cookieObj['rid']);
        $vendor_last_five_orders = $this->getLastFiveOrderInfo($cookieObj['rid']);
        $bar_chart_data = $this->getStoreMonthlySaleStatus($cookieObj['rid']);
		
		$this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'country_list'       => $vendorCore->getAllCountryList(),
            'msgx'               => $this->success_msg,
            'welcome_name'       => $welcome_name,
            'total_sale'         => $total_sale,
            'products'           => $products,
            'admin_commission'   => $commission,
            'total_amount'       => $vendor_total_amount,
            'five_orders'        => $vendor_last_five_orders,
            'bar_chart_data'     => $bar_chart_data,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'market_active'      => $market_active,
            'market_msg'         => html_entity_decode($market_msg),
            'xqty'               => $this->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('vendordashboard.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendordashboard.tpl');
    }
    // function prepare dashbaord
    private function getVendorTotalProduct($rid)
    {
        $result = Db::getInstance()->getRow('SELECT count(p.id_product) as total_product FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id='.(int)$rid . ' and p.active = 1');
        if (count($result) > 0 && $result['total_product'] > 0) {
            return $result['total_product'];
        }
        return 0;
    }
    private function getStoreTotalSale($storeId)
    {
        $sql = "SELECT count(o.id_order) as sale FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."customer c on c.id_customer = o.id_customer inner join "._DB_PREFIX_."carrier ca on ca.id_carrier = o.id_carrier inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id=".(int)$storeId." group by od.id_order order by od.id_order_detail";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            return count($results);
        }
        return 0;
    }
    
    private function getStoreMonthlySaleStatus($storeId)
    {
        $arr = array();
        $query = 'SELECT MONTHNAME(added_date) as month_name,SUM(vendor_amount) as total_amount FROM '._DB_PREFIX_.'vendor_payment_info where rid = '.(int)$storeId.' and YEAR(added_date) = "'.date('Y').'" GROUP BY YEAR(added_date), MONTH(added_date)';
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $arr[] = array(
                    'y' => $row['month_name'],
                    'a' => $row['total_amount'],
                    'b' => $row['total_amount']
                );
            }
        }
        return Tools::jsonEncode($arr);
    }
    private function getVendorTotalSale($storeId)
    {
        $amount = number_format(0, 2, '.', '');
        $result = Db::getInstance()->getRow('SELECT sum(product_price) as total_amount FROM '._DB_PREFIX_.'order_detail od inner join '._DB_PREFIX_.'restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id = '.(int)$storeId);
        if (count($result) > 0 && $result['total_amount'] != '') {
            $amount = number_format($result['total_amount'], 1, '.', '');
        }
        return $amount;
            
    }
    private function getVendorPercent($rid)
    {
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'restrurent_registration where rid='.(int)$rid);
        if (count($result) > 0 && $result['commission'] > 0) {
            return $result['commission'];
        }
        return 0;
    }
    private function getLastFiveOrder($storeId)
    {
        $order_array = array();
        $sql = "SELECT *,o.date_add FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id=".(int)$storeId.' order by id_order_detail desc limit 5';
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
    private function getLastFiveOrderInfo($storeId)
    {
        $order_array = array();
        $sql = "SELECT *,o.date_add,o.reference,c.firstname,c.lastname,ca.name as carrier_name,count(od.product_id) as total_product FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."customer c on c.id_customer = o.id_customer inner join "._DB_PREFIX_."carrier ca on ca.id_carrier = o.id_carrier inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id=".(int)$storeId.' group by od.id_order order by od.id_order_detail desc limit 5';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $result) {
                $order_array[] = array(
                    'order_id'      => $result['id_order'],
                    'reference'     => $result['reference'],
                    'name'          => $result['firstname'].' '.$result['lastname'],
                    'carrier_name'  => $result['carrier_name'],
                    'total_product' => $result['total_product'],
                    'order_date'    => $result['date_add'],
                );
            }
        }
        return $order_array;
    }
    public function lessQuantityAlert($storeId)
    {
        $qtyx = 0;
        $query = 'SELECT * FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product inner join '._DB_PREFIX_.'product_lang pl on pl.id_product = p.id_product where pvr.restaurant_id='.(int)$storeId;
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $result) {
                $qty = StockAvailable::getQuantityAvailableByProduct($result['id_product']);
                if ((int)$qty <= 5) {
                    $qtyx += 1;
                }
            }
        }
        if ($qtyx > 1) {
            return $qtyx.' '.'Products';
        } else {
            return $qtyx.' '.'Product';
        }
    }
}
