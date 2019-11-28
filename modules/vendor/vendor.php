<?php
/*
Restaurant Multi Vendor Module
Developed By SolverCircle
copyright: SolverCircle
*/

if (!defined('_PS_VERSION_'))
	exit;

class Vendor extends Module
{
	
	public function __construct()
	{
		$this->name = 'vendor';
		$this->tab = 'front_office_features';
		$this->version = '2.0';
		$this->author = 'SolverCircle';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Multi Vendor');
		$this->description = $this->l('Sell your product by your own store');
		$this->ps_versions_compliancy = array('min' => '1.5.6.1', 'max' => _PS_VERSION_);
		
		//parent menu
		$this->tabParentClassName = 'AdminVendorDashboard';
        $this->parentTabName = 'Vendor';
        //child 1
		$this->tabFirstChildName = 'Dashboard';
        $this->tabChildFirstClassName = 'AdminVendorDashboard';
        //child 2
		$this->tabSecoendChildName = 'Vendor List';
        $this->tabChildSecoendClassName = 'AdminVendorList';
		//child 3
		$this->tabThirdChildName = 'Requested Product List';
        $this->tabChildThirdClassName = 'AdminVendorRequestedProductList';
		//child 4
		$this->tabFourthChildName = 'Payment Request';
        $this->tabChildFourthClassName = 'AdminVendorWithdraw';
		//child 5
		$this->tabFifthChildName = 'Report';
        $this->tabChildFifthClassName = 'AdminVendorReport';
	}

	public function install()
	{
		if (!parent::install())
			return false;
		return $this->createQuickMenu('add') && $this->registerHook('backOfficeHeader') && $this->registerHook('displayBackOfficeHeader') && $this->registerHook('displayTop') && $this->registerHook('header') && $this->registerHook('footer') && $this->registerHook('displayProductButtons') && $this->registerHook('orderConfirmation') && $this->registerHook('displayFooter') && Configuration::updateValue('MULTI_VENDOR', 'Multi Vendor');
	}

	public function uninstall(){
		return parent::uninstall() && $this->createQuickMenu('remove') && Configuration::deleteByName('VENDOR_RESTAURANT');
	}
	//
	public function createQuickMenu($method){
		$url = $this->getPSBaseUrl();
		switch ($method) {
			case 'add':
				$tab = new Tab();
				$tab->class_name = $this->tabParentClassName;
				$tab->module = $this->name;
				$languages = Language::getLanguages();
				foreach ($languages as $language) {
					$tab->name[$language['id_lang']] = $this->parentTabName;
				}
				$tab->add();
				//here we create first child TAB
				$tab_first_child = new Tab();
				foreach ($languages as $language) {
					$tab_first_child->name[$language['id_lang']] = $this->tabFirstChildName;
				}
				$tab_first_child->class_name = $this->tabChildFirstClassName;
				$tab_first_child->id_parent = $tab->id;
				$tab_first_child->module = $this->name;
				$tab_first_child->add();
				//here we create secoend child TAB
				$tab_secoend_child = new Tab();
				foreach ($languages as $language) {
					$tab_secoend_child->name[$language['id_lang']] = $this->tabSecoendChildName;
				}
				$tab_secoend_child->class_name = $this->tabChildSecoendClassName;
				$tab_secoend_child->id_parent = $tab->id;
				$tab_secoend_child->module = $this->name;
				$tab_secoend_child->add();
				
				//here we create third child TAB
				$tab_third_child = new Tab();
				foreach ($languages as $language) {
					$tab_third_child->name[$language['id_lang']] = $this->tabThirdChildName;
				}
				$tab_third_child->class_name = $this->tabChildThirdClassName;
				$tab_third_child->id_parent = $tab->id;
				$tab_third_child->module = $this->name;
				$tab_third_child->add();
				
				//here we create fourth child TAB
				$tab_fourth_child = new Tab();
				foreach ($languages as $language) {
					$tab_fourth_child->name[$language['id_lang']] = $this->tabFourthChildName;
				}
				$tab_fourth_child->class_name = $this->tabChildFourthClassName;
				$tab_fourth_child->id_parent = $tab->id;
				$tab_fourth_child->module = $this->name;
				$tab_fourth_child->add();
				
				//here we create fourth child TAB
				$tab_fifth_child = new Tab();
				foreach ($languages as $language) {
					$tab_fifth_child->name[$language['id_lang']] = $this->tabFifthChildName;
				}
				$tab_fifth_child->class_name = $this->tabChildFifthClassName;
				$tab_fifth_child->id_parent = $tab->id;
				$tab_fifth_child->module = $this->name;
				$tab_fifth_child->add();
				
				//table creation
				$sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "restrurent_registration` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(150) CHARACTER SET utf8 NOT NULL,
  `lastname` varchar(150) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `telephone` varchar(50) CHARACTER SET utf8 NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(50) CHARACTER SET utf8 NOT NULL,
  `store_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `store_grid_image` varchar(200) CHARACTER SET utf8 NOT NULL,
  `store_banner_image` varchar(200) CHARACTER SET utf8 NOT NULL,
  `address` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `zipcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `schedule` text CHARACTER SET utf8 NOT NULL,
  `store_content` text CHARACTER SET utf8 NOT NULL,
  `grid_content` varchar(160) CHARACTER SET utf8 NOT NULL,
  `store_content_about_us` varchar(160) CHARACTER SET utf8 NOT NULL,
  `facebook_link` varchar(150) CHARACTER SET utf8 NOT NULL,
  `twitter_link` varchar(150) CHARACTER SET utf8 NOT NULL,
  `google_plus_link` varchar(150) CHARACTER SET utf8 NOT NULL,
  `store_email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `paypal_email` varchar(100) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `commission` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` int(1) NOT NULL DEFAULT '1',
  `store_style` varchar(5000) CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rid`))";
                if (!Db::getInstance()->Execute($sql)) {
                    return false;
                }
				//
				$sql2 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "restaurent_order_relationship` (
  `id_order` int(11) NOT NULL DEFAULT '0',
  `id_order_detail` int(11) NOT NULL DEFAULT '0',
  `restaurant_id` int(11) NOT NULL DEFAULT '0')";
                if (!Db::getInstance()->Execute($sql2)) {
                    return false;
                }
				//
				$sql3 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "restaurant_payment` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `vendor_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `admin_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`pid`))";
                if (!Db::getInstance()->Execute($sql3)) {
                    return false;
                }
				//
				$sql4 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "restaurant_order` (
  `roid` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`roid`))";
                if (!Db::getInstance()->Execute($sql4)) {
                    return false;
                }
				$sql5 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "product_vendor_relationship` (
  `relationId` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) NOT NULL DEFAULT '0',
  `restaurant_id` int(11) NOT NULL DEFAULT '0',
  `id_shop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`relationId`))";
                if (!Db::getInstance()->Execute($sql5)) {
                    return false;
                }
				$sql6 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "payment_withdraw` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `paypal_request_id` varchar(100) NOT NULL,
  `paypal_success_id` varchar(100) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `month` int(11) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`wid`))";
                if (!Db::getInstance()->Execute($sql6)) {
                    return false;
                }
				$sql7 = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "vendor_payment_info` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `vendor_amount` decimal(15,2) NOT NULL,
  `admin_amount` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission` decimal(15,2) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`))";
                if (!Db::getInstance()->Execute($sql7)) {
                    return false;
                }
				break;
				
			case 'remove':
				$moduleTabs = Tab::getCollectionFromModule($this->name);
				if (!empty($moduleTabs)) {
					foreach ($moduleTabs as $moduleTab) {
						$moduleTab->delete();
					}
				}
				//remove table
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'restrurent_registration')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'restaurent_order_relationship')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'restaurant_payment')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'restaurant_order')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_vendor_relationship')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'payment_withdraw')) {
                    return false;
                }
				if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'vendor_payment_info')) {
                    return false;
                }
				break;
		}
		return true;
	}
	public function hookBackOfficeHeader($params){
		$this->context->controller->addCSS($this->getPathUri(). 'views/css/vendor_res_admin.css');
	}
	public function hookDisplayBackOfficeHeader($params){
      $this->hookBackOfficeHeader($params);    
  	}
	public function hookDisplayTop($params){
	   $ps_base_url = $this->getPSBaseUrl();
	   $this->context->smarty->assign(array(
          'ps_base_url'        => $ps_base_url
       ));
	   return $this->display(__FILE__, 'views/templates/front/search_box_restaurant_old.tpl');
  	}
	public function hookDisplayFooter($params)
    {
        return $this->display(__FILE__, 'views/templates/front/layouts/front_footer.tpl');
    }
	/*Admin Settings Page*/
	public function getContent()
	{
		$html = '';
		/* Update values in DB */
		if (Tools::isSubmit('SubmitVendorResSettings'))
		{
			Configuration::updateValue('PS_VENDOR_RES_DISABLE_MARKET', (int)Tools::getValue('PS_VENDOR_RES_DISABLE_MARKET'));
			Configuration::updateValue('PS_VENDOR_RES_DISABLE_MESSAGE', (string)Tools::getValue('PS_VENDOR_RES_DISABLE_MESSAGE'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE', (string)Tools::getValue('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_API_USER_NAME', (string)Tools::getValue('PS_VENDOR_PAYPAL_API_USER_NAME'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_API_PASSWORD', (string)Tools::getValue('PS_VENDOR_PAYPAL_API_PASSWORD'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_API_SIGNATURE', (string)Tools::getValue('PS_VENDOR_PAYPAL_API_SIGNATURE'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT', (string)Tools::getValue('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT'));
			Configuration::updateValue('PS_VENDOR_PAYPAL_CURRENCY_MODE', (string)Tools::getValue('PS_VENDOR_PAYPAL_CURRENCY_MODE'));
			Configuration::updateValue('PS_VENDOR_PRODUCT_APPROVE', (string)Tools::getValue('PS_VENDOR_PRODUCT_APPROVE'));
			Configuration::updateValue('PS_VENDOR_DASHBOARD_LINK_FRONTEND', (string)Tools::getValue('PS_VENDOR_DASHBOARD_LINK_FRONTEND'));
			Configuration::updateValue('PS_VENDOR_REGISTRATION_LINK_FRONTEND', (string)Tools::getValue('PS_VENDOR_REGISTRATION_LINK_FRONTEND'));
			Configuration::updateValue('PS_VENDOR_LIST_LINK_FRONTEND', (string)Tools::getValue('PS_VENDOR_LIST_LINK_FRONTEND'));
			$html .= $this->displayConfirmation($this->l('Multi Vendor Settings updated Successfully'));
		}

		/* Configuration form */

		return $html.$this->renderForm();
	}
	public function renderForm()
	{
		$paypal_model = array();
		$paypal_currency = array();
		
		$paypal_model[] = array(
			'id' => 'sandbox',
			'name' => 'sandbox',
			'PS_VENDOR_PAYPAL_ENVIRONMENT_MODE' => 'sandbox',
		);
		$paypal_model[] = array(
			'id' => 'live',
			'name' => 'live',
			'PS_VENDOR_PAYPAL_ENVIRONMENT_MODE' => 'live',
		);
		
		$paypal_currency[] = array(
			'id' => 'USD',
			'name' => 'USD',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'USD',
		);
		$paypal_currency[] = array(
			'id' => 'GBP',
			'name' => 'GBP',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'GBP',
		);
		$paypal_currency[] = array(
			'id' => 'EUR',
			'name' => 'EUR',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'EUR',
		);
		$paypal_currency[] = array(
			'id' => 'JPY',
			'name' => 'JPY',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'JPY',
		);
		$paypal_currency[] = array(
			'id' => 'CAD',
			'name' => 'CAD',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'CAD',
		);
		$paypal_currency[] = array(
			'id' => 'AUD',
			'name' => 'AUD',
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' => 'AUD',
		);
		
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Disable All Market Temporary'),
						'desc' => $this->l(''),
						'name' => 'PS_VENDOR_RES_DISABLE_MARKET',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Disabled Message to Customer'),
						'name' => 'PS_VENDOR_RES_DISABLE_MESSAGE',
						'autoload_rte' => true
					),
					array(
						'type' => 'select',
						'label' => $this->l('Paypal Environment Mode'),
						'name' => 'PS_VENDOR_PAYPAL_ENVIRONMENT_MODE',
						'required' => false,
						'options' => array(
							'query' => $paypal_model,
							'id' => 'PS_VENDOR_PAYPAL_ENVIRONMENT_MODE',
							'name' => 'name'
						),
						'desc' => $this->l('Paypal Environment Mode')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Paypal API UserName'),
						'name' => 'PS_VENDOR_PAYPAL_API_USER_NAME',
						'suffix' => $this->l('Paypal API Username')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Paypal API Password'),
						'name' => 'PS_VENDOR_PAYPAL_API_PASSWORD',
						'suffix' => $this->l('Paypal API Password')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Paypal API Signature'),
						'name' => 'PS_VENDOR_PAYPAL_API_SIGNATURE',
						'suffix' => $this->l('Paypal API Signature')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Paypal Payment Subject'),
						'name' => 'PS_VENDOR_PAYPAL_PAYMENT_SUBJECT',
						'suffix' => $this->l('Paypal Payment Subject')
					),
					array(
						'type' => 'select',
						'label' => $this->l('Paypal Currency: '),
						'name' => 'PS_VENDOR_PAYPAL_CURRENCY_MODE',
						'required' => false,
						'options' => array(
							'query' => $paypal_currency,
							'id' => 'PS_VENDOR_PAYPAL_CURRENCY_MODE',
							'name' => 'name'
						),
						'desc' => $this->l('Paypal Currency Mode')
					),
					array(
						'type' => 'text',
						'readonly'	=> 'true',
						'label' => $this->l('Vendor Dashboard Link Front End'),
						'name' => 'PS_VENDOR_DASHBOARD_LINK_FRONTEND',
						'suffix' => $this->l('Vendor Dashboard')
					),
					array(
						'type' => 'text',
						'readonly'	=> 'true',
						'label' => $this->l('Vendor Registration Link Front End'),
						'name' => 'PS_VENDOR_REGISTRATION_LINK_FRONTEND',
						'suffix' => $this->l('Vendor Registration')
					),
					array(
						'type' => 'text',
						'readonly'	=> 'true',
						'label' => $this->l('Vendor List Link Front End'),
						'name' => 'PS_VENDOR_LIST_LINK_FRONTEND',
						'suffix' => $this->l('Vendor List Link')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Product Approve System'),
						'desc' => $this->l(''),
						'name' => 'PS_VENDOR_PRODUCT_APPROVE',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'SubmitVendorResSettings';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{
		$PS_VENDOR_DASHBOARD_LINK_FRONTEND = _PS_BASE_URL_.__PS_BASE_URI__.'index.php?fc=module&module=vendor&controller=VendorPanel';
		$PS_VENDOR_REGISTRATION_LINK_FRONTEND = _PS_BASE_URL_.__PS_BASE_URI__.'index.php?fc=module&module=vendor&controller=VendorPanel';
		$PS_VENDOR_LIST_LINK_FRONTEND = _PS_BASE_URL_.__PS_BASE_URI__.'index.php?fc=module&module=vendor&controller=VendorRestaurantList';
		return array(
			'PS_VENDOR_RES_DISABLE_MARKET' 			=> Tools::getValue('PS_VENDOR_RES_DISABLE_MARKET', Configuration::get('PS_VENDOR_RES_DISABLE_MARKET')),
			'PS_VENDOR_RES_DISABLE_MESSAGE' 		=> Tools::getValue('PS_VENDOR_RES_DISABLE_MESSAGE', Configuration::get('PS_VENDOR_RES_DISABLE_MESSAGE')),
			'PS_VENDOR_PAYPAL_ENVIRONMENT_MODE' 	=> Tools::getValue('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE', Configuration::get('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE')),
			'PS_VENDOR_PAYPAL_API_USER_NAME' 		=> Tools::getValue('PS_VENDOR_PAYPAL_API_USER_NAME', Configuration::get('PS_VENDOR_PAYPAL_API_USER_NAME')),
			'PS_VENDOR_PAYPAL_API_PASSWORD' 		=> Tools::getValue('PS_VENDOR_PAYPAL_API_PASSWORD', Configuration::get('PS_VENDOR_PAYPAL_API_PASSWORD')),
			'PS_VENDOR_PAYPAL_API_SIGNATURE' 		=> Tools::getValue('PS_VENDOR_PAYPAL_API_SIGNATURE', Configuration::get('PS_VENDOR_PAYPAL_API_SIGNATURE')),
			'PS_VENDOR_PAYPAL_PAYMENT_SUBJECT' 		=> Tools::getValue('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT', Configuration::get('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT')),
			'PS_VENDOR_PAYPAL_CURRENCY_MODE' 		=> Tools::getValue('PS_VENDOR_PAYPAL_CURRENCY_MODE', Configuration::get('PS_VENDOR_PAYPAL_CURRENCY_MODE')),
			'PS_VENDOR_PRODUCT_APPROVE' 			=> Tools::getValue('PS_VENDOR_PRODUCT_APPROVE', Configuration::get('PS_VENDOR_PRODUCT_APPROVE')),
			'PS_VENDOR_DASHBOARD_LINK_FRONTEND' 	=> Tools::getValue('PS_VENDOR_DASHBOARD_LINK_FRONTEND', $PS_VENDOR_DASHBOARD_LINK_FRONTEND),
			'PS_VENDOR_REGISTRATION_LINK_FRONTEND' 	=> Tools::getValue('PS_VENDOR_REGISTRATION_LINK_FRONTEND', $PS_VENDOR_REGISTRATION_LINK_FRONTEND),
			'PS_VENDOR_LIST_LINK_FRONTEND' 			=> Tools::getValue('PS_VENDOR_LIST_LINK_FRONTEND', $PS_VENDOR_LIST_LINK_FRONTEND)
		);
		
	}
	//fire when customer go to product details page show store information
	public function hookDisplayProductButtons($params)
    {   
		$product_id = $params['product']['id_product'];
		$ps_base_url = $this->getPSBaseUrl();
		if(!empty($product_id)){
			$result = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_vendor_relationship` pvr inner join `'._DB_PREFIX_.'restrurent_registration` rr on rr.rid = pvr.restaurant_id WHERE pvr.id_product = '.(int)$product_id);
			if (count($result) > 0) {
				if (!empty($result['firstname'])) {
                    $slang = $this->context->language->id;
					$this->context->smarty->assign(array(
					  'firstname'       => $result['firstname'],
					  'lastname'        => $result['lastname'],
					  'ps_base_url'     => $ps_base_url,
					  'rid'				=> $result['restaurant_id'],
					  'product_id'		=> $product_id,
					  //'pprice'			=> Tools::displayPrice($params['product']->base_price),
					  'slang'           => $slang,
					  'store_name'		=>$result['store_name']
					));
					$this->_clearCache('shopTagInfo.tpl');
					return $this->display(__FILE__, 'views/templates/front/shopTagInfo.tpl');
                }
			}
		}
    }
	//fire hook when order comfirmed
	public function hookOrderConfirmation($params)
	{
		$total = 0;
		$order = $params['order'];
		$products = $order->getProducts();
		foreach ($products as $product){
			$restaurant_id = $this->getRestaurentIdByProductId($product['product_id']);
			$id_order = $product['id_order'];
			$id_order_detail = $product['id_order_detail'];
			$total_price = $product['total_price'];
			if((int)$restaurant_id > 0){
				$commission = $this->getRestaurantCommission($restaurant_id);	
				$admin_amount = (float)((float)$total_price * (float)$commission / 100); // admin amount
				$vendor_amount = (float)((float)$total_price - (float)$admin_amount); // vendor amount
				$this->updateVendorCurrentAmount($restaurant_id,$admin_amount,$vendor_amount,$commission,$total_price); // update virtual payment table
				$this->updateOrderDetailsByRestaurantId($id_order, $id_order_detail, $restaurant_id);
			}
		}
	}
	private function updateVendorCurrentAmount($storeId,$admin_amount,$vendor_amount,$commission,$total){
		$q='INSERT INTO '._DB_PREFIX_.'vendor_payment_info(rid,vendor_amount,admin_amount,commission,total_price) values('.(int)$storeId.','.(float)$vendor_amount.','.(float)$admin_amount.','.(float)$commission.','.(float)$total.')';
		Db::getInstance()->Execute($q);
	}
	private function getRestaurantCommission($storeId){
		$result = Db::getInstance()->getRow('SELECT commission FROM '._DB_PREFIX_.'restrurent_registration where rid = "'.(int)$storeId.'"');
		if(count($result) > 0 && (float)$result['commission'] > 0){
			return $result['commission'];
		}
		return 0;
	}
	private function getRestaurentIdByProductId($product_id) 
	{
		if (!empty($product_id)) {
			$result = Db::getInstance()->getRow('SELECT restaurant_id FROM '._DB_PREFIX_.'product_vendor_relationship where id_product = "'.(int)$product_id.'"');
			if (count($result) > 0 && (int)$result['restaurant_id'] > 0) {
				return $result['restaurant_id'];
			}
			return 0;
		}
	}
	private function updateOrderDetailsByRestaurantId($id_order,$id_order_detail,$store_id)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.'restaurent_order_relationship(id_order,id_order_detail,restaurant_id) VALUES('.(int)$id_order.','.(int)$id_order_detail.','.(int)$store_id.')';
		Db::getInstance()->Execute($sql);
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

