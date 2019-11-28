<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/


class AdminVendorReportController extends ModuleAdminController
{
    private $sMsg = '';
   
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $restrurent_list = array();
        $base_url = $this->getPSBaseUrl();
        $ps__base_url = $base_url.'modules/vendor/';
        
		/*
		* @ report array
		*/
		$vendor_store_info = array();
		$vendor_product_details = array();
		$vendor_order_details = array();
		
		
		$vendor_list = $this->storeDetailsByStoreId();
		
		$selected_store = 0;
		$selected_report_type = 0;
		
		if (Tools::isSubmit('hdnreportoption')) {
			$selected_store = Tools::getValue('ddlVendorList');
			$selected_report_type = Tools::getValue('ddlVendorReportType');
			if ($selected_report_type == 1) {
				$vendor_store_info = $this->storeDetailsByStoreId($selected_store);
			}
			else if ($selected_report_type == 2) {
				$vendor_product_details = $this->getVendorStoreProductInformation($selected_store);
			}
			else if ($selected_report_type == 3) {
				$vendor_order_details = $this->getStoreAllOrder($selected_store);
			}
		}

		$this->context->smarty->assign(array(
            'vendor_list'   			=> $vendor_list,
            'ps__base_url'      		=> $ps__base_url,
            'base_url'          		=> $base_url,
			'selected_store'			=> $selected_store,
			'selected_report_type'		=> $selected_report_type,
			'vendor_store_info'			=> $vendor_store_info,
			'vendor_product_details'	=> $vendor_product_details,
			'vendor_order_details'		=> $vendor_order_details,
            'xMsg'              		=> $this->sMsg,
            'token'             		=> $this->token
        ));
    }
	
	private function getStoreAllOrder($storeId)
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
	
	private function getVendorStoreProductInformation($storeId)
    {
		$link = new Link();
        $products = array();
        $query='SELECT * FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id='.(int)$storeId . ' order by p.id_product desc';
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $product = new Product($row['id_product'], false, $this->context->language->id);
				$category = new Category($row['id_category_default']);
                $id_product = $row['id_product'];
                $image = Image::getCover($id_product);
                $imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));
			    $products[] = array(
                    'id_product'    => $row['id_product'],
                    'name'          => $product->name,
					'status'		=> $row['active'],
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
	
	private function storeDetailsByStoreId($storeId = 0) {
		$vendor_info = array();
		$sql = 'SELECT *,cl.name FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id ORDER BY rr.rid desc';
		if (!empty($storeId) && (int)$storeId > 0) {
			$sql = 'SELECT *,cl.name FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id WHERE rid ='.(int)$storeId;
		}
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $vendor_info[] = array(
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
		return $vendor_info;
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
	
    public function initContent()
    {
        parent::initContent();
        $smarty = $this->context->smarty;
        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'vendor/views/templates/admin/vendorreport.tpl');
        $this->context->smarty->assign(array(
            'content'    => $this->content.$content
        ));
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
