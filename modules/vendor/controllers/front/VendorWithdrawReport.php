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

class VendorVendorWithdrawReportModuleFrontController extends ModuleFrontController
{
    // default vale
    private $success_msg = 'none';
    
    private $lists = array();
    
    private $type = '0';
    private $month = '0';
    private $year = '0';
    
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
        
        //here get all product for current store
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
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('reports/rptwithdrawreport.tpl');
		$this->setTemplate('module:vendor/views/templates/front/reports/rptwithdrawreport.tpl');
    }
    public function postProcess()
    {
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        if (Tools::isSubmit('submitCreate')) {
            $query = '';
            if (Tools::getValue('ddlWithdrawMonth') != '' && Tools::getValue('ddlWithdrawYear') != '') {
                if (Tools::getValue('ddlWithdrawType') == '0') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '1') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' and status = 0 order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '2') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' and status = 1 order by wid desc';
                }
            } elseif (Tools::getValue('ddlWithdrawMonth') != '') {
				if (Tools::getValue('ddlWithdrawType') == '0') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '1') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' and status = 0 order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '2') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Month(added_date) = '.(int)Tools::getValue('ddlWithdrawMonth').' and status = 1 order by wid desc';
                }
            } elseif (Tools::getValue('ddlWithdrawYear') != '') {
                if (Tools::getValue('ddlWithdrawType') == '0') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '1') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' and status = 0 order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '2') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and Year(added_date) = '.(int)Tools::getValue('ddlWithdrawYear').' and status = 1 order by wid desc';
                }
            } else {
                if (Tools::getValue('ddlWithdrawType') == '0') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '1') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and status = 0 order by wid desc';
                } elseif (Tools::getValue('ddlWithdrawType') == '2') {
                    $query = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid'].' and status = 1 order by wid desc';
                }
            }
            if ($query != '') {
				$this->getWithdrawInformation($query);
            }
        }
    }
    public function getWithdrawInformation($query)
    {
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $status = 'Pending';
                if ($row['status'] == 1) {
                    $status = 'Success';
                }
                $x = strtotime($row['added_date']);
                $added_date = date('d-m-Y', $x);
                $this->lists[] = array(
                    'wid'               => $row['wid'],
                    'added_date'        => $added_date,
                    'success_date'      => $row['success_date'],
                    'amount'            => Tools::displayPrice($row['amount']),
                    'status'            => $status
                );
            }
        }
    }
}
