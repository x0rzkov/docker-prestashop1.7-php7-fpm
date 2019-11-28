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



class VendorVendorRestaurantDetailsModuleFrontController extends ModuleFrontController

{

    public function init()

    {

        parent::init();

        $this->display_column_left = false;

		$this->display_column_right = false;

        $this->display_column_right = false;

    }

    public function initContent()

    {

        parent::initContent();

        

        $vendorCore = new ScVendor;

		$vendorCore->storeId = Tools::getValue('rid');

		

		$ps_base_url = $this->getPSBaseUrl();

        $slang = $this->context->language->id;

        $store_info = $this->getAllActiveStoreInformation(Tools::getValue('rid'));

        

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

			if (!empty($store_style->vendor_color_8)) {

                $vendor_color_10 = $store_style->vendor_color_10;

            }

			if (!empty($store_style->vendor_color_8)) {

                $vendor_color_11 = $store_style->vendor_color_11;

            }

            

        }

		

		/*

		* @rating

		*/

		$rating = array();

		$vendorRatings = $vendorCore->getStoreRatingInfo();

		$rating = $vendorRatings[0];

        $rating_image = $vendorRatings[1];

        $total_rating = $vendorRatings[2];



		$link = new Link;

		$store_ratings = $this->getStoreRatings(Tools::getValue('rid'));

		$rating_array = array();

        foreach ($store_ratings as $ratingx) {

            $rating_array[] = array(

                'product_name'      => $ratingx['name'],

				'href'      		=> $link->getProductLink($ratingx['id_product']),

                'customer_name'     => $ratingx['customer_name'],

                'grade'             => number_format($ratingx['grade'], 2, '.', ' '),

                'content'           => $ratingx['content'],

                'date_add'          => date('Y/m/d h:i:s a',strtotime($ratingx['date_add'])),

				'rating_image'		=> $vendorCore->getStoreRatingInfoFrontPanel($ratingx['grade'])



            );

        }

		

        $link = new Link;

		

        $this->context->smarty->assign(array(

            'slang'              				=> $slang,

            'ps_base_url'        				=> $ps_base_url,

            'store_content'     				=> html_entity_decode(base64_decode($store_info['store_content'])),

			'store_content_about_us'     		=> html_entity_decode(base64_decode($store_info['store_content_about_us'])),

            'store'             				=> $store_info,

            'products'         					=> $this->getStoreAllProductList(),

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

			'vendor_color_11'                	=> $vendor_color_11,

			'cart_link'							=> $link->getPageLink('order'),

			'store_rating'       				=> $rating,

            'total_rating'       				=> $total_rating,

            'rating_image'       				=> $rating_image,

			'rating_array'						=> $rating_array

        ));

        

		$this->context->controller->registerJavascript('fancy-loader-again', 'modules/vendor/views/js/custom.js', ['position' => 'bottom', 'priority' => 150]);

		

		$this->setTemplate('module:vendor/views/templates/front/vendorrestaurantdetails.tpl');

		//$this->setTemplate('vendorrestaurantdetails.tpl');

    }

	

	

	private function getProductNewTagByProductId($id_product) {

		$id_lang = $this->context->language->id;

		$sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock` out_of_stock, pl.`description`, pl.`description_short`,

			pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,

			p.`ean13`, p.`upc`, image_shop.`id_image` id_image, il.`legend`,

			DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",

			INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'

				DAY)) > 0 AS new

		FROM `'._DB_PREFIX_.'product` p

		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (

			p.`id_product` = pl.`id_product`

			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'

		)

		'.Shop::addSqlAssociation('product', 'p').'

		LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop

			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$this->context->shop->id.')

		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$this->context->language->id.')

		'.Product::sqlStock('p', 0).'

		WHERE p.id_product = '.(int)$id_product;



		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

		if (!$row) {

        	return 0;

        }

		else{

			return $row['new'];

		}

	}

	

    private function getAllActiveStoreInformation($rid)

    {

        $query='SELECT * FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id where rr.status=1 and rr.approved = 1 and rr.rid='.(int)$rid;

        return Db::getInstance()->getRow($query);

    }

    

    private function geCurrentStoreCategoryAndProduct()

    {

        $products = array();

        $cat_collection = array();

        $query = "SELECT c.id_category,cl.name from " . _DB_PREFIX_ . "category c inner join " . _DB_PREFIX_ . "category_lang cl on cl.id_category = c.id_category where c.active = 1 and cl.id_shop = ".(int)Context::getContext()->shop->id." and cl.id_lang = ".(int)$this->context->language->id." order by cl.name ASC";

        $catlists = Db::getInstance()->ExecuteS($query);

        foreach ($catlists as $cat) {

            if (Tools::strtolower($cat['name']) != 'root') {

                $products = $this->getProductsByCategory($cat['id_category']);

                if (count($products) > 0) {

                    $cat_collection[] = array(

                        'id_category'   => $cat['id_category'],

                        'name'          => $cat['name'],

                        'products'      => $products

                    );

                }

            }

        }

        return $cat_collection;

    }

    public function getProductVariation($id_product, $restaurant_id)

    {

        $product_variation = array();

        if (!empty($restaurant_id) && !empty($id_product)) {

            $result_pro = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_variation_product_group` cvpg inner join `'._DB_PREFIX_.'custom_variation_group` cvg on cvg.cvgid = cvpg.cvgid WHERE cvpg.id_product = "'.(int)$id_product.'" AND cvpg.restaurant_id = "'.(int)$restaurant_id.'" ORDER BY cvpg.cvgid ASC');

            foreach ($result_pro as $resx) {

                $result_var_val = Db::getInstance()->executeS('SELECT cvv.vid, cvv.variation_name, cvpv.price FROM `'._DB_PREFIX_.'custom_variation_product_value` cvpv inner join `'._DB_PREFIX_.'custom_variation_value` cvv on cvv.vid = cvpv.cvid WHERE cvpv.cvpgid = '.(int)$resx['cvpgid'].' AND cvpv.id_product = '.(int)$id_product.' AND cvpv.restaurant_id = '.(int)$restaurant_id.' ORDER BY cvv.sort_order ASC');

                $product_variation[] = array(

                    'cvgid'             => $resx['cvgid'],

                    'group_name'        => $resx['group_name'],

                    'type'              => $resx['attubute_type'],

                    'required'          => $resx['required'],

                    'childs'            => $result_var_val

                );

            }

        }

        return $product_variation;

    }

	

	private function getStoreAllProductList(){

		$rid = Tools::getValue('rid');

		$link = new Link;

		$product_list = array();

		if (!empty($rid) && $rid != '') {

			$sql = 'SELECT *, pvr.restaurant_id FROM `'._DB_PREFIX_.'product` p INNER JOIN '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product WHERE pvr.restaurant_id = "'.(int)$rid.'"';

			$products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            foreach ($products as $product) {

				if ((int)$product['active'] == 1 && (int)$rid == (int)$product['restaurant_id']) {

					$productx = new Product($product['id_product'], false, $this->context->language->id);

					//$price = Product::getPriceStatic($product['id_product']);

					$price_info = $this->getProductReductionPrice($product['id_product'], $productx->price);

					$price = $productx->price;

					$reduction = 0;

					if(isset($price_info['price']) && $price_info['price'] != ''){

						$price = $price_info['price'];

					}

					if(isset($price_info['reduction']) && $price_info['reduction'] != ''){

						$reduction = $price_info['reduction'];

					}

					$image = Image::getCover($product['id_product']);

					$imagePath = $link->getImageLink($productx->link_rewrite, $image['id_image'], ImageType::getFormattedName('home'));

					//$products_variation = $this->getProductVariation($product['id_product'], $rid);

					$product_list[] = array(

						'id_product'    => $product['id_product'],

						'name'          => $productx->name,

						'image'         => $this->siteProtocol().$imagePath,

						'active'        => $productx->active,

						'new'			=> $this->getProductNewTagByProductId($product['id_product']),

						'price'         => Tools::displayPrice($price),

						's_desc'    	=> $productx->description_short,

						'old_price'		=> Tools::displayPrice($productx->price),

						'reduction'		=> $reduction,

						//'options'     => $products_variation,

						'link'          => $link->getProductLink($product['id_product'])

					);	

				}

			}

		}

		return $product_list;

	}

	

	public function getProductReductionPrice($id_product, $price) {

		$array = array();

		if (!empty($id_product)) {

			if ($row = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` = '.(int)$id_product)) {

				$array['reduction_type'] = $row ['reduction_type'];

				if($row ['reduction_type'] == 'percentage') {

					$x1 = (float)$row ['reduction'] * (int)100;

					$array['reduction'] = $x1;

					$array['price'] = (float)((float)$price - (float)((float)((float)$price * (float)$x1) / (int)100));

				} 

			}

		}

		return $array;

	}

    

    public function getProductsByCategory($id_category)

    {

        $rid = Tools::getValue('rid');

        $link = new Link;

        $only_active = true;

        $order_by = "name";

        $order_way = "ASC";

        $order_by_prefix = "pl";

        $product_list = array();

        $rid = Tools::getValue("rid");

        if (!empty($rid) && $rid != '') {

            $sql = 'SELECT p.*, product_shop.*, pl.* ,pvr.restaurant_id, m.`name` AS manufacturer_name, s.`name` AS supplier_name

                    FROM `'._DB_PREFIX_.'product` p

                    '.Shop::addSqlAssociation('product', 'p').'

                    LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')

                    LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)

                    LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.

                    ($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'

                    INNER JOIN '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product

                    WHERE pl.`id_lang` = '.(int)$this->context->language->id.

                        ($id_category ? ' AND c.`id_category` = '.(int)$id_category : '').

                        ($only_active ? ' AND product_shop.`active` = 1' : '').'

                    ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way);

            $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            foreach ($products as $product) {

                if ((int)$product['active'] == 1 && (int)$rid == (int)$product['restaurant_id']) {

                    $productx = new Product($product['id_product'], false, $this->context->language->id);

                    $image = Image::getCover($product['id_product']);

                    $imagePath = $link->getImageLink($productx->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));

                    $products_variation = $this->getProductVariation($product['id_product'], $rid);

                    $product_list[] = array(

                        'id_product'    => $product['id_product'],

                        'name'          => $product['name'],

                        'image'         => $this->siteProtocol().$imagePath,

                        'active'        => $product['active'],

                        'price'         => Tools::displayPrice($productx->price),

                        'short_desc'    => $product['description_short'],

                        'options'       => $products_variation,

                        'link'          => $link->getProductLink($product['id_product'])

                    );

                }

            }

        }

        return $product_list;

    }

    /*************************************************************/

    public function getPSBaseUrl()

    {

        $base_url = '';

        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');

        if (count($result) > 0) {

            $domian = $this->siteProtocol().$result['domain'];

            $physical_uri = $result['physical_uri'];

            $base_url = $domian . $physical_uri;

        }

        return $base_url;

    }

    public function siteProtocol()

    {

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {

            return 'https://';

        } else {

            return 'http://';

        }

    }

	public function getStoreRatings($storeId)

    {

        $query = 'SELECT * FROM '._DB_PREFIX_.'product_comment pc inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = pc.id_product inner join '._DB_PREFIX_.'product_lang pl on pl.id_product = pc.id_product where pvr.restaurant_id='.(int)$storeId;

        $results = Db::getInstance()->ExecuteS($query);

        return $results;

    }

}

