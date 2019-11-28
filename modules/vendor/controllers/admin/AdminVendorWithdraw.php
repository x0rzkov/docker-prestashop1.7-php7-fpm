<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class AdminVendorWithdrawController extends ModuleAdminController
{
    private $sMsg = '';
   
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $base_url = $this->getPSBaseUrl();
        $ps__base_url = $base_url.'modules/restaurant/';
        $this->context->smarty->assign(array(
            'withdraws'         => $this->getWithdrawPendingList(),
            'ps__base_url'      => $ps__base_url,
            'base_url'          => $base_url,
            'xMsg'              => $this->sMsg,
            'token'             => $this->token
        ));
    }
    public function initContent()
    {
        parent::initContent();
        $smarty = $this->context->smarty;
        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'vendor/views/templates/admin/withdrawlist.tpl');
        $this->context->smarty->assign(array(
            'content'    => $this->content.$content
        ));
    }
    public function postProcess()
    {
        if (Tools::isSubmit('chkVendorEmail')) {
           
		   /*
		   * @get value from setting page
		   */
		    $paypal_mode = 'sandbox';
			$paypal_subject = 'Affiliate Monthly Payment';
			$paypal_currency = 'USD';
			$paypal_api_username = '';
			$paypal_api_password = '';
			$paypal_api_signature = '';
		    if(Configuration::get('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE') && Configuration::get('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE') != '') {
		   		$paypal_mode = Configuration::get('PS_VENDOR_PAYPAL_ENVIRONMENT_MODE');
		    }
			if(Configuration::get('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT') && Configuration::get('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT') != '') {
		   		$paypal_subject = Configuration::get('PS_VENDOR_PAYPAL_PAYMENT_SUBJECT');
		    }
			if(Configuration::get('PS_VENDOR_PAYPAL_CURRENCY_MODE') && Configuration::get('PS_VENDOR_PAYPAL_CURRENCY_MODE') != '') {
		   		$paypal_currency = Configuration::get('PS_VENDOR_PAYPAL_CURRENCY_MODE');
		    }
			if(Configuration::get('PS_VENDOR_PAYPAL_API_USER_NAME') && Configuration::get('PS_VENDOR_PAYPAL_API_USER_NAME') != '') {
		   		$paypal_api_username = Configuration::get('PS_VENDOR_PAYPAL_API_USER_NAME');
		    }
			if(Configuration::get('PS_VENDOR_PAYPAL_API_PASSWORD') && Configuration::get('PS_VENDOR_PAYPAL_API_PASSWORD') != '') {
		   		$paypal_api_password = Configuration::get('PS_VENDOR_PAYPAL_API_PASSWORD');
		    }
			if(Configuration::get('PS_VENDOR_PAYPAL_API_SIGNATURE') && Configuration::get('PS_VENDOR_PAYPAL_API_SIGNATURE') != '') {
		   		$paypal_api_signature = Configuration::get('PS_VENDOR_PAYPAL_API_SIGNATURE');
		    }
		   
		    //environment setup
            define('ENVIRONMENT', $paypal_mode);  // 'sandbox' or 'beta-sandbox' or 'live'
            //receivers payment common subject
            define('EMAIL_SUBJECT', $paypal_subject); // you can set any subject
            //receiver Type
            define('RECEIVER_TYPE', 'EmailAddress'); // 'EmailAddress' or 'PhoneNumber' or 'UserID'
            //currency setup
            define('CURRENCY', paypal_currency); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            //API version
            define('VERSION', '90.0');
            //sender API Credentials
            define('API_USERNAME', $paypal_api_username); //sender api username
            define('API_PASSWORD', $paypal_api_password); //sender api password
            define('API_SIGNATURE', $paypal_api_signature); //sender api signature
            // Set request-specific fields from config file.
            $environment = ENVIRONMENT;
            $version = VERSION;
            $emailSubject = urlencode(EMAIL_SUBJECT);
            $receiverType = urlencode(RECEIVER_TYPE);
            $currency = urlencode(CURRENCY);
            $receivers = array();
            foreach (Tools::getValue('chkVendorEmail') as $receiver) {
                //here set multiple receivers array object
                if ($receiver != '') {
                    $str = explode('~', $receiver);
                    $receiver_email = trim($str[0]);
                    $receiver_money = trim($str[1]);
                    $wid = trim($str[2]);
                    if ($receiver_email != '' && $receiver_money != '' && (float)$receiver_money > 0) {
                        $rid = $this->uniqueId(13);
                        $receivers[] = array(
                            'receiverEmail' => $receiver_email,
                            'amount' => number_format($receiver_money, 1, '.', ''),
                            'uniqueID' => $rid, // 13 chars max
                            'note' => EMAIL_SUBJECT
                        );
                        $this->updateWithdrawRequestId($wid, $rid);
                    }
                }
            }
            if (count($receivers) > 0) {
                $receiversLenght = count($receivers);
                $nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
                $receiversArray = array();
                for ($i = 0; $i < $receiversLenght; $i++) {
                    $receiversArray[$i] = $receivers[$i];
                }
                foreach ($receiversArray as $i => $receiverData) {
                    $receiverEmail = urlencode($receiverData['receiverEmail']);
                    $amount = urlencode($receiverData['amount']);
                    $uniqueID = urlencode($receiverData['uniqueID']);
                    $note = urlencode($receiverData['note']);
                    $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
                }
                $httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr, $environment, $version, API_USERNAME, API_PASSWORD, API_SIGNATURE);
                if ("SUCCESS" == Tools::strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == Tools::strtoupper($httpParsedResponseAr["ACK"])) {
                    foreach (Tools::getValue('chkVendorEmail') as $receiver) {
                        if ($receiver != '') {
                            $str = explode('~', $receiver);
                            $receiver_email = trim($str[0]);
                            $receiver_money = trim($str[1]);
                            $wid = trim($str[2]);
                            if ($receiver_email != '' && $receiver_money != '' && (float)$receiver_money > 0) {
                                $this->updatePaypalSuccessId($wid, $httpParsedResponseAr["CORRELATIONID"]);
                            }
                            //echo 'MassPay Completed Successfully: ' . $httpParsedResponseAr;
                            //print_r($httpParsedResponseAr);
                        }
                    }
                    $ps__base_url = $this->getPSBaseUrl().'modules/restaurant/';
        			$vendor_list_link  = 'index.php?controller=AdminVendorWithdraw&m=s';
					$vendor_list_link .= '&token='.Tools::getAdminTokenLite('AdminVendorWithdraw');
					Tools::redirect($vendor_list_link);
					//$this->sMsg = "Sent Payment Successfully";
                } else {
                    print_r($httpParsedResponseAr);
                    die();
                }
            }
        }
    }
    private function PPHttpPost($methodName_, $nvpStr_, $environment, $version, $API_UserName, $API_Password, $API_Signature)
    {
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if ("sandbox" === $environment || "beta-sandbox" === $environment) {
            $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
        }
        $version = urlencode($version);
        
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        
        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
        
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&".$nvpStr_);
        
        // Get response from the server.
        $httpResponse = curl_exec($ch);
        if (!$httpResponse) {
            echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
        }
        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        return $httpParsedResponseAr;
    }
    private function uniqueId($l = 8)
    {
        $better_token = md5(uniqid(rand(), true));
        $rem = Tools::strlen($better_token)-$l;
        $unique_code = Tools::substr($better_token, 0, -$rem);
        $uniqueid = $unique_code;
        return $uniqueid;
    }
    private function updatePaypalSuccessId($wid, $sid)
    {
        $query = 'UPDATE '._DB_PREFIX_.'payment_withdraw SET paypal_success_id = "'.(string)$sid.'",status=1 WHERE wid = '.(int)$wid;
        Db::getInstance()->Execute($query);
    }
    private function updateWithdrawRequestId($wid, $rid)
    {
        $query = 'UPDATE '._DB_PREFIX_.'payment_withdraw SET paypal_request_id = "'.(string)$rid.'" WHERE wid = '.(int)$wid;
        Db::getInstance()->Execute($query);
    }
    public function getWithdrawPendingList()
    {
        $lists = array();
        $sql = 'SELECT *,w.status as payment_status FROM '._DB_PREFIX_.'payment_withdraw w inner join '._DB_PREFIX_.'restrurent_registration rr on rr.rid = w.rid where w.status = 0 order by w.wid desc';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $status = 'Pending';
                if ($row['payment_status'] == 1) {
                    $status = 'Success';
                }
                $x = strtotime($row['added_date']);
                $added_date = date('d-m-Y', $x);
                $lists[] = array(
                    'wid'               => $row['wid'],
                    'paypal_email'      => $row['paypal_email'],
                    'email'             => $row['email'],
                    'store_name'        => $row['firstname'].' '.$row['lastname'],
                    'added_date'        => $added_date,
                    'success_date'      => $row['success_date'],
                    'send_money'        => $row['amount'],
                    'amount'            => Tools::displayPrice($row['amount']),
                    'tamount'           => Tools::displayPrice($this->getVendorTotalSale($row['rid'])),
                    'status'            => $status
                );
            }
        }
        return $lists;
    }
    private function getVendorTotalSale($storeId)
    {
        $amount = number_format(0, 2, '.', '');
        $sell_amt = 0;
		$withdraw_amt = 0;
		$result = Db::getInstance()->getRow('SELECT sum(vendor_amount) as vendor_amount FROM '._DB_PREFIX_.'vendor_payment_info where rid = '.(int)$storeId);
        if (count($result) > 0 && $result['vendor_amount'] != '') {
            //$amount = number_format($result['vendor_amount'], 1, '.', '');
			$sell_amt = $result['vendor_amount'];
        }
		
		$result2 = Db::getInstance()->getRow('SELECT sum(amount) as withdraw_amount FROM '._DB_PREFIX_.'payment_withdraw where rid = '.(int)$storeId);
        if (count($result2) > 0 && $result2['withdraw_amount'] != '') {
            //$amount = number_format($result['vendor_amount'], 1, '.', '');
			$withdraw_amt = $result2['withdraw_amount'];
        }
		
		$total = (float)$sell_amt - (float)$withdraw_amt;
		$amount = number_format($total, 1, '.', '');
        return $amount;
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
}
