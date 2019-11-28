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

class VendorVendorRestaurantSetupModuleFrontController extends ModuleFrontController
{
    private $success_msg = 'none';
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        
        parent::initContent();
		
		//init
		$vendorCore = new ScVendor;
        
        $ps_base_url = $vendorCore->getPSBaseUrl();
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        if (empty($this->context->cookie->vendorObj)) {
            Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
            die();
        }
        
        if (Tools::getValue('imgdel') && Tools::getValue('imgdel') == 'grid') {
            $query = 'UPDATE '._DB_PREFIX_.'restrurent_registration SET store_grid_image="" WHERE rid="'.(int)$cookieObj['rid'].'"';
            Db::getInstance()->Execute($query);
            $this->success_msg = 'block';
        }
        //here remove banner image
        if (Tools::getValue('imgdel') && Tools::getValue('imgdel') == 'banner') {
            $query = 'UPDATE '._DB_PREFIX_.'restrurent_registration SET store_banner_image="" WHERE rid="'.(int)$cookieObj['rid'].'"';
            Db::getInstance()->Execute($query);
            $this->success_msg = 'block';
        }
        $store_info = $vendorCore->getStoreInfo();
        $store_name = $store_info['store_name'];
        $grid_image = $store_info['store_grid_image'];
        $banner_image = $store_info['store_banner_image'];
        $store_address = $store_info['address'];
        $store_zipcode = $store_info['zipcode'];
        $store_schedule = base64_decode($store_info['schedule']);
        $face_book_link = $store_info['facebook_link'];
        $twitter_link = $store_info['twitter_link'];
        $google_plus_link = $store_info['google_plus_link'];
        $store_content = base64_decode($store_info['store_content']);
		$store_content_about_us = base64_decode($store_info['store_content_about_us']);
        $store_email = $store_info['store_email'];
        $paypal_email = $store_info['paypal_email'];
        $grid_content = base64_decode($store_info['grid_content']);
        
        $vendor_color_1 = '000000';
        $vendor_color_2 = '000000';
        $vendor_color_3 = '000000';
        $vendor_color_4 = '000000';
        $vendor_color_5 = 'a4a547';
        $vendor_color_6 = '000000';
		$vendor_color_7 = 'ffffff';
		$vendor_color_8 = 'ffffff';
		$vendor_color_9 = 'ffffff';
		$vendor_color_10 = 'e6ecec';
		$vendor_color_11 = 'ffffff';
        
        if (!empty($store_info['store_style'])) {
            $store_style = Tools::jsonDecode($store_info['store_style']);
            if (!empty($store_style->vendor_color_1)) {
                $vendor_color_1 = $store_style->vendor_color_1;
            }
            if (!empty($store_style->vendor_color_2)) {
                $vendor_color_2 = $store_style->vendor_color_2;
            }
            if (!empty($store_style->vendor_color_3)) {
                $vendor_color_3 = $store_style->vendor_color_3;
            }
            if (!empty($store_style->vendor_color_4)) {
                $vendor_color_4 = $store_style->vendor_color_4;
            }
            if (!empty($store_style->vendor_color_5)) {
                $vendor_color_5 = $store_style->vendor_color_5;
            }
			if (!empty($store_style->vendor_color_6)) {
                $vendor_color_6 = $store_style->vendor_color_6;
            }
			if (!empty($store_style->vendor_color_7)) {
                $vendor_color_7 = $store_style->vendor_color_7;
            }
			if (!empty($store_style->vendor_color_8)) {
                $vendor_color_8 = $store_style->vendor_color_8;
            }
			if (!empty($store_style->vendor_color_9)) {
                $vendor_color_9 = $store_style->vendor_color_9;
            }
			if (!empty($store_style->vendor_color_10)) {
                $vendor_color_10 = $store_style->vendor_color_10;
            }
			if (!empty($store_style->vendor_color_11)) {
                $vendor_color_11 = $store_style->vendor_color_11;
            }
            
        }
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];
        /*End*/
        //////////////////////////////////SEND DATA TO SMARTY////////////////////////////////////
        $slang = $this->context->language->id;
        $this->context->smarty->assign(array(
            'slang'                             => $slang,
            'ps_base_url'                       => $ps_base_url,
            'welcome_name'                      => $welcome_name,
            'store_name'                        => $store_name,
            'grid_image'                        => $grid_image,
            'banner_image'                      => $banner_image,
            'store_address'                     => $store_address,
            'store_zipcode'                     => $store_zipcode,
            'store_schedule'                    => $store_schedule,
            'store_email'                       => $store_email,
            'paypal_email'                      => $paypal_email,
            'facebook_link'                     => $face_book_link,
            'twitter_link'                      => $twitter_link,
            'google_plus_link'                  => $google_plus_link,
            'store_content'                     => $store_content,
			'store_content_about_us'			=> $store_content_about_us,
            'grid_content'                      => $grid_content,
            'msgx'                              => $this->success_msg,
            'store_rating'                      => $rating,
            'total_rating'                      => $total_rating,
            'rating_image'                      => $rating_image,
            'xqty'                              => $vendorCore->lessQuantityAlert($cookieObj['rid']),
            'vendor_color_1'                  	=> $vendor_color_1,
            'vendor_color_2'   					=> $vendor_color_2,
            'vendor_color_3'             		=> $vendor_color_3,
            'vendor_color_4'    				=> $vendor_color_4,
            'vendor_color_5'           			=> $vendor_color_5,
            'vendor_color_6'                	=> $vendor_color_6,
			'vendor_color_7'                	=> $vendor_color_7,
			'vendor_color_8'                	=> $vendor_color_8,
			'vendor_color_9'                	=> $vendor_color_9,
			'vendor_color_10'                	=> $vendor_color_10,
			'vendor_color_11'                	=> $vendor_color_11
        ));
        
		//$this->setTemplate('vendorrestaurantsetup.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorrestaurantsetup.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            $cookieObj = unserialize($this->context->cookie->vendorObj);
            $store_id = $cookieObj['rid'];
            if ($store_id != '' && (int)$store_id > 0) {
                $glink = '';
                $blink = '';
                if ($_FILES["gridimage"]['name'] != '') {
                    $glink = $this->uploadFunction($_FILES["gridimage"], 'grid');
                } else {
                    $glink = Tools::getValue('hdnGridImage');
                }
                if ($_FILES["bannerimage"]['name'] != '') {
                    $blink = $this->uploadFunction($_FILES["bannerimage"], 'banner');
                } else {
                    $blink = Tools::getValue('hdnBannerImage');
                }
                $style_arr = array(
                    'vendor_color_1'    => Tools::getValue('txtTopHeaderTextColor'),
                    'vendor_color_2'    => Tools::getValue('txtStorePhoneTextColor'),
                    'vendor_color_3'    => Tools::getValue('txtStoreEmailColor'),
                    'vendor_color_4'    => Tools::getValue('txtTopOtherTextColor'),
                    'vendor_color_5'    => Tools::getValue('txtLeftMenuTextColor'),
                    'vendor_color_6'    => Tools::getValue('txtLeftMenuSelectedTextColor'),
					'vendor_color_7'    => Tools::getValue('txtVendorTopBarBGColor'),
					'vendor_color_8'    => Tools::getValue('txtVendorTopBorderBGColor'),
					'vendor_color_9'    => Tools::getValue('txtVendorStoreLeftMenuBGColor'),
					'vendor_color_10'    => Tools::getValue('txtVendorStoreLeftMenuSelectedColor'),
					'vendor_color_11'   => Tools::getValue('txtVendorStoreContentBoxBGColor')
                );
				
                $query = 'UPDATE '._DB_PREFIX_.'restrurent_registration SET store_name="'.pSQL(Tools::getValue('txtStoreName')).'",store_grid_image="'.pSQL($glink).'",store_banner_image="'.pSQL($blink).'",address="'.pSQL(Tools::getValue('txtStoreAddress')).'",zipcode="'.pSQL(Tools::getValue('txtZipCode')).'",store_email="'.pSQL(Tools::getValue('txtStoreEmail')).'",paypal_email="'.pSQL(Tools::getValue('txtPaypalEmail')).'",schedule="'.base64_encode(Tools::getValue('txtSchedule')).'",facebook_link="'.pSQL(Tools::getValue('txtFacebookLink')).'",twitter_link="'.pSQL(Tools::getValue('txtTwitterLink')).'",google_plus_link="'.pSQL(Tools::getValue('txtGooglePlus')).'",store_content="'.base64_encode(Tools::getValue('txtStoreContent')).'",store_content_about_us="'.base64_encode(Tools::getValue('txtStoreContentAboutUs')).'",grid_content="'.base64_encode(Tools::getValue('txtGridContent')).'",store_style="'.pSQL(Tools::jsonEncode($style_arr)).'" WHERE rid="'.(int)$store_id.'"';
                Db::getInstance()->Execute($query);
                $this->success_msg = 'block';
            }
        }
    }
    public function uploadFunction($uploadName, $type)
    {
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $vendorCore = new ScVendor;
		if (is_array($uploadName)) {
            $imageName = $type.'_'.$cookieObj['rid'];
           $saveDir = _PS_ROOT_DIR_ . '/modules/vendor/views/img/vendor_store/';
            $filename = $uploadName["name"];
            $ext = Tools::substr(strrchr($filename, "."), 1);
            if (in_array(Tools::strtolower($uploadName["type"]), array('image/jpeg', 'image/gif', 'image/png'))) {
                $filename = $imageName.'.'.$ext;
                if (move_uploaded_file($uploadName["tmp_name"], $saveDir . $filename)) {
                    return $vendorCore->getPSBaseUrl().'modules/vendor/views/img/vendor_store/'.$filename;
                } else {
                    return "";
                }
            }
        } else {
            return "";
        }
    }
    public function getStoreRating($storeId)
    {
        $arr = array();
        if ($result = Db::getInstance()->getRow('SELECT CAST(SUM(pc.grade)/COUNT(pc.grade) AS DECIMAL(15,2)) as rating,COUNT(pc.grade) as total_rating FROM '._DB_PREFIX_.'product_comment pc inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = pc.id_product where pvr.restaurant_id='.(int)$storeId)) {
            $arr = array($result['rating'],$result['total_rating']);
        }
        return $arr;
    }
}
