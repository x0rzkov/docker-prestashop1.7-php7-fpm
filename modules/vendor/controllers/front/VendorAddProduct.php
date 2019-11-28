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

class VendorVendorAddProductModuleFrontController extends ModuleFrontController
{
    /*
	* @default values
	*/
    private $success_msg = 'none';
    private $base_url = '';
    private $id_product = 0;
    private $name = '';
    private $long_desc = '';
    private $short_desc = '';
    private $active = 0;
    private $category = '';
    private $price = '0.00';
    private $seo_title = '';
    private $seo_desc = '';
    private $seo_friendly_url = '';
    private $quantity = 0;
	private $condition = 'new';
	private $product_type = 0;
    private $image = '';
    private $special_price = '0.00';
    private $visibility = 'both'; //configure this product dont show nowhere
    private $xToken = 'add';
	private $download_filename = '';
	private $no_download = 0;
    
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
		
		$vendorCore = new ScVendor;
		
		//ajax process
		if (Tools::getValue("method") && Tools::getValue("method") == 'getComInformation') {
			$productComb = new Product(Tools::getValue('id_product'), false, $this->context->language->id);
			$combinations = $productComb->getAttributeCombinationsById(Tools::getValue('id_product_attribute'), $this->context->language->id);
			foreach ($combinations as $key => $combination) {
				$combinations[$key]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
			}
			die(Tools::jsonEncode($combinations));
		}
		
		//ajax remove process
		if (Tools::getValue("method") && Tools::getValue("method") == 'removeCombination') {
			if (!Combination::isFeatureActive()) {
				return;
			}
			$id_product = (int)Tools::getValue('id_product');
			$id_product_attribute = (int)Tools::getValue('id_product_attribute');
			if ($id_product && Validate::isUnsignedId($id_product) && Validate::isLoadedObject($product = new Product($id_product))) {
				if (($depends_on_stock = StockAvailable::dependsOnStock($id_product)) && StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute)) {
					$json = array(
						'status' => 'error',
						'message'=> $this->l('It is not possible to delete a combination while it still has some quantities in the Advanced Stock Management. You must delete its stock first.')
					);
				} else {
					$product->deleteAttributeCombination((int)$id_product_attribute);
					$product->checkDefaultAttributes();
					Tools::clearColorListCache((int)$product->id);
					if (!$product->hasAttributes()) {
						$product->cache_default_attribute = 0;
						$product->update();
					} else {
						Product::updateDefaultAttribute($id_product);
					}

					if ($depends_on_stock && !Stock::deleteStockByIds($id_product, $id_product_attribute)) {
						$json = array(
							'status' => 'error',
							'message'=> $this->l('Error while deleting the stock')
						);
					} else {
						$json = array(
							'status' => 'ok',
							'message'=> 'Product deleted successfully',
							'id_product_attribute' => (int)$id_product_attribute
						);
					}
				}
			} else {
				$json = array(
					'status' => 'error',
					'message'=> $this->l('You cannot delete this attribute.')
				);
			}
	
			die(Tools::jsonEncode($json));
		}
		
		
        $cookieObj = unserialize($this->context->cookie->vendorObj);
        $welcome_name = $cookieObj['firstname'];
        $productImages = array();
        $variation = array();
        $product_variation = array();
		$admin_approve = 1;
		$comb_array = array();
		
		if(Configuration::get('PS_VENDOR_PRODUCT_APPROVE')){
			$admin_approve  = Configuration::get('PS_VENDOR_PRODUCT_APPROVE');
		}
        
        if (Tools::getValue('edit_id_product') && Tools::getValue('edit_id_product') != '') {
            $productObj = new Product(Tools::getValue('edit_id_product'), false, $this->context->language->id);
			$comb_array = $this->renderListAttributes($productObj, $this->context->currency);
			$link = new Link();
            if (!empty($productObj)) {
                $new_product = new ProductCore(Tools::getValue('edit_id_product'), false, $this->context->language->id);
				$images = $new_product->getImages((int)self::$cookie->id_lang);
			    foreach ($images as $image) {
                    $productImages[] = array(
                        'href'    =>$vendorCore->getSiteProtocal().$link->getImageLink($new_product->link_rewrite, $image['id_image'], ImageType::getFormatedName('home')),
                        'coverId' =>$image['id_image']
                    );
                }
                $image = Image::getCover(Tools::getValue('edit_id_product'));
                $imagePath = $link->getImageLink($productObj->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));
                $this->name = $productObj->name;
                $this->long_desc = $productObj->description;
				$this->condition = $productObj->condition;
                $this->short_desc = $productObj->description_short;
                $this->active = $productObj->active;
                $this->category = $productObj->id_category_default;
                $this->price = $productObj->price;
                $this->seo_title = $productObj->meta_title;
                $this->seo_desc = $productObj->meta_description;
                $this->seo_friendly_url = $productObj->link_rewrite;
                $this->quantity = StockAvailable::getQuantityAvailableByProduct(Tools::getValue('edit_id_product'));
                $this->image = $imagePath;
				$this->product_type = $productObj->is_virtual;
                $this->special_price = $this->getDiscountPrice(Tools::getValue('edit_id_product'));
				
				//get product virtula information
				if ($productObj->is_virtual != 0) {
					$data = $this->getProductDownloadInfoByProductId(Tools::getValue('edit_id_product'));
					if(!empty($data)){
						$this->download_filename = $data['display_filename'];
						$this->no_download = $data['nb_downloadable'];
					}
				}
            }
            //here load product custom variation
            /*$result_pro = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_variation_product_group` WHERE id_product = "'.(int)Tools::getValue('edit_id_product').'" AND restaurant_id = "'.(int)$cookieObj['rid'].'" ORDER BY cvgid ASC');
            
            foreach ($result_pro as $resultx) {
                $result_pro_val = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_variation_product_value` WHERE cvpgid = '.(int)$resultx['cvpgid'].' ORDER BY cvid ASC');
                foreach ($result_pro_val as $res) {
                    $product_variation[] = array(
                        'cvgid'     => $resultx['cvgid'],
                        'vid'       => $res['cvid'],
                        'price'     => $res['price']
                    );
                }
            }*/
            $this->xToken = 'update';
            $this->id_product = Tools::getValue('edit_id_product');
        }
        
        $cat_collection = array();
        $cat_query = "SELECT c.id_category,cl.name from " . _DB_PREFIX_ . "category c inner join " . _DB_PREFIX_ . "category_lang cl on cl.id_category = c.id_category where c.active = 1 and cl.id_shop = ".(int)Context::getContext()->shop->id." and cl.id_lang = ".(int)$this->context->language->id." order by cl.name ASC";
        $catlists = Db::getInstance()->ExecuteS($cat_query);
        foreach ($catlists as $cat) {
            if (Tools::strtolower($cat['name']) != 'root') {
                $cat_collection[] = array(
                    'id_category'   => $cat['id_category'],
                    'name'          => $cat['name'],
                );
            }
        }
		
		$product_attr_group = array();
		$attr_groups = AttributeGroup::getAttributesGroups($this->context->language->id);
		if (!empty($attr_groups)) {
			foreach ($attr_groups as $attr_group) {
				$product_attr_group[] = array(
					'id_attribute_group'	=> $attr_group['id_attribute_group'],
					'name'					=> $attr_group['name'],
					'group_type'			=> $attr_group['group_type']
				);
			}
		}
		
        //load custom variation
        /*$results = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_variation_group` ORDER BY group_name ASC');
        foreach ($results as $result) {
            $resultss = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'custom_variation_value` WHERE cvgid = '.(int)$result['cvgid'].' ORDER BY variation_name ASC');
            $variation[] = array(
                'cvgid'         => $result['cvgid'],
                'group_name'    => $result['group_name'],
                'attubute_type' => $result['attubute_type'],
                'attr_vals'     => $resultss
            );
        }*/
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
            'slang'              	=> $slang,
            'ps_base_url'        	=> $ps_base_url,
            'country_list'       	=> $this->getAllCountryList(),
            'msgx'               	=> $this->success_msg,
            'cat_collection'     	=> $cat_collection,
            'attr_groups'			=> $product_attr_group,
            'welcome_name'       	=> $welcome_name,
            'p_name'             	=> $this->name,
            'p_con'              	=> $this->condition,
			'p_long'             	=> $this->long_desc,
            'p_short'            	=> $this->short_desc,
            'active'             	=> $this->active,
            'category'           	=> $this->category,
            'price'              	=> number_format($this->price, '2', '.', ''),
            'seo_title'          	=> $this->seo_title,
            'seo_desc'          	=> $this->seo_desc,
            'seo_friendly_url'   	=> $this->seo_friendly_url,
            'quantity'           	=> $this->quantity,
			'comArrays'				=> $comb_array,
			'product_type'       	=> $this->product_type,
            'image'              	=> ($this->image !="") ? $vendorCore->getSiteProtocal().$this->image : "",
            'special_price'      	=> $this->special_price,
            'gallerys'           	=> $productImages,
            'tokenx'             	=> $this->xToken,
            'product_id'         	=> $this->id_product,
            'pro_var'            	=> $product_variation,
            'store_rating'       	=> $rating,
            'total_rating'       	=> $total_rating,
            'rating_image'       	=> $rating_image,
			'download_filename'	 	=> $this->download_filename,
			'no_download'	 		=> $this->no_download,
			'admin_approve'			=> $admin_approve,
            'xqty'               	=> $vendorCore->lessQuantityAlert($cookieObj['rid'])
        ));
		
        //$this->setTemplate('vendoraddproduct.tpl');
		//$this->xTestImage();
		
		$this->setTemplate('module:vendor/views/templates/front/vendoraddproduct.tpl');
    }
	
	public function renderListAttributes($product, $currency)
    {
        $comb_array = array();
		if ($product->id) {
            /* Build attributes combinations */
            $combinations = $product->getAttributeCombinations($this->context->language->id);
            $groups = array();
            if (is_array($combinations)) {
                $combination_images = $product->getCombinationImages($this->context->language->id);
                foreach ($combinations as $k => $combination) {
                    $price_to_convert = Tools::convertPrice($combination['price'], $currency);
                    $price = Tools::displayPrice($price_to_convert, $currency);

                    $comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                    $comb_array[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
                    $comb_array[$combination['id_product_attribute']]['wholesale_price'] = $combination['wholesale_price'];
                    $comb_array[$combination['id_product_attribute']]['price'] = $price;
                    $comb_array[$combination['id_product_attribute']]['weight'] = $combination['weight'].Configuration::get('PS_WEIGHT_UNIT');
                    $comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
                    $comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
                    $comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
                    $comb_array[$combination['id_product_attribute']]['upc'] = $combination['upc'];
                    $comb_array[$combination['id_product_attribute']]['id_image'] = isset($combination_images[$combination['id_product_attribute']][0]['id_image']) ? $combination_images[$combination['id_product_attribute']][0]['id_image'] : 0;
                    $comb_array[$combination['id_product_attribute']]['available_date'] = strftime($combination['available_date']);
                    $comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
                    if ($combination['is_color_group']) {
                        $groups[$combination['id_attribute_group']] = $combination['group_name'];
                    }
                }
            }

            if (isset($comb_array)) {
                foreach ($comb_array as $id_product_attribute => $product_attribute) {
                    $list = '';

                    /* In order to keep the same attributes order */
                    asort($product_attribute['attributes']);

                    foreach ($product_attribute['attributes'] as $attribute) {
                        $list .= $attribute[0].' - '.$attribute[1].', ';
                    }

                    $list = rtrim($list, ', ');
                    $comb_array[$id_product_attribute]['image'] = $product_attribute['id_image'] ? new Image($product_attribute['id_image']) : false;
                    $comb_array[$id_product_attribute]['available_date'] = $product_attribute['available_date'] != 0 ? date('Y-m-d', strtotime($product_attribute['available_date'])) : '0000-00-00';
                    $comb_array[$id_product_attribute]['attributes'] = $list;
                    $comb_array[$id_product_attribute]['name'] = $list;
                }
            }
        }
		return $comb_array;
    }
	
	
    private function getDiscountPrice($product_id)
    {
        $discount = '0%';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'specific_price where id_product='.(int)$product_id);
        if (count($result) > 0 && (float)$result['reduction'] > 0) {
            $discount = (float)((float)$result['reduction'] * (int)100);
        }
        return $discount;
    }
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            if (empty($this->context->cookie->vendorObj)) {
                Tools::redirect('index.php?fc=module&module=vendor&controller=VendorRegistration');
                die();
            }


            $languages = Language::getLanguages();

            $vendorCore = new ScVendor;
			$product_status = 0;
            $cookieObj = unserialize($this->context->cookie->vendorObj);
            $rid = (int)$cookieObj['rid'];
            $this->base_url = $vendorCore->getPSBaseUrl();
            $product_name = Tools::getValue('txtItemName');
			$condition = Tools::getValue('condition');
			$product_type = Tools::getValue('type_product');
            $product_seo_url = Tools::getValue('txtSeoUrl');
            $product_meta_title = Tools::getValue('txtMetaTitle');
            $product_meta_desc = Tools::getValue('txtMetaDesc');
            $product_short_desc = Tools::getValue('txtShortDesc');
            $product_long_desc = Tools::getValue('txtLongDesc');
            $product_price = Tools::getValue('txtItemPrice');
            $product_quantity = Tools::getValue('txtItemQuantity');
      		if(Tools::getValue('txtItemStatus')) {
				$product_status = Tools::getValue('txtItemStatus');
			}
			$product_category = Tools::getValue('ddlcategoryId');
            $product_discount = Tools::getValue('txtItemDiscount');
            $this->id_product = Tools::getValue('product_id');
            $product_main_image = '';
            $product_image_gallery = '';
            if (isset($_FILES['productimage']) && $_FILES['productimage']['size'] > 1) {
                $product_main_image = $_FILES["productimage"];
            }
            if (isset($_FILES['filesToUpload']) && $_FILES['filesToUpload']['size'] > 1) {
                $product_image_gallery = $_FILES['filesToUpload'];
            }
			
            //set product object
            $productObj = new Product();
            //$productObj->name = array((int)Configuration::get('PS_LANG_DEFAULT') => pSQL($product_name));

            foreach($languages as $lang) {
                $productObj->name[$lang['id_lang']] = pSQL($product_name);
                $productObj->description[$lang['id_lang']] = pSQL(description);
                $productObj->link_rewrite[$lang['id_lang']] = pSQL($product_seo_url);
                $productObj->meta_title[$lang['id_lang']] = pSQL($product_meta_title);
                $productObj->meta_description[$lang['id_lang']] = pSQL($product_meta_desc);
            }

            $productObj->condition = pSQL($condition);
			$productObj->is_virtual = (int)($product_type);
            //$productObj->link_rewrite = array((int)Configuration::get('PS_LANG_DEFAULT') =>  pSQL($product_seo_url));
            $productObj->id_category_default = (int)$product_category;
            $productObj->category = array($product_category);
            //$productObj->meta_title = array((int)Configuration::get('PS_LANG_DEFAULT') => pSQL($product_meta_title));
            //$productObj->meta_description = array((int)Configuration::get('PS_LANG_DEFAULT') => pSQL($product_meta_desc));
            //$productObj->description = pSQL($product_long_desc);
            $productObj->description_short = pSQL($product_short_desc);
            $productObj->redirect_type = '404';
            $productObj->visibility = $this->visibility;
            //$productObj->wholesale_price = (float)$product_price;
            //$productObj->price = (float)$product_price;
            //$productObj->specificPrice = (float)$product_price;
            $productObj->price = (float)$product_price;
            $productObj->id_tax_rules_group = 0;
            $productObj->wholesale_price = 0;
            $productObj->additional_shipping_cost = 0;
            $productObj->ecotax = 0;
            $productObj->show_price = 1;
            $productObj->on_sale = 0;
			
            //$productObj->online_only = 1;
            $productObj->active = (int)$product_status;
            if (Tools::getValue('SubmitCreate') == 'add') {
				$productObj->add();
                //return added product id
                $this->id_product = $productObj->id;
                if (!empty($this->id_product)) {
                    Db::getInstance()->insert('product_vendor_relationship', array(
                        'id_product'            => (int)$this->id_product,
                        'restaurant_id'         => (int)$rid,
                        'id_shop'               => (int)Context::getContext()->shop->id
                    ));
                }
				$this->saveAttributeCombination();
            } else {
                $productObj->id = $this->id_product;
                $productObj->update();
				$this->saveAttributeCombination();
            }
            $productObj->addToCategories(array($product_category));
            //$productObj->updateCategories($productObj->category, true);
            //here we add product quantity
            StockAvailable::setQuantity((int)$this->id_product, '', (int)$product_quantity, (int)Configuration::get('PS_SHOP_DEFAULT'));
            
            //add product main image here
            if (!empty($product_main_image) && $product_main_image != '') {
                $url = $this->uploadFunction($product_main_image); //here we get the image url after upload
			    if ($url != 'error') {
                    $this->addProductImage($url, true);
                }
            }/* elseif (Tools::getValue('hdnItemImage')) {
                $this->addProductImage(Tools::getValue('hdnItemImage'),true);
            }*/
            //here we add product image gallery
            if ($product_image_gallery != '' && is_array($product_image_gallery)) {
                $this->addProductGalleryImage($product_image_gallery);
            } //else {
            /*foreach(Tools::getValue('hdnGallery') as $imgGallery){
                $this->addProductImage($imgGallery,false);
            }*/
            //}
            //here we add product specific price
            if ($product_discount != '' && (float)$product_discount > 0) {
                $spObj = new SpecificPrice();
                $spObj->id_product = $this->id_product;
                $spObj->from_quantity = 1;
                $spObj->from_quantity = 1;
                $spObj->reduction = (float)((float)$product_discount / (int)100);
                $spObj->reduction_tax = 1;
                $spObj->id_shop = 0;
                $spObj->id_currency = 0;
                $spObj->id_country = 0;
                $spObj->id_group = 0;
                $spObj->id_customer = 0;
                $spObj->price = '-1.000000';
                $spObj->from = '0000-00-00 00:00:00';
                $spObj->to = '0000-00-00 00:00:00';
                $spObj->reduction_type = 'percentage';
                if (Tools::getValue('SubmitCreate') == 'add') {
                    $spObj->add();
                } else {
					Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "specific_price WHERE id_product = '".(int)$this->id_product."'");
					$spObj->add();
					//$spObj->update();
                }
            }
			
			//here we add product download info
			if(!empty($this->id_product)){
				if ($product_type == 1) {
					//get new filename
					$new_filename = ProductDownload::getNewFilename();
					$d_status = '';
					if (isset($_FILES['uploadDownloadFile']) && $_FILES['uploadDownloadFile']['size'] > 1) {
						$d_status = $this->uploadDownloadFile($_FILES['uploadDownloadFile'], $new_filename);
					}
					else{
						$new_filename = Tools::getValue('downloadFileId');
					}
					//
					$pd = new ProductDownload();
					$pd->id_product = $this->id_product;
					$pd->display_filename = Tools::getValue('txtDisplayFileName');
					$pd->filename = $new_filename;
					$pd->nb_downloadable = Tools::getValue('txtNoDownload');
					//
					$download_pro_info = $this->getProductDownloadInfoByProductId($this->id_product);
					if (!empty($download_pro_info)) {
						unlink(_PS_DOWNLOAD_DIR_.$download_pro_info['filename']);
						Db::getInstance()->delete('product_download', 'id_product_download = '.(int)$download_pro_info['id_product_download']);
					}
					//
					$pd->add();
				 }
			 }
			 
			 /*
			 * @add product combination here
			 */
			 if(!empty($this->id_product)){
			 	$pc = new Combination();
			 	$pc->id_product = $this->id_product;
				
			 }
			 
			 /**/
            /*Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "custom_variation_product_group WHERE id_product = '".(int)$this->id_product."' AND restaurant_id = '".(int)$rid."'");
            Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "custom_variation_product_value WHERE id_product = '".(int)$this->id_product."' AND restaurant_id = '".(int)$rid."'");
            $options = Tools::getValue('options');
            foreach ($options as $key => $value) {
                if (isset($value['option'])) {
                    Db::getInstance()->insert('custom_variation_product_group', array(
                        'cvgid'                     => (int)$key,
                        'id_product'                => (int)$this->id_product,
                        'restaurant_id'             => (int)$rid
                    ));
                    $group_id = Db::getInstance()->Insert_ID();
                    if (!empty($group_id) && $group_id > 0) {
                        if (isset($value['option'])) {
                            $i=0;
                            foreach ($value['option'] as $val) {
                                $price = 0.00;
                                if (isset($value['price'][$i]) && (float)$value['price'][$i] > 0) {
                                    $price = $value['price'][$i];
                                }
                                Db::getInstance()->insert('custom_variation_product_value', array(
                                    'cvpgid'                    => (int)$group_id,
                                    'cvid'                      => (int)$val,
                                    'id_product'                => (int)$this->id_product,
                                    'price'                     => (float)$price,
                                    'restaurant_id'             => (int)$rid
                                ));
                                $i++;
                            }
                        }
                        
                    }
                }
            }*/
            if (Tools::getValue('SubmitCreate') == 'add') {
                Tools::redirect('index.php?fc=module&module=vendor&controller=VendorListProduct&token=s');
            } else {
                Tools::redirect('index.php?fc=module&module=vendor&controller=VendorListProduct&token=u');
            }
        }
    }

	protected function isProductFieldUpdated($field, $id_lang = null)
    {
        // Cache this condition to improve performances
        static $is_activated = null;
        if (is_null($is_activated)) {
            $is_activated = Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP && $this->id_object;
        }

        if (!$is_activated) {
            return true;
        }

        $def = ObjectModel::getDefinition($this->object);
        if (!$this->object->isMultiShopField($field) && is_null($id_lang) && isset($def['fields'][$field])) {
            return true;
        }

        if (is_null($id_lang)) {
            return !empty($_POST['multishop_check'][$field]);
        } else {
            return !empty($_POST['multishop_check'][$field][$id_lang]);
        }
    }
	
	public function saveAttributeCombination()
    {
        // Don't process if the combination fields have not been submitted
		
		/*if (!Combination::isFeatureActive() || !Tools::getValue('attribute_combination_list')) {
            return;
        }*/
		
        //if (Validate::isLoadedObject($product = $this->object)) {
			
			$product = new Product();
			$product->id = $this->id_product;
			
			$attr_qty = 0;
			if (isset($_POST['attribute_stock_available'])) {
				$attr_qty = $_POST['attribute_stock_available'];
			}
			
			if ($this->isProductFieldUpdated('attribute_price') && (!Tools::getIsset('attribute_price') || Tools::getIsset('attribute_price') == null)) {
                $this->errors[] = Tools::displayError('The price attribute is required.');
            }
            //if (!Tools::getIsset('attribute_combination_list') || Tools::isEmpty(Tools::getValue('attribute_combination_list'))) {
			if (isset($_POST['attribute_combination_list']) && empty($_POST['attribute_combination_list'])) {
                $this->errors[] = Tools::displayError('You must add at least one attribute.');
            }

            $array_checks = array(
                'reference' => 'isReference',
                'supplier_reference' => 'isReference',
                'location' => 'isReference',
                'ean13' => 'isEan13',
                'upc' => 'isUpc',
                'wholesale_price' => 'isPrice',
                'price' => 'isPrice',
                'ecotax' => 'isPrice',
                'quantity' => 'isInt',
                'weight' => 'isUnsignedFloat',
                'unit_price_impact' => 'isPrice',
                'default_on' => 'isBool',
                'minimal_quantity' => 'isUnsignedInt',
                'available_date' => 'isDateFormat'
            );
            foreach ($array_checks as $property => $check) {
                if (Tools::getValue('attribute_'.$property) !== false && !call_user_func(array('Validate', $check), Tools::getValue('attribute_'.$property))) {
                    $this->errors[] = sprintf(Tools::displayError('Field %s is not valid'), $property);
                }
            }
			 
            if (!count($this->errors)) {
			    if (!isset($_POST['attribute_wholesale_price'])) {
                    $_POST['attribute_wholesale_price'] = 0;
                }
                if (!isset($_POST['attribute_price_impact'])) {
                    $_POST['attribute_price_impact'] = 0;
                }
                if (!isset($_POST['attribute_weight_impact'])) {
                    $_POST['attribute_weight_impact'] = 0;
                }
                if (!isset($_POST['attribute_ecotax'])) {
                    $_POST['attribute_ecotax'] = 0;
                }
                if (Tools::getValue('attribute_default')) {
                    $product->deleteDefaultAttributes();
                }

                // Change existing one
                if (($id_product_attribute = (int)Tools::getValue('id_product_attribute')) || ($id_product_attribute = $product->productAttributeExists($_POST['attribute_combination_list'], false, null, true, true))) {
                    //if ($this->tabAccess['edit'] === '1') {
						if ($this->isProductFieldUpdated('available_date_attribute') && (Tools::getValue('available_date_attribute') != '' &&!Validate::isDateFormat(Tools::getValue('available_date_attribute')))) {
                            $this->errors[] = Tools::displayError('Invalid date format.');
                        } else {
                            $product->updateAttribute((int)$id_product_attribute,
                                $this->isProductFieldUpdated('attribute_wholesale_price') ? Tools::getValue('attribute_wholesale_price') : null,
                                $this->isProductFieldUpdated('attribute_price_impact') ? Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact') : null,
                                $this->isProductFieldUpdated('attribute_weight_impact') ? Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact') : null,
                                $this->isProductFieldUpdated('attribute_unit_impact') ? Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact') : null,
                                $this->isProductFieldUpdated('attribute_ecotax') ? Tools::getValue('attribute_ecotax') : null,
                                Tools::getValue('id_image_attr'),
                                Tools::getValue('attribute_reference'),
                                Tools::getValue('attribute_ean13'),
                                $this->isProductFieldUpdated('attribute_default') ? Tools::getValue('attribute_default') : null,
                                Tools::getValue('attribute_location'),
                                Tools::getValue('attribute_upc'),
                                $this->isProductFieldUpdated('attribute_minimal_quantity') ? Tools::getValue('attribute_minimal_quantity') : null,
                                $this->isProductFieldUpdated('available_date_attribute') ? Tools::getValue('available_date_attribute') : null, false);
                            StockAvailable::setProductDependsOnStock((int)$product->id, $product->depends_on_stock, null, (int)$id_product_attribute);
                            StockAvailable::setProductOutOfStock((int)$product->id, $product->out_of_stock, null, (int)$id_product_attribute);
                        }
						StockAvailable::setQuantity($this->id_product, (int)Tools::getValue('id_product_attribute'), (int)$attr_qty);
                    /*} else {
                        $this->errors[] = Tools::displayError('You do not have permission to add this.');
                    }*/
                } else {
                    // Add new
					//if ($this->tabAccess['add'] === '1') {
					if ($product->productAttributeExists($_POST['attribute_combination_list'])) {
						$this->errors[] = Tools::displayError('This combination already exists.');
					} else {
						$id_product_attribute = $product->addCombinationEntity(
							Tools::getValue('attribute_wholesale_price'),
							Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact'),
							Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact'),
							Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact'),
							Tools::getValue('attribute_ecotax'),
							0,
							Tools::getValue('id_image_attr'),
							Tools::getValue('attribute_reference'),
							null,
							Tools::getValue('attribute_ean13'),
							Tools::getValue('attribute_default'),
							Tools::getValue('attribute_location'),
							Tools::getValue('attribute_upc'),
							Tools::getValue('attribute_minimal_quantity'),
							array(),
							Tools::getValue('available_date_attribute')
						);
						StockAvailable::setProductDependsOnStock((int)$this->id_product, $product->depends_on_stock, null, (int)$id_product_attribute);
						StockAvailable::setProductOutOfStock((int)$this->id_product, $product->out_of_stock, null, (int)$id_product_attribute);
						StockAvailable::setQuantity($this->id_product, (int)$id_product_attribute, (int)$attr_qty);
					}
                    /*} else {
                        $this->errors[] = Tools::displayError('You do not have permission to').'<hr>'.Tools::displayError('edit here.');
                    }*/
                }
                if (!count($this->errors)) {
				    $combination = new Combination((int)$id_product_attribute);
                    $combination->setAttributes($_POST['attribute_combination_list']);

                    // images could be deleted before
                    $id_images = Tools::getValue('id_image_attr');
                    if (!empty($id_images)) {
                        $combination->setImages($id_images);
                    }

                    $product->checkDefaultAttributes();
                    if (Tools::getValue('attribute_default')) {
                        $product->updateDefaultAttribute((int)$this->id_product);
                        if (isset($id_product_attribute)) {
							$product->cache_default_attribute = (int)$id_product_attribute;
                        }

                        if ($available_date = Tools::getValue('available_date_attribute')) {
                            $product->setAvailableDate($available_date);
                        } else {
                            $product->setAvailableDate();
                        }
                    }
                }
            }
        //}
    }
    public function getAllCountryList()
    {
        $country = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'country_lang where id_lang='.(int)$this->context->language->id . ' order by name asc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $country[] = array(
                    'id_country'    => $row['id_country'],
                    'name'          => $row['name']
                );
            }
        }
        return $country;
    }
    public function addProductImage($url, $cover)
    {
        if (!empty($cover)) {
            Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "image WHERE id_product = '".(int)$this->id_product."'");
            Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "image_shop WHERE id_product = '".(int)$this->id_product."'");
        }
        $shops = Shop::getShops(true, null, true);
        $image = new Image();
        $image->id_product = $this->id_product;
        $image->position = Image::getHighestPosition($this->id_product) + 1;
        $image->cover = $cover; // or false;
        if (($image->validateFields(false, true)) === true &&
        ($image->validateFieldsLang(false, true)) === true && $image->add()) {
            $image->associateTo($shops);
            if (!$this->copyProductImg($this->id_product, $image->id, $url, 'products', true)) {
                $image->delete();
            }
        }
    }
    public function addProductGalleryImage($uploadName)
    {
        for ($i=0; $i<count($uploadName['name']); $i++) {
            $imageName = $this->id_product.'_'.$i;
            $saveDir = _PS_ROOT_DIR_ . '/modules/vendor/views/img/vendor/';
            $filename = $uploadName['name'][$i];
            $ext = Tools::substr(strrchr($filename, "."), 1);
            if (in_array(Tools::strtolower($uploadName["type"][$i]), array('image/jpeg', 'image/gif', 'image/png'))) {
                $filename = $imageName.'.'.$ext;
                if (move_uploaded_file($uploadName["tmp_name"][$i], $saveDir.$filename)) {
                    $this->addProductImage($this->base_url.'modules/vendor/views/img/vendor/'.$filename, false);
                }
            }
        }
    }
    public function uploadFunction($uploadName)
    {
        if (is_array($uploadName)) {
            $imageName = $this->id_product;
            $saveDir = _PS_ROOT_DIR_ . '/modules/vendor/views/img/vendor/';
            $filename = $uploadName["name"];
            $ext = Tools::substr(strrchr($filename, "."), 1);
            if (in_array(Tools::strtolower($uploadName["type"]), array('image/jpeg', 'image/gif', 'image/png'))) {
                $filename = $imageName.'.'.$ext;
                if (move_uploaded_file($uploadName["tmp_name"], $saveDir . $filename)) {
                    return $this->base_url.'modules/vendor/views/img/vendor/'.$filename;
                } else {
                    return "error";
                }
            }
        } else {
            return "error";
        }
    }
	//here we upload only download file
	public function uploadDownloadFile($uploadName,$downloadname)
    {
        if (is_array($uploadName)) {
            //$imageName = $this->id_product;
			$saveDir = _PS_ROOT_DIR_ . '/download/';
            $filename = $uploadName["name"];
            $ext = Tools::substr(strrchr($filename, "."), 1);
            if (Tools::strtolower($ext) == 'zip') {
                //$filename = $imageName.'.'.$ext;
				$filename = $downloadname;
                if (move_uploaded_file($uploadName["tmp_name"], $saveDir . $filename)) {
					return $this->base_url.'download/'.$filename;
                } else {
                    return "error";
                }
            } else {
				return "error";
			}
        } else {
            return "error";
        }
    }
	//public $multiple_value_separator;

	public function xTestImage() {
	
		$cover = true;
		$url = "http://localhost/ps_1711/img/shoe.jpg";
		if (!empty($cover)) {
            Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "image WHERE id_product = '".(int)$this->id_product."'");
            Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "image_shop WHERE id_product = '".(int)$this->id_product."'");
        }
        $shops = Shop::getShops(true, null, true);
        $image = new Image();
        $image->id_product = 9;
        $image->position = Image::getHighestPosition(9) + 1;
        $image->cover = $cover; // or false;
		if (($image->validateFields(false, true)) === true &&
        ($image->validateFieldsLang(false, true)) === true && $image->add()) {
            $image->associateTo($shops);
			if (!$this->copyProductImg(9, $image->id, $url, 'products', true)) {
                $image->delete();
            }
        }
	}
	
	protected static function createMultiLangField($field)
    {
        $res = array();
        foreach (Language::getIDs(false) as $id_lang) {
            $res[$id_lang] = $field;
        }

        return $res;
    }
	
    public function copyProductImg($id_entity, $id_image = null, $url = '', $entity = 'products', $regenerate = true)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
		
        switch ($entity) {
            default:
            case 'products':
                $image_obj = new Image($id_image);
                $path = $image_obj->getPathForCreation();
                break;
            case 'categories':
                $path = _PS_CAT_IMG_DIR_.(int)$id_entity;
                break;
            case 'manufacturers':
                $path = _PS_MANU_IMG_DIR_.(int)$id_entity;
                break;
            case 'suppliers':
                $path = _PS_SUPP_IMG_DIR_.(int)$id_entity;
                break;
            case 'stores':
                $path = _PS_STORE_IMG_DIR_.(int)$id_entity;
                break;
        }

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/'.implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = array();
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once(_PS_TOOL_DIR_.'http_build_url/http_build_url.php');
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
			if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);
                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path.'.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
            $images_types = ImageType::getImagesTypes($entity, true);
			
            if ($regenerate) {
                $previous_path = null;
                $path_infos = array();
                $path_infos[] = array($tgt_width, $tgt_height, $path.'.jpg');
				
				foreach ($images_types as $image_type) {
                    $tmpfile = $this->get_best_path($image_type['width'], $image_type['height'], $path_infos);
                    if (ImageManager::resize(
                        $tmpfile,
                        $path.'-'.stripslashes($image_type['name']).'.jpg',
                        $image_type['width'],
                        $image_type['height'],
                        'jpg',
                        false,
                        $error,
                        $tgt_width,
                        $tgt_height,
                        5,
                        $src_width,
                        $src_height
                    )) {
                        // the last image should not be added in the candidate list if it's bigger than the original image
                        if ($tgt_width <= $src_width && $tgt_height <= $src_height) {
                            $path_infos[] = array($tgt_width, $tgt_height, $path.'-'.stripslashes($image_type['name']).'.jpg');
                        }
                        if ($entity == 'products') {
                            if (is_file(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$id_entity.'.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$id_entity.'.jpg');
                            }
                            if (is_file(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$id_entity.'_'.(int)Context::getContext()->shop->id.'.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$id_entity.'_'.(int)Context::getContext()->shop->id.'.jpg');
                            }
                        }
                    }
                    if (in_array($image_type['id_image_type'], $watermark_types)) {
                        Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                    }
                }
            }
        } else {
            @unlink($orig_tmpfile);
            return false;
        }
        unlink($orig_tmpfile);
        return true;
    }
	public function get_best_path($tgt_width, $tgt_height, $path_infos)
    {
        $path_infos = array_reverse($path_infos);
        $path = '';
        foreach ($path_infos as $path_info) {
            list($width, $height, $path) = $path_info;
            if ($width >= $tgt_width && $height >= $tgt_height) {
                return $path;
            }
        }
        return $path;
    }
	public function getProductDownloadInfoByProductId($id_product) {
		return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_download` WHERE `id_product` = '.(int)$id_product);
	}
}
