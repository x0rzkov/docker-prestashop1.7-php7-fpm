<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class VendorVendorRestaurantListModuleFrontController extends ModuleFrontController
{
 
    private $restaurent_list = array();
    
    private $filter_top_text = "All Vendor List";
    
    private $query_text = "";
    
    private $temp_array = array();
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        parent::initContent();
        
        $ps_base_url = $this->getPSBaseUrl();
        $slang = $this->context->language->id;
        $this->setMedia();
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'query_text'         => $this->query_text,
            'filter_top_text'    => html_entity_decode($this->filter_top_text),
            'stores'             => $this->restaurent_list
        ));
        //$this->setTemplate('vendorrestaurantlist.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorrestaurantlist.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitFilterBox')) {
            //$results = array();
            $filter_data = Tools::getValue('txtRestaurantSearch');
            if ($filter_data != '') {
                /*if (count($this->filterQueryProcess('zipcode', $filter_data)) > 0) {
                    $this->restaurent_list = $this->temp_array;
                } elseif (count($this->filterQueryProcess('firstname', $filter_data)) > 0) {
                    $this->restaurent_list = $this->temp_array;
                } elseif (count($this->filterQueryProcess('lastname', $filter_data)) > 0) {
                    $this->restaurent_list = $this->temp_array;
                } elseif (count($this->filterQueryProcess('state_id', $this->getStateNameByStateId($filter_data))) > 0) {
                    $this->restaurent_list = $this->temp_array;
                }*/
				if (count($this->filterQueryProcess($filter_data)) > 0) {
					$this->restaurent_list = $this->temp_array;
				}
				
                $this->filter_top_text = 'Vendor Filter By "'  . $filter_data . '"';
                $this->query_text = $filter_data;
            }
        } else {
            $this->restaurent_list = $this->getAllActiveStoreInformation();
        }
    }
    private function filterQueryProcess($value)
    {
        $filter_result = array();
        $ps_base_url = $this->getPSBaseUrl();
		if ($value != '') {
            $query='SELECT * FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id where rr.status=1 and rr.approved = 1 and (firstname LIKE "%'.(string)$value.'%" OR lastname LIKE "%'.(string)$value.'%" OR zipcode LIKE "%'.(string)$value.'%" OR state_id LIKE "%'.(string)$value.'%") order by rr.store_name asc';
			if ($results = Db::getInstance()->ExecuteS($query)) {
                foreach ($results as $row) {
                    
					/*rating work here*/
                /*$rating = '0.00';
                $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.png';
                $total_rating = '0';
                $store_rating = $this->getStoreRating($row['rid']);
                if (count($store_rating) > 0) {
                    $rating = $store_rating[0];
                    if ($store_rating[1] > 1) {
                        $total_rating = $store_rating[1].' ' . 'ratings';
                    } else {
                        $total_rating = $store_rating[1].' ' . 'rating';
                    }
                    //
                    if ((float)$rating > 0 && (float)$rating < 0.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.25.png';
                    } elseif ((float)$rating > 0.25 && (float)$rating < 0.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.5.png';
                    } elseif ((float)$rating > 0.5 && (float)$rating < 1) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.75.png';
                    } elseif ((float)$rating > 0.75 && (float)$rating <= 1) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.0.png';
                    } elseif ((float)$rating > 1 && (float)$rating < 1.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.25.png';
                    } elseif ((float)$rating > 1.25 && (float)$rating < 1.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.5.png';
                    } elseif ((float)$rating > 1.5 && (float)$rating < 2) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.75.png';
                    } elseif ((float)$rating > 1.75 && (float)$rating <= 2) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.0.png';
                    } elseif ((float)$rating > 2 && (float)$rating < 2.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.25.png';
                    } elseif ((float)$rating > 2.25 && (float)$rating < 2.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.5.png';
                    } elseif ((float)$rating > 2.5 && (float)$rating < 3) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.75.png';
                    } elseif ((float)$rating > 2.75 && (float)$rating <= 3) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.0.png';
                    } elseif ((float)$rating > 3 && (float)$rating < 3.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.25.png';
                    } elseif ((float)$rating > 3.25 && (float)$rating < 3.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.5.png';
                    } elseif ((float)$rating > 3.5 && (float)$rating < 4) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.75.png';
                    } elseif ((float)$rating > 3.75 && (float)$rating <= 4) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.0.png';
                    } elseif ((float)$rating > 4 && (float)$rating < 4.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.25.png';
                    } elseif ((float)$rating > 4.25 && (float)$rating < 4.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.5.png';
                    } elseif ((float)$rating > 4.5 && (float)$rating < 5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.75.png';
                    } elseif ((float)$rating > 4.75 && (float)$rating <= 5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/5.0.png';
                    }
                    
                }*/
                /**/
					
					
					
					$filter_result[] = array(
                        'rid'                   => $row['rid'],
                        'firstname'             => $row['firstname'],
                        'lastname'              => $row['lastname'],
                        'email'                 => $row['email'],
                        'telephone'             => $row['telephone'],
                        'country'               => $row['name'],
                        'store_name'            => $row['store_name'],
                        'store_grid_image'      => $row['store_grid_image'],
                        'store_banner_image'    => $row['store_banner_image'],
                        'schedule'              => html_entity_decode($row['schedule']),
                        'grid_content'          => htmlspecialchars(Tools::substr(base64_decode($row['grid_content']), 0, 160)),
                        'facebook_link'         => $row['facebook_link'],
                        'twitter_link'          => $row['twitter_link'],
                        'google_plus_link'      => $row['google_plus_link'],
						//'store_rating'          => $rating,
                    	//'total_rating'          => $total_rating,
                    	//'rating_image'          => $rating_image
                    );
                }
            }
        }
        $this->temp_array = $filter_result;
        return $filter_result;
    }
    private function getStateNameByStateId($name)
    {
        if ($name != '') {
            $result = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'state WHERE name="'.pSQL($name).'" order by name ASC');
            if (count($result) > 0 && (int)$result['id_state'] > 0) {
                return $result['id_state'];
            }
        }
        return '-99';
    }
    private function getAllActiveStoreInformation()
    {
        
		$this->context->controller->registerJavascript('fancy-loader-again', 'modules/vendor/views/js/custom.js', ['position' => 'bottom', 'priority' => 150]);
		
		$stores = array();
		$ps_base_url = $this->getPSBaseUrl();
        //$query='SELECT * FROM '._DB_PREFIX_.'restrurent_registration rr inner join '._DB_PREFIX_.'country_lang cl on cl.id_country = rr.country_id where rr.status=1 and rr.approved = 1 order by rr.store_name asc';
		$query='SELECT * FROM '._DB_PREFIX_.'restrurent_registration rr where rr.status=1 and rr.approved = 1 order by rr.store_name asc';
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $row) {
                
                /**/
                //////////////////////////////////SEND DATA TO SMARTY////////////////////////////////////
                /*rating work here*/
                /*$rating = '0.00';
                $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.png';
                $total_rating = '0';
                $store_rating = $this->getStoreRating($row['rid']);
                if (count($store_rating) > 0) {
                    $rating = $store_rating[0];
                    if ($store_rating[1] > 1) {
                        $total_rating = $store_rating[1].' ' . 'ratings';
                    } else {
                        $total_rating = $store_rating[1].' ' . 'rating';
                    }
                    //
                    if ((float)$rating > 0 && (float)$rating < 0.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.25.png';
                    } elseif ((float)$rating > 0.25 && (float)$rating < 0.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.5.png';
                    } elseif ((float)$rating > 0.5 && (float)$rating < 1) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.75.png';
                    } elseif ((float)$rating > 0.75 && (float)$rating <= 1) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.0.png';
                    } elseif ((float)$rating > 1 && (float)$rating < 1.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.25.png';
                    } elseif ((float)$rating > 1.25 && (float)$rating < 1.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.5.png';
                    } elseif ((float)$rating > 1.5 && (float)$rating < 2) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/1.75.png';
                    } elseif ((float)$rating > 1.75 && (float)$rating <= 2) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.0.png';
                    } elseif ((float)$rating > 2 && (float)$rating < 2.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.25.png';
                    } elseif ((float)$rating > 2.25 && (float)$rating < 2.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.5.png';
                    } elseif ((float)$rating > 2.5 && (float)$rating < 3) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/2.75.png';
                    } elseif ((float)$rating > 2.75 && (float)$rating <= 3) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.0.png';
                    } elseif ((float)$rating > 3 && (float)$rating < 3.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.25.png';
                    } elseif ((float)$rating > 3.25 && (float)$rating < 3.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.5.png';
                    } elseif ((float)$rating > 3.5 && (float)$rating < 4) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/3.75.png';
                    } elseif ((float)$rating > 3.75 && (float)$rating <= 4) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.0.png';
                    } elseif ((float)$rating > 4 && (float)$rating < 4.5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.25.png';
                    } elseif ((float)$rating > 4.25 && (float)$rating < 4.75) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.5.png';
                    } elseif ((float)$rating > 4.5 && (float)$rating < 5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/4.75.png';
                    } elseif ((float)$rating > 4.75 && (float)$rating <= 5) {
                        $rating_image = $ps_base_url.'modules/vendor/views/img/ratings/5.0.png';
                    }
                    
                }*/
                /**/
                
				$stores[] = array(
                    'rid'                   => $row['rid'],
                    'firstname'             => $row['firstname'],
                    'lastname'              => $row['lastname'],
                    'email'                 => $row['email'],
                    'telephone'             => $row['telephone'],
                    //'country'             => $row['name'],
                    'store_name'            => $row['store_name'],
                    'store_grid_image'      => $row['store_grid_image'],
                    'store_banner_image'    => $row['store_banner_image'],
                    'schedule'              => html_entity_decode(base64_decode($row['schedule'])),
                    'grid_content'          => html_entity_decode(Tools::substr(base64_decode($row['grid_content']), 0, 160)),
                    'facebook_link'         => $row['facebook_link'],
                    'twitter_link'          => $row['twitter_link'],
                    'google_plus_link'      => $row['google_plus_link']
                    //'store_rating'        => $rating,
                    //'total_rating'        => $total_rating,
                    //'rating_image'        => $rating_image
                );
            }
        }

        return $stores;
    }
    public function getSiteProtocal()
    {
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return 'https://';
        } else {
            return 'http://';
        }
    }
    private function getPSBaseUrl()
    {
        $base_url = '';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');
        if (count($result) > 0) {
            $domian = $this->getSiteProtocal() . $result['domain'];
            $physical_uri = $result['physical_uri'];
            $base_url = $domian . $physical_uri;
        }
        return $base_url;
    }
    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_PS_CSS_DIR_.'jquery.fancybox.css', 'screen');
        $this->addJqueryPlugin('fancybox');
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
