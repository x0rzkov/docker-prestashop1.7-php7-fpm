<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class AdminVendorDashboardController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
		$ps__base_url = $this->getPSBaseUrl().'modules/vendor/';
        $vendor_list_link  = 'index.php?controller=AdminVendorList';
        $vendor_list_link .= '&token='.Tools::getAdminTokenLite('AdminVendorList');
        $product_list_link  = 'index.php?controller=AdminProducts';
        $product_list_link .= '&token='.Tools::getAdminTokenLite('AdminProducts');
        $order_list_link  = 'index.php?controller=AdminOrders';
        $order_list_link .= '&token='.Tools::getAdminTokenLite('AdminOrders');
       
	    $this->context->smarty->assign(array(
            'total_restaurant'              => $this->getTotalStore(),
            'waiting_restaurant_approve'    => $this->getTotalWaitingApproveStore(),
            'total_product'                 => $this->getTotalStoreProduct(),
            'total_sale'                    => $this->getStoreTotalSale(),
            'fivesales'                     => $this->getLastFiveOrder(),
            'ps__base_url'                  => $ps__base_url,
            'withdraw_pending_list'         => $this->getPaymentPendingList(),
            'withdraw_success_list'         => $this->getPaymentSuccessList(),
            'bar_chart_data'                => $this->getStoreMonthlySaleStatus(),
            'token'                         => $this->token,
            'vendor_list_link'              => $vendor_list_link,
            'product_list_link'             => $product_list_link,
            'order_list_link'               => $order_list_link
        ));
    }
    public function initContent()
    {
        parent::initContent();
		$smarty = $this->context->smarty;
        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'vendor/views/templates/admin/dashbaord.tpl');
        $this->context->smarty->assign(array(
            'content'    => $this->content.$content
        ));
    }
    public function getTotalStore()
    {
        $total=0;
        $result = Db::getInstance()->getRow('SELECT count(rid) as total FROM '._DB_PREFIX_.'restrurent_registration');
        if (count($result) > 0 && $result['total'] != '') {
            $total = $result['total'];
        }
        return $total;
    }
    public function getTotalWaitingApproveStore()
    {
        $total=0;
        $result = Db::getInstance()->getRow('SELECT count(rid) as total FROM '._DB_PREFIX_.'restrurent_registration where status = 1 and approved = 0');
        if (count($result) > 0 && $result['total'] != '') {
            $total = $result['total'];
        }
        return $total;
    }
    public function getTotalStoreProduct()
    {
        $total=0;
        $result = Db::getInstance()->getRow('SELECT count(p.id_product) as total_product FROM `'._DB_PREFIX_.'product` p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product inner join `'._DB_PREFIX_.'restrurent_registration` rr on rr.rid = pvr.`restaurant_id` and rr.approved = 1');
        if (count($result) > 0 && $result['total_product'] != '') {
            $total = $result['total_product'];
        }
        return $total;
    }
    public function getStoreTotalSale()
    {
        $result = Db::getInstance()->getRow('SELECT count(od.product_id) as sale FROM '._DB_PREFIX_.'order_detail od inner join '._DB_PREFIX_.'restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail');
        if (count($result) > 0 && $result['sale'] != '') {
            return $result['sale'];
        }
        return 0;
    }
    public function getLastFiveOrder()
    {
        $order_array = array();
        $sql = "SELECT *,o.date_add FROM "._DB_PREFIX_."order_detail od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail order by ror.id_order_detail desc limit 5";
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
    
    public function getPaymentPendingList()
    {
        $pending_list = 0;
        $sql = 'SELECT *,w.status as payment_status FROM '._DB_PREFIX_.'payment_withdraw w inner join '._DB_PREFIX_.'restrurent_registration rr on rr.rid = w.rid where w.status = 0 order by w.wid desc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            $pending_list = count($results);
        }
        return $pending_list;
    }
    public function getPaymentSuccessList()
    {
        $success_list = 0;
        $sql = 'SELECT *,w.status as payment_status FROM '._DB_PREFIX_.'payment_withdraw w inner join '._DB_PREFIX_.'restrurent_registration rr on rr.rid = w.rid where w.status = 1 order by w.wid desc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            $success_list = count($results);
        }
        return $success_list;
    }
    
    private function getStoreMonthlySaleStatus()
    {
        $arr = array();
        $query = 'SELECT MONTHNAME(added_date) as month_name,SUM(vendor_amount) as total_amount FROM '._DB_PREFIX_.'vendor_payment_info where YEAR(added_date) = "'.date('Y').'" GROUP BY YEAR(added_date), MONTH(added_date)';
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
    
    public function getPSBaseUrl()
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
        $protocol = 'http://';
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        return $protocol;
    }
}
