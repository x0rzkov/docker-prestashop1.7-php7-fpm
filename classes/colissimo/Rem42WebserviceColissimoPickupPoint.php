<?php

/**
 * @param $class
 *
 * @throws Exception
 */
function load_webservice_class_colissimo($class)
{
    if (is_dir(_PS_MODULE_DIR_ . '/colissimo')) {
        if (file_exists(_PS_MODULE_DIR_ . '/colissimo/classes/' . $class . '.php') !== false) {
            require_once _PS_MODULE_DIR_ . '/colissimo/classes/' . $class . '.php';
        }
    } else {
        throw new Exception("Colissimo module doesn't exist, please install it !");
    }
}

spl_autoload_register('load_webservice_class_colissimo');

class Rem42WebserviceColissimoPickupPoint
{
    /**
     * @var WebserviceRequest
     */
    protected $input;
    /**
     * @var WebserviceOutputBuilder
     */
    protected $output;
    /**
     * @var WebserviceReturn
     */
    protected $webserviceReturn;
    /**
     * @var array
     */
    protected $filter;
    /**
     * @var array
     */
    protected $validFilter = ['id_order'];

    /**
     * Rem42WebserviceInvoice constructor.
     *
     * @param WebserviceRequest       $input
     * @param WebserviceOutputBuilder $output
     */
    public function __construct(WebserviceRequest $input, WebserviceOutputBuilder $output)
    {
        $this->input            = $input;
        $this->output           = $output;
        $this->webserviceReturn = new WebserviceReturn();
    }

    /**
     * @param WebserviceRequest       $input
     * @param WebserviceOutputBuilder $output
     *
     * @return WebserviceReturn
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function load(WebserviceRequest $input, WebserviceOutputBuilder $output)
    {
        $self = new self($input, $output);
        return $self->execute();
    }

    /**
     * @return WebserviceReturn
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function execute() {

        $this->manageFilters();
        $depth = 0;
        $this->webserviceReturn->isString = true;
        if(strlen($this->input->urlSegment[3]) > 0) {
            $address = WSColissimoPickupPoint::getWSPickupPointByIdOrder($this->input->urlSegment[3]);
            $this->webserviceReturn->string .= $this->output->renderEntity($address, null);
        } else {
            foreach ($this->filter['id_order'] as $idOrder) {
                $address = WSColissimoPickupPoint::getWSPickupPointByIdOrder($idOrder);

                if ($this->input->fieldsToDisplay === 'minimum') {
                    $this->webserviceReturn->string .= $this->output->renderEntityMinimum($address, $depth);
                } else {
                    $this->webserviceReturn->string .= $this->output->renderEntity($address, $depth);
                }
            }
        }
        return $this->webserviceReturn;
    }

    protected function manageFilters()
    {
        if (isset($this->input->urlFragments["filter"])) {
            foreach ($this->input->urlFragments["filter"] as $urlFragment => $value) {
                if (in_array($urlFragment, $this->validFilter)) {
                    $value                      = str_replace(['[', ']'], '', $value);
                    $value                      = explode('|', $value);
                    $this->filter[$urlFragment] = $value;
                } else {
                    $this->input->setErrorDidYouMean(400, 'This filter does not exist for this linked table', $urlFragment, $this->validFilter, 1);
                }
            }
        }
        if(isset($this->input->urlSegment[3]) && $this->input->urlSegment[3] > 0){
            $this->filter['id_order'] = [$this->input->urlSegment[3]];
            if(!isset($this->input->urlFragments['display'])){
                $this->input->urlFragments['display'] = 'full';
            }
        }
        $this->input->setFieldsToDisplay();
        $this->output->setFieldsToDisplay($this->input->fieldsToDisplay);
    }
}
