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

class VendorVendorSoldStatusReportModuleFrontController extends ModuleFrontController
{
    // default vale
    private $success_msg = 'none';
    
    private $lists = array();
    
    private $date_filter = '';
    private $month_filter = '';
    private $year_filter = '';
    
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
        
        $year_arr = array();
        for ($i=2000; $i<=date('Y'); $i++) {
            $year_arr[] = $i;
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
            'welcome_name'       => $welcome_name,
            'lists'              => $this->lists,
            'year_arr'           => $year_arr,
            'date_filter'        => $this->date_filter,
            'month_filter'       => $this->month_filter,
            'year_filter'        => $this->year_filter,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('reports/rptproductsoldstatus.tpl');
		$this->setTemplate('module:vendor/views/templates/front/reports/rptproductsoldstatus.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('submitCreate')) {
            $cookieObj = unserialize($this->context->cookie->vendorObj);
            $query = '';
            if (Tools::getValue('ddlSellMonth') != '' && Tools::getValue('ddlSellYear') != '') {
                $this->month_filter = Tools::getValue('ddlSellMonth');
                $this->year_filter = Tools::getValue('ddlSellYear');
                $query = 'SELECT *,o.date_add as order_date FROM `'._DB_PREFIX_.'order_detail` od inner join '._DB_PREFIX_.'orders o on o.id_order = od.id_order inner join '._DB_PREFIX_.'restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail WHERE MONTH(o.date_add) = '.(int)Tools::getValue('ddlSellMonth').' AND YEAR(o.date_add) = '.(int)Tools::getValue('ddlSellYear').' AND ror.restaurant_id = '.(int)$cookieObj['rid'];
            } elseif (Tools::getValue('ddlSellMonth') != '') {
                $this->month_filter = Tools::getValue('ddlSellMonth');
                $query = 'SELECT *,o.date_add as order_date FROM `'._DB_PREFIX_.'order_detail` od inner join '._DB_PREFIX_.'orders o on o.id_order = od.id_order inner join '._DB_PREFIX_.'restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail WHERE MONTH(o.date_add) = '.(int)Tools::getValue('ddlSellMonth').' and ror.restaurant_id = '.(int)$cookieObj['rid'];
            } elseif (Tools::getValue('ddlSellYear') != '') {
                $this->year_filter = Tools::getValue('ddlSellYear');
                $query = 'SELECT *,o.date_add as order_date FROM `'._DB_PREFIX_.'order_detail` od inner join '._DB_PREFIX_.'orders o on o.id_order = od.id_order inner join '._DB_PREFIX_.'restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail WHERE YEAR(o.date_add) = '.(int)Tools::getValue('ddlSellYear').' and ror.restaurant_id = '.(int)$cookieObj['rid'];
            } elseif (Tools::getValue('txtSellDate') != '') {
                $this->date_filter = Tools::getValue('txtSellDate');
                $date = explode('/', Tools::getValue('txtSellDate'));
                $new_date = $date[2].'-'.$date[1].'-'.$date[0];
                $query = "SELECT *,o.date_add as order_date FROM `"._DB_PREFIX_."order_detail` od inner join "._DB_PREFIX_."orders o on o.id_order = od.id_order inner join "._DB_PREFIX_."restaurent_order_relationship ror on ror.id_order_detail = od.id_order_detail WHERE ror.restaurant_id=".(int)$cookieObj['rid']. " and o.date_add BETWEEN '".$new_date." 00:00:00' AND '".$new_date." 23:59:59'";
            }
            if ($query != '') {
                $this->getSellReportData($query);
            }
        }
    }
    public function getSellReportData($query)
    {
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $this->lists[] = array(
                    'order_id'          => $row['id_order'],
                    'product_name'      => $row['product_name'],
                    'product_price'     => Tools::displayPrice($row['product_price']),
                    'date_add'          => date('d-m-Y H:i:s', strtotime($row['date_add']))
                );
            }
        }
    }
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJqueryUI('ui.datepicker');
        
    }
}
