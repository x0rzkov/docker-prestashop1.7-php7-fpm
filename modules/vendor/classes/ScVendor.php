<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

/**
*int ps info
*/

require_once(_PS_ROOT_DIR_.'/config/config.inc.php');
require_once(_PS_ROOT_DIR_.'/init.php');

class ScVendor
{
	public $storeId = 0;
	
	/**
	* @get store rating details
	*/
	public function getStoreRatingInfo() {
		
		$ps_base_url = $this->getPSBaseUrl();
		
		$total_rating = '0';
		$rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.png';
		$rating = '0.00';
		
		$store_rating = $this->getStoreRating();
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
        }
        return array($rating, $rating_image, $total_rating);
	}
	
	/**
	* @get store rating details
	*/
	public function getStoreRatingInfoFrontPanel($rating) {
		
		$ps_base_url = $this->getPSBaseUrl();
		
		$rating_image = $ps_base_url.'modules/vendor/views/img/ratings/0.png';
		
	    if ($rating > 0) {
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
        }
        return $rating_image;
	}
	
	private function getStoreRating()
    {
        $arr = array();
        if ($result = Db::getInstance()->getRow('SELECT CAST(SUM(pc.grade)/COUNT(pc.grade) AS DECIMAL(15,2)) as rating,COUNT(pc.grade) as total_rating FROM '._DB_PREFIX_.'product_comment pc inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = pc.id_product where pvr.restaurant_id='.(int)$this->storeId)) {
            $arr = array($result['rating'],$result['total_rating']);
        }
        return $arr;
    }
	
	/**
	*get all country list from preastashop database
	*/
	public function getAllCountryList()
    {
        $context = Context::getContext();
		$country = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'country_lang where id_lang='.(int)$context->language->id . ' order by name asc';
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
	/**
	*get all state list from preastashop database
	*/
	public function getAllStateList($country_id)
    {
        $states = array();
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'state WHERE id_country = '.(int)$country_id . ' order by name ASC';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $states[] = array(
                    'id_state'      => $row['id_state'],
                    'name'          => $row['name']
                );
            }
        }
        return $states;
    }
	/**
	*get store info from database
	*/
	public function getStoreInfo()
    {
        $context = Context::getContext();
		$cookieObj = unserialize($context->cookie->vendorObj);
        return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'restrurent_registration  WHERE rid="'.(int)$cookieObj['rid'].'"');
    }
	/**
	*get vendor quantity alert
	*/
	public function lessQuantityAlert($storeId)
    {
        $qtyx = 0;
        $query = 'SELECT * FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product inner join '._DB_PREFIX_.'product_lang pl on pl.id_product = p.id_product where pvr.restaurant_id='.(int)$storeId;
        if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $result) {
                $qty = StockAvailable::getQuantityAvailableByProduct($result['id_product']);
                if ((int)$qty <= 5) {
                    $qtyx += 1;
                }
            }
        }
        if ($qtyx > 1) {
            return $qtyx.' '.'Products';
        } else {
            return $qtyx.' '.'Product';
        }
    }
	
	public function getPSBaseUrl()
    {
        $base_url = '';
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'shop_url');
        if (count($result) > 0) {
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
        } else {
            $protocol = 'http://';
        }
        return $protocol;
    }
	
	public function sendEmail($to, $from, $subject, $message, $store_name) {
		
		$headers  = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message  = '<html><body>';
		$message .= '<h1>'.$store_name.'</h1>';
		$message .= '<div>'.$message.'</div>';
		$message .= '</body></html>';
		
		mail($to, $subject, $message, $headers);
	}

}
