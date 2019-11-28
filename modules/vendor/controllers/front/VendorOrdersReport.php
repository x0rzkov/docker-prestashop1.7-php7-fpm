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

class VendorVendorOrdersReportModuleFrontController extends ModuleFrontController
{
    private $success_msg = 'none';
    
    private $orders = array();
    
    private $xdate = '';
    
    private $orderId = '';
    
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
		
        if (Tools::getValue('token') && Tools::getValue('token') == 's') {
            $this->success_msg = 'block';
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
            'ps_base_url'        => $ps_base_url,
            'orders'             => $this->orders,
            'orderId'            => $this->orderId,
            'xdate'              => $this->xdate,
            'welcome_name'       => $welcome_name,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
		
        //$this->setTemplate('reports/rptvendororder.tpl');
		$this->setTemplate('module:vendor/views/templates/front/reports/rptvendororder.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('submitCreate')) {
            if (Tools::getValue("txtOrderId") != '') {
                $this->orderId = Tools::getValue("txtOrderId");
                $this->orders = $this->getOrderDetailsByOrderId(Tools::getValue("txtOrderId"));
            } elseif (Tools::getValue("txtOrderdate") != '') {
                $this->xdate = Tools::getValue("txtOrderdate");
                $str = explode('/', Tools::getValue("txtOrderdate"));
                $day = $str[0];
                $month = $str[1];
                $year = $str[2];
                $format = $year.'-'.$month.'-'.$day;
				$this->orders = $this->getOrderDetailsByOrderDate($format);
            }
        }
    }
    private function getOrderDetailsByOrderId($orderId)
    {
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $order_array = array();
        $sql = "SELECT *,o.date_add,o.reference,c.firstname,c.lastname,ca.name as carrier_name,count(od.product_id) as total_product FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."customer c on c.id_customer = o.id_customer inner join "._DB_PREFIX_."carrier ca on ca.id_carrier = o.id_carrier inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id=".(int)$cookieObj['rid']." and ror.id_order = ".(int)$orderId." group by od.id_order";
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
    private function getOrderDetailsByOrderDate($date)
    {
	    $cookieObj = unserialize($this->context->cookie->vendorObj);
        $order_array = array();
        $sql = "SELECT *,o.date_add,o.reference,c.firstname,c.lastname,ca.name as carrier_name,count(od.product_id) as total_product FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."customer c on c.id_customer = o.id_customer inner join "._DB_PREFIX_."carrier ca on ca.id_carrier = o.id_carrier inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail where ror.restaurant_id=".(int)$cookieObj['rid']." and o.date_add BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' group by od.id_order";
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
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJqueryUI('ui.datepicker');
        
    }
}
