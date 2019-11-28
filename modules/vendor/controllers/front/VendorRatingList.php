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

class VendorVendorRatingListModuleFrontController extends ModuleFrontController
{
    
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
            Tools::redirect('index.php?fc=module&module=restaurant&controller=VendorRegistration');
            die();
        }
		
		//init
		$vendorCore = new ScVendor;
		
        if (Tools::getValue('token') && Tools::getValue('token') == 's') {
            $this->success_msg = 'block';
            $this->_msg = 'Added Item Successfully.';
        } elseif (Tools::getValue('token') && Tools::getValue('token') == 'u') {
            $this->success_msg = 'block';
            $this->_msg = 'Updated Item Successfully.';
        }
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        
        
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];
        /*End*/
        $slang = $this->context->language->id;
        $store_ratings = $this->getStoreRatings($cookieObj['rid']);
        $rating_array = array();
        foreach ($store_ratings as $ratingx) {
            $rating_array[] = array(
                'product_name'      =>$ratingx['name'],
                'customer_name'     =>$ratingx['customer_name'],
                'grade'             =>number_format($ratingx['grade'], 2, '.', ' '),
                'content'           =>$ratingx['content'],
                'date_add'          =>$ratingx['date_add']
            );
        }
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'rating_array'       => $rating_array,
            'welcome_name'       => $welcome_name,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        $this->setTemplate('vendorratinglist.tpl');
    }
    public function getStoreRatings($storeId)
    {
        $query = 'SELECT * FROM '._DB_PREFIX_.'product_comment pc inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = pc.id_product inner join '._DB_PREFIX_.'product_lang pl on pl.id_product = pc.id_product where pvr.restaurant_id='.(int)$storeId;
        $results = Db::getInstance()->ExecuteS($query);
        return $results;
    }
}
