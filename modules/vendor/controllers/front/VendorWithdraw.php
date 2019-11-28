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

class VendorVendorWithdrawModuleFrontController extends ModuleFrontController
{
    // default vale
        
    private $msgx = 'none';
    private $warrning = 'none';
    
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
            'balance'            => Tools::displayPrice($this->getAvailableBalanceForWithdraw()),
            'dvalue'             => Tools::displayPrice(0.00),
            'msgx'               => $this->msgx,
            'warrning'           => $this->warrning,
            'welcome_name'       => $welcome_name,
            'withdraw'           => $this->checkVendorPendingWithdraw($cookieObj['rid']),
            'lists'              => $this->getWithdrawPendingList(),
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        
		//$this->setTemplate('vendorwithdraw.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorwithdraw.tpl');
    }
    public function getWithdrawPendingList()
    {
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $lists = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid='.(int)$cookieObj['rid']. ' order by wid desc limit 10';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $status = 'Pending';
                if ($row['status'] == 1) {
                    $status = 'Success';
                }
                $x = strtotime($row['added_date']);
                $added_date = date('d-m-Y', $x);
                $lists[] = array(
                    'wid'               => $row['wid'],
                    'added_date'        => $added_date,
                    'success_date'      => $row['success_date'],
                    'amount'            => Tools::displayPrice($row['amount']),
                    'status'            => $status
                );
            }
        }
        return $lists;
    }
    public function postProcess()
    {
        if (Tools::isSubmit('Submitwithdraw')) {
            $cookieObj = unserialize($this->context->cookie->vendorObj);
            if (empty($this->context->cookie->vendorObj)) {
                Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
                die();
            }
            $amount = Tools::getValue('txtWithdrawAmount');
            if ((float)$amount<=(float)$this->getAvailableBalanceForWithdraw()) {
                $query = 'INSERT INTO '._DB_PREFIX_.'payment_withdraw SET rid='.(int)$cookieObj['rid'].', amount = '.(int)$amount;
                Db::getInstance()->Execute($query);
                $this->msgx = 'block';
            } else {
                $this->warrning = 'block';
            }
        }
    }
    private function checkVendorPendingWithdraw($storeId)
    {
        $return = false;
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'payment_withdraw where rid = '.(int)$storeId.' and status = 0');
        if (count($result) > 0 && $result['rid'] != '') {
            $return = true;
        }
        return $return;
    }
    private function getAvailableBalanceForWithdraw()
    {
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $total_balance = 0;
        $balance = $this->getVendorTotalSale($cookieObj['rid']);
        $withdraw = $this->getTotalWithdrawBalance($cookieObj['rid']);
        $total_balance = (float)((float)$balance - (float)$withdraw);
        $total_balance = number_format($total_balance, 2, '.', '');
        return $total_balance;
    }
    private function getVendorTotalSale($storeId)
    {
        $amount = number_format(0, 2, '.', ' ');
        $result = Db::getInstance()->getRow('SELECT sum(vendor_amount) as vendor_amount FROM '._DB_PREFIX_.'vendor_payment_info where rid = '.(int)$storeId);
        if (count($result) > 0 && $result['vendor_amount'] != '') {
            $amount = number_format($result['vendor_amount'], 1, '.', '');
        }
        return $amount;
            
    }
    private function getTotalWithdrawBalance($storeId)
    {
        $amount = number_format(0, 2, '.', ' ');
        $result = Db::getInstance()->getRow('SELECT sum(amount) as amount FROM '._DB_PREFIX_.'payment_withdraw where rid = '.(int)$storeId);
        if (count($result) > 0 && $result['amount'] != '') {
            $amount = number_format($result['amount'], 1, '.', '');
        }
        return $amount;
    }
}
