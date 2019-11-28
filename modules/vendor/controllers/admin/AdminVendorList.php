<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class AdminVendorListController extends ModuleAdminController
{
    private $sMsg = '';
   
    public function __construct()
    {
        
		if (Tools::isSubmit('SubmitCreate')) {
            $commission = Tools::getValue('txtSetAdminCommission');
			$vendorId = Tools::getValue('vendorId');
			if (!Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'restrurent_registration set commission = "'.(float)$commission.'" WHERE rid="'.(int)$vendorId.'"')) {
                $this->errorlog[] = $this->l("ERROR");
            }
            $this->sMsg = 'Added Commission Successfully';
        }
		
		$this->bootstrap = true;
        parent::__construct();
        $restrurent_list = array();
        $base_url = $this->getPSBaseUrl();
        $ps__base_url = $base_url.'modules/vendor/';
        $sql = 'SELECT *,cl.name FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id AND cl.id_lang = '.$this->context->language->id.' ORDER BY rr.rid desc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $restrurent_list[] = array(
                    'rid'               => $row['rid'],
                    'firstname'         => $row['firstname'],
                    'lastname'          => $row['lastname'],
                    'email'             => $row['email'],
                    'telephone'         => $row['telephone'],
                    'approved'          => $row['approved'],
                    'commission'        => $row['commission'],
                    'status'            => $row['status'],
                    'country'           => $row['name'],
                    'grid_image'        => $row['store_grid_image'],
                    'created_date'      => $row['created_date'],
                    'total_product'     => $this->getStoreTotalProduct($row['rid']),
                    'total_active'      => $this->getStoreActiveTotalProduct($row['rid']),
                    'total_inactive'    => $this->getStoreInactiveTotalProduct($row['rid']),
                    'total_sale'        => $this->getStoreTotalSale($row['rid']),
                    'total_amount'      => $this->getVendorTotalSale($row['rid']),
                    'admin_amount'      => $this->getAdminTotalAmount($row['rid'])
                );
            }
        }
        $this->context->smarty->assign(array(
            'restrurent_list'   => $restrurent_list,
            'ps__base_url'      => $ps__base_url,
            'base_url'          => $base_url,
            'xMsg'              => $this->sMsg,
            'token'             => $this->token
        ));
    }
    public function initContent()
    {
        parent::initContent();
        $smarty = $this->context->smarty;
        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'vendor/views/templates/admin/restaurantlist.tpl');
        $this->context->smarty->assign(array(
            'content'    => $this->content.$content
        ));
    }
    public function getStoreTotalProduct($storeId)
    {
        $result = Db::getInstance()->getRow('SELECT count(p.id_product) as total FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id = "'.(int)$storeId.'"');
        if (count($result) > 0 && $result['total'] != '') {
            return $result['total'];
        }
        return 0;
    }
    public function getStoreActiveTotalProduct($storeId)
    {
        $result = Db::getInstance()->getRow('SELECT count(p.id_product) as total FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id = "'.(int)$storeId.'" and p.active = 1');
        if (count($result) > 0 && $result['total'] != '') {
            return $result['total'];
        }
        return 0;
            
    }
    public function getStoreInactiveTotalProduct($storeId)
    {
        $result = Db::getInstance()->getRow('SELECT count(p.id_product) as total FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id = "'.(int)$storeId.'" and p.active = 0');
        if (count($result) > 0 && $result['total'] != '') {
            return $result['total'];
        }
        return 0;
            
    }
    public function getStoreTotalSale($storeId)
    {
        $result = Db::getInstance()->getRow('SELECT count(id_order_detail) as sale FROM '._DB_PREFIX_.'restaurent_order_relationship where restaurant_id='.(int)$storeId);
        if (count($result) > 0 && $result['sale'] != '') {
            return $result['sale'];
        }
        return 0;
    }
    private function getVendorTotalSale($storeId)
    {
        $amount = number_format(0, 2, '.', ' ');
        $result = Db::getInstance()->getRow('SELECT sum(vendor_amount) as vamount FROM '._DB_PREFIX_.'vendor_payment_info where rid = '.(int)$storeId);
        if (count($result) > 0 && $result['vamount'] != '') {
            $amount = number_format($result['vamount'], 2, '.', ' ');
        }
        return $amount;
    }
    private function getAdminTotalAmount($storeId)
    {
        $amount = number_format(0, 2, '.', ' ');
        $result = Db::getInstance()->getRow('SELECT sum(admin_amount) aamount FROM '._DB_PREFIX_.'vendor_payment_info where rid = '.(int)$storeId);
        if (count($result) > 0 && $result['aamount'] != '') {
            $amount = number_format($result['aamount'], 2, '.', ' ');
        }
        return $amount;
    }
    /*public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            $commission = Tools::getValue('txtSetAdminCommission');
			$vendorId = Tools::getValue('vendorId');
			if (!Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'restrurent_registration set commission = "'.(float)$commission.'" WHERE rid="'.(int)$vendorId.'"')) {
                $this->errorlog[] = $this->l("ERROR");
            }
            $this->sMsg = 'Added Commission Successfully';
        }
    }*/
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
