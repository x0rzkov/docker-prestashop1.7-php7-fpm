<?php
/**
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*/

class VendorVendorForgotpasswordModuleFrontController extends ModuleFrontController
{
    
    public $msg = '';
    public $msg_class = '';
    public $store_status = 0;
    public $firstname = '';
    public $lastname = '';
    
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        parent::initContent();
        $ps_base_url = $this->getPSBaseUrl();
        $slang = $this->context->language->id;
        $this->context->smarty->assign(array(
            'slang'              => $slang,
            'ps_base_url'        => $ps_base_url,
            'msgs'               => $this->msg,
            'msg_class'          => $this->msg_class
        ));
        //$this->setTemplate('vendorforgotpass.tpl');
		$this->setTemplate('module:vendor/views/templates/front/vendorforgotpass.tpl');
    }
    public function postProcess()
    {
        if (Tools::isSubmit('email')) {
            if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email)) {
                $this->msg = 'Invalid email address.';
                $this->msg_class = 'danger';
            } else {
                if (!$this->checkEmailAddressAlreadyExist(Tools::getValue('email'))) {
                    $this->msg = 'There is no account registered for this email address.';
                    $this->msg_class = 'danger';
                } elseif ((int)$this->store_status === 0) {
                    $this->msg = 'Your account is inactive so contact with admin thanks.';
                    $this->msg_class = 'danger';
                } else {
                    //success now send email
                    $password = $this->generatePassword(10);
                    $this->updateOldPassword($password, Tools::getValue('email'));
                    $mail_params = array(
                        '{email}' => Tools::getValue('email'),
                        '{lastname}' => $this->lastname,
                        '{firstname}' => $this->firstname,
                        '{passwd}' => $password
                    );
                    if (Mail::Send($this->context->language->id, 'password_query', Mail::l('Password query confirmation'), $mail_params, Tools::getValue('email'), $this->firstname.' '.$this->lastname)) {
                        $this->msg = 'Your password has been successfully reset and a confirmation has been sent to your email address';
                        $this->msg_class = 'success';
                    } else {
                        $this->msg = 'An error occurred while sending the email.';
                        $this->msg_class = 'danger';
                    }
                }
            }
        }
    }
    private function generatePassword($_len)
    {
        $_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';
        $_alphaCaps  = Tools::strtoupper($_alphaSmall);
        $_numerics   = '1234567890';
        $_specialChars = '`~!@#$%^&*()-_=+]}[{;:,<.>/?\'"\|';
        $_container = $_alphaSmall.$_alphaCaps.$_numerics.$_specialChars;
        $password = '';
        for ($i = 0; $i < $_len; $i++) {
            $_rand = rand(0, Tools::strlen($_container) - 1);
            $password .= Tools::substr($_container, $_rand, 1);
        }
        return $password;
    }
    private function checkEmailAddressAlreadyExist($email)
    {
        $result = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'restrurent_registration where email="'.pSQL($email).'"');
        if (count($result) > 0 && $result['email'] != '' && trim($result['email']) === trim($email)) {
            $this->store_status = $result['status'];
            $this->firstname = $result['firstname'];
            $this->lastname = $result['lastname'];
            return true;
        }
        return false;
    }
    private function updateOldPassword($password, $email)
    {
        $query = 'UPDATE '._DB_PREFIX_.'restrurent_registration SET password="'.pSQL($password).'" WHERE email="'.pSQL($email).'"';
        Db::getInstance()->Execute($query);
    }
    private function getPSBaseUrl()
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
}
