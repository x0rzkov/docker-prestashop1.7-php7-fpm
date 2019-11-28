<?php

/**

* DISCLAIMER

*

*  @author    SolverCircle



*  @copyright 2007-2016 SolverCircle

*  @license   http://opensource.org/licenses/LGPL-2.1

*  International Registered Trademark & Property of SolverCircle

*/





class AdminVendorRequestedProductListController extends ModuleAdminController

{

    private $sMsg = '';

   

    public function __construct()

    {

        $this->bootstrap = true;

        parent::__construct();

        $restrurent_list = array();

        $base_url = $this->getPSBaseUrl();

        $ps__base_url = $base_url.'modules/vendor/';

        

		if (Tools::getValue('hdnVendorEmail')) {

			$shop_email = strval(Configuration::get('PS_SHOP_EMAIL'));

			$shop_name = strval(Configuration::get('PS_SHOP_NAME'));

			

			/*

			* @ post data

			*/

			$to_email = Tools::getValue('hdnVendorEmail');

			$subject = Tools::getValue('txtEmailSubject');

			$message = Tools::getValue('txtEmailBody');

			$id_product = Tools::getValue('hdnVendorProduct');

			$delete_product = false;

			if (Tools::getValue('chkDeleteProduct') == 'on') {

				$delete_product = true;

			}

			/*

			*/

			if($delete_product){

				$productObj = new Product();

        		$productObj->id = $id_product;

        		$productObj->delete();

			}

			/*

			* @ send email to vendor

			*/

			$this->sendEmail($to_email, $shop_email, $subject, $message, $shop_name);

			$this->sMsg = 'Send email to vendor succsesfully';

		}

		

		

		$this->context->smarty->assign(array(

            'requested_product_list'   	=> $this->getAllStoreRequestedProducts(),

            'ps__base_url'      		=> $ps__base_url,

            'base_url'          		=> $base_url,

            'xMsg'              		=> $this->sMsg,

            'token'             		=> $this->token

        ));

    }

	

    public function initContent()

    {

        parent::initContent();

        $smarty = $this->context->smarty;

        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'vendor/views/templates/admin/productlist.tpl');

        $this->context->smarty->assign(array(

            'content'    => $this->content.$content

        ));

    }

	

	private function getAllStoreRequestedProducts()

    {

		$link = new Link();

        $products = array();

        $query='SELECT *,rr.store_name FROM '._DB_PREFIX_.'product p inner join '._DB_PREFIX_.'product_vendor_relationship pvr on pvr.id_product = p.id_product inner join '._DB_PREFIX_.'restrurent_registration rr on rr.rid = pvr.restaurant_id where p.active = 0 order by p.id_product desc';

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

					's_email'       => $row['email'],

					's_name'        => $row['store_name'],

                    'price'         => Tools::displayPrice($product->price),

                    'href'          => $link->getProductLink($product),

                    'stock'         => StockAvailable::getQuantityAvailableByProduct($row['id_product']),

                    'img'           => $this->getSiteProtocal().$imagePath,

					'date_add'    	=> $row['date_add'],

					'status'    	=> $row['active'],

                    'category_name' => $category->name[1]

                );

            }

        }

		

        return $products;

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

