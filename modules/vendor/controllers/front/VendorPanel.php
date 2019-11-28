<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class VendorVendorPanelModuleFrontController extends ModuleFrontController
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
        if (Tools::getValue('method') != '' && Tools::getValue('method')=='logout') {
            $this->context->cookie->__unset('vendorObj');
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
            die();
        }
        
        /*call vendor core class*/
		
        $slang = $this->context->language->id;
		
		
        $this->context->smarty->assign(array(
            'slang'              => $slang,
			'base_url'			 => $this->getPSBaseUrl()
        ));
        //$this->setTemplate('vendorpanel.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorpanel.tpl');
    }
	
	public function getPSBaseUrl()
	{
		$base_url = '';
		$result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');
		if(count($result) > 0){
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
		}
		else {
		  $protocol = 'http://';
		}
		return $protocol;
	}
}
