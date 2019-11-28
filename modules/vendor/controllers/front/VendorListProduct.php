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
class VendorVendorListProductModuleFrontController extends ModuleFrontController
{
    private $success_msg = 'none';
    
    private $_msg = '';
    
    private $delete_msg = 'none';
    
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
		
		
		$product_approve = Configuration::get('PS_VENDOR_PRODUCT_APPROVE');
		
		$vendorCore = new ScVendor;
		
        if (Tools::getValue('token') && Tools::getValue('token') == 's') {
            $this->success_msg = 'block';
            if ($product_approve == 1) {
				$this->_msg = 'Added Item Successfully and waiting for admin approval';
			} else {
				$this->_msg = 'Added Item Successfully.';
			}
        } elseif (Tools::getValue('token') && Tools::getValue('token') == 'u') {
            $this->success_msg = 'block';
            $this->_msg = 'Updated Item Successfully.';
        }
        
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        
        if (!empty(Tools::getValue('xdelid'))) {
            $this->deleteVendorProduct(Tools::getValue('xdelid'));
            $this->delete_msg = 'block';
        }
        
		//load products
		$products = $this->getAllStoreProducts($cookieObj['rid']);
		
        $slang = $this->context->language->id;
        /*call vendor core class*/
		$ps_base_url = $vendorCore->getPSBaseUrl();
		$vendorCore->storeId = $cookieObj['rid'];
		$vendorRatings = $vendorCore->getStoreRatingInfo();
        
		$rating = $vendorRatings[0];
        $rating_image = $vendorRatings[1];
        $total_rating = $vendorRatings[2];
        /*End*/
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'products'           => $products,
            'msgx'               => $this->success_msg,
            'msgxx'              => $this->delete_msg,
            'welcome_name'       => $welcome_name,
            'xmsg'               => $this->_msg,
            'products'           => $products,
            'store_rating'       => $rating,
            'total_rating'       => $total_rating,
            'rating_image'       => $rating_image,
			'product_approve'	 => $product_approve,
            'xqty'               => $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
        //$this->setTemplate('vendorlistproduct.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorlistproduct.tpl');
    }
    private function getAllStoreProducts($storeId)
    {
        $vendorCore = new ScVendor;
		$link = new Link();
        $products = array();
        $query='SELECT * FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product where pvr.restaurant_id='.(int)$storeId . ' order by p.id_product desc';
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                $product = new Product($row['id_product'], false, $this->context->language->id);
				$category = new Category($row['id_category_default']);
                $id_product = $row['id_product'];
                $image = Image::getCover($id_product);
                $imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], ImageType::getFormattedName('home'));
			    $products[] = array(
                    'id_product'    => $row['id_product'],
                    'name'          => $product->name,
					'status'		=> $row['active'],
                    'price'         => Tools::displayPrice($product->price),
                    'href'          => $link->getProductLink($product),
                    'stock'         => StockAvailable::getQuantityAvailableByProduct($row['id_product']),
                    'img'           => $vendorCore->getSiteProtocal().$imagePath,
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
    private function deleteVendorProduct($product_id)
    {
        $productObj = new Product();
        $productObj->id = $product_id;
        $productObj->delete();
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