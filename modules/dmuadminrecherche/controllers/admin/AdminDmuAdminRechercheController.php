<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL DREAM ME UP
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL DREAM ME UP is strictly forbidden.
 *
 *   .--.
 *   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
 *   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
 *   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
 *        w w w . d r e a m - m e - u p . f r       '
 *
 * @author    Dream me up <prestashop@dream-me-up.fr>
 * @copyright 2007 - 2016 Dream me up
 * @license   All Rights Reserved
 */

class AdminDmuAdminRechercheController extends ModuleAdminController
{
    protected static $pref = null;
    protected $id_shop = null;
    protected $position_column = null;
    protected $return_result_ajax = false;
    protected $nb_warehouse = 0;
    protected $price_display_precision = 2;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->lang = true;
        $this->table = 'product';
        $this->identifier = 'id_product';
        $this->className = 'Product';
        $this->explicitSelect = true;
        $this->_defaultOrderBy = 'a.date_add';
        $this->_defaultOrderWay = 'desc';
        $this->display = 'list';
        $this->list_no_link = true;
        $this->list_simple_header = true;
        $this->update_fields = null;

        parent::__construct();

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts'));
        }

        $this->price_display_precision = Configuration::get('PS_PRICE_DISPLAY_PRECISION');
        if ($this->price_display_precision === false) {
            $this->price_display_precision = 2;
        }

        $config = Configuration::get('DMUADMINRECHERCHE_CONF', null, 0, 0);
        if ($config) {
            self::$pref = unserialize((string)$config);
        }
        $this->list_id = $this->module->name;
        $this->_use_found_rows = false;

        $this->fields_list = array();
        $this->fields_list['status'] = array(
            'title' => $this->l('Status'),
            'filter_key' => 'ds!status',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'type' => 'dmu_status',
            'editable' => true,
            'default' => true
        );
        $this->fields_list['id_product'] = array(
            'title' => $this->l('ID'),
            'filter_key' => 'a!id_product',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'default' => true
        );
        $this->fields_list['image_p'] = array(
            'title' => $this->l('Image'),
            'align' => 'center',
            'link_bo' => 'index.php?controller=AdminProducts&token=' . Tools::getAdminTokenLite('AdminProducts')
                . '&updateproduct&id_product=',
            'link_img_large' => $this->detectImageType(200, 200),
            'type' => 'dmu_image',
            'default' => true
        );
        $this->fields_list['reference'] = array(
            'title' => $this->l('Reference'),
            'filter_key' => 'a!reference',
            'type' => 'dmu_text_input',
            'size' => 100,
            'default' => true
        );
        $this->fields_list['supplier_reference'] = array(
            'title' => $this->l('Supplier Reference'),
            'filter_key' => 'psu!product_supplier_reference',
            'type' => 'dmu_text_input',
            'size' => 100,
            'show_action' => 'combinations',
            'show_action_param' => 'cache_default_attribute',
            'default' => true
        );
        $this->fields_list['name'] = array(
            'title' => $this->l('Name'),
            'filter_key' => 'b!name',
            'type' => 'dmu_text_input',
            'lang' => true,
            'default' => true,
        );
        $this->fields_list['manufacturer'] = array(
            'title' => $this->l('Manufacturer'),
            'filter_key' => 'm!name',
            'type' => 'dmu_list',
            'key' => 'id_manufacturer',
            'default' => true
        );
        $this->fields_list['category'] = array(
            'title' => $this->l('Category'),
            'filter_key' => 'cl!name',
            'type' => 'dmu_list',
            'key' => 'id_category_default',
            'required' => true,
            'default' => true
        );
        if ($this->context->country->display_tax_label && !Tax::excludeTaxeOption()) {
            $this->fields_list['price_ti'] = array(
                'title' => $this->l('Retail price with tax'),
                'type' => 'dmu_text_input',
                'size' => 100,
                'price' => true,
                'align' => 'text-right',
                'default' => true,
                'update' => array('price_te', 'margin', 'price_final')
            );
        }
        $this->fields_list['price_te'] = array(
            'title' =>
                (!$this->context->country->display_tax_label || Tax::excludeTaxeOption()) ? $this->l('Retail price')
                    : $this->l('Pre-tax retail price'),
            'filter_key' => 'ps!price',
            'align' => 'text-right',
            'type' => 'dmu_text_input',
            'price' => true,
            'size' => 100,
            'default' => ($this->context->country->display_tax_label && !Tax::excludeTaxeOption()) ? false : true,
            'update' => array('price_ti', 'margin', 'price_final')
        );
        if (Configuration::get('PS_USE_ECOTAX')) {
            $this->fields_list['ecotax'] = array(
                'title' => $this->l('Ecotax (tax incl.)'),
                'filter_key' => 'ps!ecotax',
                'align' => 'text-right',
                'type' => 'dmu_text_input',
                'price' => true,
                'callback' => 'getEcotax',
                'size' => 100,
                'update' => array('price_te', 'margin')
            );
        }
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $this->fields_list['quantity'] = array(
                'title' => $this->l('Quantity'),
                'filter_key' => 'sa!quantity',
                'type' => 'dmu_text_input',
                'size' => 100,
                'show_action' => 'combinations',
                'show_action_param' => 'cache_default_attribute',
                'align' => 'text-right',
                'class' => 'fixed-width-sm',
                'badge_danger' => true,
                'default' => true
            );
        }
        $this->fields_list['wholesale_price'] = array(
            'title' => $this->l('Wholesale Price'),
            'filter_key' => 'ps!wholesale_price',
            'type' => 'dmu_text_input',
            'size' => 100,
            'price' => true,
            'align' => 'text-right',
            'update' => array('margin')
        );
        $this->fields_list['additional_shipping_cost'] = array(
            'title' => $this->l('Additional shipping costs'),
            'filter_key' => 'ps!additional_shipping_cost',
            'type' => 'dmu_text_input',
            'size' => 100,
            'price' => true,
            'align' => 'text-right'
        );
        $this->fields_list['ean13'] = array(
            'title' => $this->l('EAN-13'),
            'filter_key' => 'a!ean13',
            'type' => 'dmu_text_input',
            'size' => 100
        );
        $this->fields_list['upc'] = array(
            'title' => $this->l('UPC'),
            'filter_key' => 'a!upc',
            'type' => 'dmu_text_input',
            'size' => 100
        );
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $this->nb_warehouse = Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'warehouse`
             WHERE `deleted` = 0');
            $this->fields_list['location'] = array(
                'title' => $this->l('Location (warehouse)'),
                'callback' => 'getLocation'
            );
        }
        $this->fields_list['width'] = array(
            'title' => $this->l('Width (package)'),
            'filter_key' => 'a!width',
            'align' => 'text-right',
            'type' => 'dmu_text_input',
            'callback' => 'getDimension',
            'float' => true
        );
        $this->fields_list['height'] = array(
            'title' => $this->l('Height (package)'),
            'filter_key' => 'a!height',
            'align' => 'text-right',
            'type' => 'dmu_text_input',
            'callback' => 'getDimension',
            'float' => true
        );
        $this->fields_list['depth'] = array(
            'title' => $this->l('Depth (package)'),
            'filter_key' => 'a!depth',
            'align' => 'text-right',
            'type' => 'dmu_text_input',
            'callback' => 'getDimension',
            'float' => true
        );
        $this->fields_list['weight'] = array(
            'title' => $this->l('Weight (package)'),
            'filter_key' => 'a!weight',
            'align' => 'text-right',
            'type' => 'dmu_text_input',
            'callback' => 'getWeight',
            'size' => 100,
            'float' => true
        );
        $this->fields_list['supplier'] = array(
            'title' => $this->l('Supplier'),
            'filter_key' => 's!name',
            'type' => 'dmu_list',
            'key' => 'id_supplier',
        );
        $this->fields_list['condition'] = array(
            'title' => $this->l('Condition'),
            'callback' => 'getCondition'
        );
        if (Configuration::get('PS_TAX')) {
            $this->fields_list['id_tax_rules_group'] = array(
                'title' => $this->l('Tax'),
                'filter_key' => 'ps!id_tax_rules_group',
                'type' => 'dmu_text',
                'callback' => 'getTaxName'
            );
        }
        $this->fields_list['unit_price_ratio'] = array(
            'title' => $this->l('Price per Unit TE'),
            'filter_key' => 'ps!unit_price_ratio',
            'align' => 'text-right',
            'type' => 'dmu_text',
            'callback' => 'getUnitPrice'
        );
        $this->fields_list['price_final'] = array(
            'title' => $this->l('Final price TI'),
            'align' => 'text-right',
            'price' => true,
            'type' => 'dmu_text',
            'callback' => 'getPriceFinal'
        );
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $this->fields_list['minimal_quantity'] = array(
                'title' => $this->l('Minimum quantity'),
                'filter_key' => 'ps!minimal_quantity',
                'align' => 'text-right',
                'type' => 'dmu_text_input',
            );
            $this->fields_list['available_now'] = array(
                'title' => $this->l('Message when in stock'),
                'filter_key' => 'b!available_now',
                'lang' => true,
                'type' => 'dmu_text_input'
            );
            $this->fields_list['available_later'] = array(
                'title' => $this->l('Message when not in stock'),
                'filter_key' => 'b!available_later',
                'lang' => true,
                'type' => 'dmu_text_input'
            );
        }
        $this->fields_list['meta_title'] = array(
            'title' => $this->l('Meta title'),
            'filter_key' => 'b!meta_title',
            'type' => 'dmu_text_input',
            'lang' => true,
        );
        $this->fields_list['meta_description'] = array(
            'title' => $this->l('Meta description'),
            'filter_key' => 'b!meta_description',
            'type' => 'dmu_text'
        );
        $this->fields_list['link_rewrite'] = array(
            'title' => $this->l('Permalink'),
            'filter_key' => 'b!link_rewrite',
            'type' => 'dmu_text'
        );
        $this->fields_list['margin'] = array(
            'title' => $this->l('Margin'),
            'filter_key' => 'ps!price',
            'align' => 'text-right',
            'callback' => 'getMargin'
        );
        $this->fields_list['active'] = array(
            'title' => $this->l('Enabled'),
            'filter_key' => 'ps!active',
            'align' => 'text-center',
            'type' => 'dmu_active',
            'class' => 'fixed-width-xs',
            'editable' => true,
            'default' => true
        );
        $this->fields_list['action'] = array(
            'title' => $this->l('Actions'),
            'align' => 'text-center',
            'class' => 'fixed-width-lg btn_actions',
            'type' => 'dmu_action'
        );

        foreach ($this->fields_list as &$value) {
            // fond gris hover
            if (isset($value['type'])) {
                if ($value['type'] == 'dmu_text_input') {
                    $value['class'] = 'show_dmu_text';
                    $value['editable'] = true;
                } elseif ($value['type'] == 'dmu_list') {
                    $value['class'] = 'show_dmu_list';
                    $value['editable'] = true;
                }
            }
        }

        // On copie le tableau pour garder les positions par défaut
        $this->position_column = $this->fields_list;

        // à true pour retourner la liste des produits en ajax
        $this->return_result_ajax = $this->ajax && in_array(Tools::getValue('action'), array(
                'change_filter',
                'critere',
                'change_order',
                'set_position',
                'bulk',
                'price_impact',
                'reload',
                'change_pagination',
                'duplicate'
            ));

        if (Tools::getValue('id_shop')) {
            Shop::setContext(Shop::CONTEXT_SHOP, Tools::getValue('id_shop'));
        }

        $this->id_shop = (int)Shop::getContextShopID();
        if (!Shop::isFeatureActive() && !$this->id_shop) {
            $this->id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');
        }
    }

    protected function detectImageType($size_max = 100, $size_min = 60)
    {
        $type_image = '';
        $images = Db::getInstance()->executeS('SELECT name, height FROM ' . _DB_PREFIX_ . 'image_type WHERE products = 1
         ORDER BY height DESC');
        foreach ($images as $image) {
            if ($image['height'] <= $size_max && $image['height'] >= $size_min) {
                $type_image = $image['name'];
                break;
            }
        }
        if (!$type_image) {
            $images = array_reverse($images);
            foreach ($images as $image) {
                if ($image['height'] >= $size_max) {
                    $type_image = $image['name'];
                    break;
                }
            }

            $images = array_reverse($images);
            if (!$type_image && !empty($images[0])) {
                $type_image = $images[0]['name'];
            }
        }

        return $type_image;
    }

    public function setColumns()
    {
        // Changement de la position des colonnes
        $new_position = array();
        foreach (unserialize($this->getCookie('column_position')) as $column_position) {
            if (isset($this->fields_list[$column_position])) {
                $new_position[$column_position] = $this->fields_list[$column_position];
            }
        }
        $this->fields_list = $new_position;

        $num = 0;
        $nb_column = count($this->fields_list);
        foreach ($this->fields_list as $column => &$value) {
            // On désactive le filtre de recherche de Prestashop
            $value['search'] = false;

            // Ajout des liens pour déplacer les colonnes
            if ($column != 'action') {
                $value['title_link'] = $this->getColumnPositionLink($num++, $column, $value, $nb_column);
            }
        }
    }

    public function ajaxProcessSetPosition()
    {
        $list_column = unserialize($this->getCookie('column_position'));

        $column = Tools::getValue('critere');
        $new_position = (int)Tools::getValue('position');

        $old_position = array_search($column, $list_column);
        $list_column[$old_position] = $list_column[$new_position];
        $list_column[$new_position] = $column;

        $this->setCookie('column_position', serialize($list_column));
    }

    public function getColumnPositionLink($num, $column, $value, $nb_column)
    {
        $prev = '';
        if ($num - 1 >= 0) {
            $prev = '<a class="btn_prev" href="javascript:;" onclick="position_column(' . ($num - 1) . ',\'' . $column
                . '\');" title="' . $this->l('Move the column on the left') . '">&laquo; </a>';
        }

        $next = '';
        if ($num + 1 < $nb_column - 1) {
            $next = '<a class="btn_next" href="javascript:;" onclick="position_column(' . ($num + 1) . ',\'' . $column
                . '\');" title="' . $this->l('Move the column on the right') . '"> &raquo;</a>';
        }

        return $prev . $value['title'] . $next;
    }

    public function ajaxProcessCritere()
    {
        $list_columns = explode(',', Tools::getValue('list_columns'));
        if (!$list_columns) {
            return;
        }

        $position_column = array_keys($this->position_column);
        $list_columns = array_flip($list_columns);
        $new_list_column = array();
        foreach ($position_column as $col) {
            if (isset($list_columns[$col])) {
                $new_list_column[] = $col;
            }
        }
        $new_list_column[] = 'action';
        $this->setCookie('column_position', serialize($new_list_column));
    }

    public function getEcotax($ecotax)
    {
        return $ecotax * (Tax::getProductEcotaxRate() / 100 + 1);
    }

    public function getWeight($weight)
    {
        return ($weight > 0) ? Tools::ps_round($weight, 3) . ' ' . Configuration::get('PS_WEIGHT_UNIT') : '';
    }

    public function getDimension($dimension)
    {
        return ($dimension > 0) ? Tools::ps_round($dimension, 3) . ' ' . Configuration::get('PS_DIMENSION_UNIT') : '';
    }

    public function getCondition($condition)
    {
        if ($condition == 'new') {
            return $this->l('New');
        } elseif ($condition == 'used') {
            return $this->l('Used');
        } elseif ($condition == 'refurbished') {
            return $this->l('Refurbished');
        }

        return '';
    }

    public function getTaxName($id_tax_rules_group)
    {
        static $tax_name = array();

        if (!isset($tax_name[$id_tax_rules_group])) {
            $tax_name[$id_tax_rules_group] = Db::getInstance()->getValue('
						SELECT `name`
						FROM `' . _DB_PREFIX_ . 'tax_rules_group`
						WHERE `id_tax_rules_group` = ' . (int)$id_tax_rules_group);
        }

        return $tax_name[$id_tax_rules_group];
    }

    public function getUnitPrice($unit_price_ratio, $tr)
    {
        return ($unit_price_ratio > 0) ? Tools::displayPrice($tr['price'] / $unit_price_ratio)
            . ' ' . $this->l('per') . ' ' . $tr['unity'] : '--';
    }

    public function getPriceFinal($price, $tr)
    {
        $price = true;
        return Product::getPriceStatic(
            $tr['id_product'],
            $price,
            (isset($tr['id_product_attribute']) ? $tr['id_product_attribute'] : false),
            $this->price_display_precision,
            null,
            false,
            true
        );
    }

    public function getMargin($price, $tr)
    {
        $return = '<span id="margin_' . $tr['id_product'] . '_0"></span>';
        if ($tr['wholesale_price'] > 0) {
            $marge = Tools::ps_round(($price - $tr['wholesale_price']) / $tr['wholesale_price'] * 100);

            $return = '<span id="margin_' . $tr['id_product'] . '_0" style="color:'
                . (($marge > 0) ? '#068400' : '#ba0f0f') . '">' . $marge . '%</span>';
        }

        return $return;
    }

    public static function recurseCategory($categories, $current, $id_category = null, $id_selected = 1)
    {
        if (!$id_category) {
            $id_category = (int)Configuration::get('PS_ROOT_CATEGORY');
        }

        $return = '<option value="' . $id_category . '"'
            . (($id_selected == $id_category) ? ' selected="selected"' : '') . '>'
            . str_repeat('&nbsp;', $current['infos']['level_depth'] * 5)
            . Tools::stripslashes($current['infos']['name']) . '</option>';
        if (isset($categories[$id_category])) {
            foreach (array_keys($categories[$id_category]) as $key) {
                $return .= self::recurseCategory($categories, $categories[$id_category][$key], $key, $id_selected);
            }
        }

        return $return;
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->addCSS(_MODULE_DIR_ . $this->module->name . '/views/css/dmuadminrecherche.css');
        $this->addJS(array(
            _MODULE_DIR_ . $this->module->name . '/views/js/ajax.js',
            _MODULE_DIR_ . $this->module->name . '/views/js/functions.js',
            _PS_JS_DIR_ . 'tiny_mce/tiny_mce.js',
            _PS_JS_DIR_ . 'admin/tinymce.inc.js',
            _PS_JS_DIR_ . '/tinymce.inc.js'
        ));

        $this->addjQueryPlugin(array('tagify', 'typewatch', 'fancybox'));
    }

    public function initPageHeaderToolbar()
    {
        $filters = unserialize($this->getCookie('filters'));
        if ($filters['query']) {
            $this->toolbar_title = array($this->module->displayName . ' > ' . $this->l('Search results for:') . ' '
                . $filters['query']);
        }
        parent::initPageHeaderToolbar();
    }

    protected function ajaxProcessChangeFilter()
    {
        $filters = unserialize($this->getCookie('filters'));

        $field = Tools::getValue('name');
        if (isset($filters[$field]) || $filters[$field] === null) {
            $filters[$field] = Tools::getValue('value');
        }
        // pour remttre à 0 les valeurs
        if ($field == 'id_attribute_groupe') {
            $filters['id_attribute'] = 0;
        }
        if ($field == 'id_feature') {
            $filters['id_feature_value'] = 0;
        }

        $this->setCookie('filters', serialize($filters));
    }

    protected function initSql()
    {
        $filters = unserialize($this->getCookie('filters'));

        // filtre par catégorie
        if ($filters['id_category']) {
            $category_interval = Category::getInterval($filters['id_category']);
            $this->_where .= ' AND EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'category_product cp
                LEFT JOIN ' . _DB_PREFIX_ . 'category c ON (c.id_category = cp.id_category)
                WHERE cp.id_product = a.id_product AND c.nleft >= ' . (int)$category_interval['nleft'] . '
                 AND c.nright <= ' . (int)$category_interval['nright'] . '
                GROUP BY cp.id_product)';
        }

        // filtre par fabricant
        if ($filters['id_manufacturer']) {
            $this->_where .= ' AND a.id_manufacturer = ' . (int)$filters['id_manufacturer'];
        }

        // filtre par fournisseur
        if ($filters['id_supplier']) {
            $this->_where .= ' AND psu.id_supplier = ' . (int)$filters['id_supplier'];
        }

        // filtre par statut
        if ($filters['id_status_color']) {
            $this->_where .= ' AND EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'dmuadminrecherche_status` ds
             WHERE a.id_product = ds.id_product AND `status` = ' . (int)$filters['id_status_color'] . ')';
        }

        // filtre par attribut
        if ($filters['id_attribute_groupe'] && !$filters['id_attribute']) {
            $this->_where .= ' AND EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_attribute pa
                INNER JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac
                 ON pa.id_product_attribute = pac.id_product_attribute
                LEFT JOIN ' . _DB_PREFIX_ . 'attribute aa ON aa.id_attribute = pac.id_attribute
                WHERE a.`id_product` = pa.id_product
                 AND id_attribute_group = ' . (int)$filters['id_attribute_groupe'] . '
                GROUP BY pa.id_product)';
        }

        // filtre par valeur d'attribut
        if ($filters['id_attribute']) {
            $this->_where .= ' AND EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_attribute pa
                LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac
                 ON pac.id_product_attribute = pa.id_product_attribute
                WHERE a.`id_product` = pa.id_product AND pac.id_attribute = ' . (int)$filters['id_attribute'] . '
                GROUP BY pa.id_product)';
        }

        // filtre par caractéristique
        if ($filters['id_feature'] && !$filters['id_feature_value']) {
            $this->_where .= ' AND EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'feature_product fp
             WHERE a.`id_product` = fp.id_product AND id_feature = ' . (int)$filters['id_feature'] . ')';
        }

        // filtre par valeur de caracteristique
        if ($filters['id_feature_value']) {
            $this->_where .= ' AND EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'feature_product` fp
             WHERE a.`id_product` =  fp.id_product AND `id_feature_value` = ' . (int)$filters['id_feature_value'] . ')';
        }

        // filtre par produit activé
        if ($filters['active'] != 2) {
            $this->_where .= ' AND ps.`active` = ' . (int)$filters['active'];
        }

        // filtre par pack
        if ($filters['pack'] != 2) {
            $this->_where .= ' AND a.`cache_is_pack` = ' . (int)$filters['pack'];
        }

        // filtre par produit en solde
        if ($filters['onsale'] != 2) {
            $this->_where .= ' AND (ps.`on_sale` = ' . (int)$filters['onsale']
                . ' OR EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_group_reduction_cache pgr
                 WHERE a.id_product = pgr.id_product))';
        }

        // filtre par produit en stock
        if ($filters['stock'] != 2) {
            $this->_where .= ' AND ' . ($filters['stock'] ? '' : 'NOT')
                . ' EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'stock_available sa
                 WHERE a.`id_product` = sa.id_product AND quantity > 0)';
        }

        // filtre par fichier à télécharger
        if ($filters['download'] != 2) {
            $this->_where = ' AND ' . ($filters['download'] ? '' : 'NOT')
                . ' EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_download pd
                 WHERE a.`id_product` = pd.id_product AND active = 1)';
        }

        // filtre par produit sans image
        if ($filters['no_image'] != 2) {
            $this->_where .= ' AND ' . ($filters['no_image'] ? 'NOT' : '')
                . ' EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'image i
                 WHERE a.`id_product` = i.id_product)';
        }

        // filtre par terme de recherche
        if ($filters['query']) {
            $chaines = explode(' ', $filters['query']);
            $array_product = array();

            if ($chaines) {
                foreach ($chaines as $chaine) {
                    // name, description, description_short, reference,
                    // product_supplier_reference, ean13, id_product, tags
                    $all_products = Db::getInstance()->executeS('
					SELECT p.`id_product`
					FROM `' . _DB_PREFIX_ . 'product` p
					LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product`)
					LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
					LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` psu ON (psu.`id_product` = p.`id_product`)
					WHERE p.`id_product` = ' . (int)$chaine . '
                        OR p.`reference` LIKE \'%' . pSQL($chaine) . '%\'
                        OR pa.`reference` LIKE \'%' . pSQL($chaine) . '%\'
                        OR psu.`product_supplier_reference` LIKE \'%' . pSQL($chaine) . '%\'
                        OR p.`ean13` LIKE \'%' . pSQL($chaine) . '%\'
                        OR pa.`ean13` LIKE \'%' . pSQL($chaine) . '%\'
                        OR pl.`name` LIKE \'%' . pSQL($chaine) . '%\'
                        OR pl.`description` LIKE \'%' . pSQL($chaine) . '%\'
                        OR pl.`description_short` LIKE \'%' . pSQL($chaine) . '%\'
                        OR p.id_product IN (SELECT pt.`id_product`
                            FROM `' . _DB_PREFIX_ . 'tag` t
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_tag` pt ON (t.`id_tag` = pt.`id_tag`)
                            WHERE t.`name` LIKE \'%' . pSQL($chaine) . '%\')
                    GROUP BY p.id_product');

                    if ($all_products) {
                        foreach ($all_products as $p) {
                            if (!in_array($p['id_product'], $array_product)) {
                                $array_product[] = $p['id_product'];
                            }
                        }
                    }
                }

                //uniquement les produits avec tous les termes
                $array_product_bis = array();
                foreach ($array_product as $p) {
                    $produit_ok = true;
                    foreach ($chaines as $chaine) {
                        $all_products = Db::getInstance()->getValue('
						SELECT COUNT(*)
						FROM `' . _DB_PREFIX_ . 'product` p
						LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product`)
						LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
						LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` psu ON (psu.`id_product` = p.`id_product`)
						WHERE (p.`id_product` = ' . (int)$chaine . '
						OR p.`reference` LIKE \'%' . pSQL($chaine) . '%\'
						OR pa.`reference` LIKE \'%' . pSQL($chaine) . '%\'
						OR psu.`product_supplier_reference` LIKE \'%' . pSQL($chaine) . '%\'
						OR p.`ean13` LIKE \'%' . pSQL($chaine) . '%\'
						OR pa.`ean13` LIKE \'%' . pSQL($chaine) . '%\'
						OR pl.`name` LIKE \'%' . pSQL($chaine) . '%\'
						OR pl.`description` LIKE \'%' . pSQL($chaine) . '%\'
						OR pl.`description_short` LIKE \'%' . pSQL($chaine) . '%\')
						AND p.`id_product` = ' . (int)$p . '
                        GROUP BY p.id_product');

                        if (!$all_products) {
                            //tags
                            $all_tags = Db::getInstance()->getValue('
							SELECT COUNT(*)
							FROM `' . _DB_PREFIX_ . 'tag` t
							LEFT JOIN `' . _DB_PREFIX_ . 'product_tag` pt ON (t.`id_tag` = pt.`id_tag`)
							WHERE t.`name` LIKE \'%' . pSQL($chaine) . '%\'
							AND pt.`id_product` = ' . (int)$p);

                            if (!$all_tags) {
                                $produit_ok = false;
                            }
                        }
                    }
                    if ($produit_ok) {
                        $array_product_bis[] = $p;
                    }
                }
                $array_product = $array_product_bis;
            }

            if ($array_product) {
                $this->_where .= ' AND a.`id_product` IN (' . implode(',', $array_product) . ')';
            } else {
                $this->_where .= ' AND a.`id_product` IN (0)';
            }
        }

        $this->_select .= 'a.id_product, 1 AS action';

        $this->_join .= ' INNER JOIN ' . _DB_PREFIX_ . 'product_shop ps ON (ps.id_product = a.id_product
         AND ps.id_shop = ' . $this->id_shop . ')';

        if (isset($this->fields_list['status'])) {
            $this->_select .= ', IF(ds.status, ds.status, 0) as status, ds.`comment`';
            $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . $this->module->name . '_status` ds
             ON (ds.id_product = a.id_product)';
        }
        if (isset($this->fields_list['manufacturer'])) {
            $this->_select .= ', a.id_manufacturer, m.`name` AS manufacturer';
            $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
             ON (m.`id_manufacturer` = a.`id_manufacturer`)';
        }
        if (isset($this->fields_list['category'])) {
            $this->_select .= ', ps.id_category_default';
        }
        if (isset($this->fields_list['price_ti'])) {
            $this->_select .= ', 1 AS price_ti';
        }
        if (isset($this->fields_list['category']) || isset($this->fields_list['image_p'])) {
            $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
             ON (ps.`id_category_default` = cl.`id_category` AND b.`id_lang` = cl.`id_lang` AND cl.id_shop = '
                . $this->id_shop . ')';
        }
        if (isset($this->fields_list['quantity'])) {
            $this->_select .= ', sa.quantity';
            $this->_join .= 'LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON (sa.`id_product` = a.`id_product`
             AND sa.`id_product_attribute` = 0 ' . StockAvailable::addSqlShopRestriction(null, null, 'sa') . ')';
        }
        if (isset($this->fields_list['supplier_reference']) || isset($this->fields_list['quantity'])) {
            $this->_select .= ', ps.cache_default_attribute';
        }
        if (isset($this->fields_list['supplier_reference']) || isset($this->fields_list['supplier'])
            || $filters['id_supplier']) {
            $this->_select .= ', a.id_supplier';
            $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` psu ON (psu.id_supplier = a.id_supplier
             AND psu.`id_product` = a.`id_product` AND psu.`id_product_attribute` = 0)
                LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (s.`id_supplier` = psu.`id_supplier`) ';
        }
        if (isset($this->fields_list['unit_price_ratio'])) {
            $this->_select .= ', ps.unity, ps.price';
        }
        if (isset($this->fields_list['price_final'])) {
            $this->_select .= ', 1 AS price_final';
        }
        if (isset($this->fields_list['margin'])) {
            $this->_select .= ', ps.wholesale_price, ps.price';
        }
        if (isset($this->fields_list['image_p'])) {
            $this->_select .= ', 1 AS image_p, b.link_rewrite, cl.link_rewrite AS category_link_rewrite,
             image_shop.id_image';
            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                 ON (image_shop.`id_product` = a.`id_product` AND image_shop.`cover` = 1
                  AND image_shop.id_shop = ' . $this->id_shop . ')
			    LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_image` = image_shop.`id_image`)';
            } else {
                $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'image` i
                 ON (i.`id_product` = a.`id_product` AND i.`cover` = 1)
                LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image`
                 AND image_shop.`cover` = 1 AND image_shop.id_shop = ' . $this->id_shop . ')';
            }
        }
        if (isset($this->fields_list['condition'])) {
            $this->_select .= ', ps.`condition`';
        }

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->_where .= ' AND state = '.\Product::STATE_SAVED;
        }

        // tri
        $order = unserialize($this->getCookie('order'));
        if ($order) {
            $this->_orderBy = $order['by'];
            $this->_orderWay = $order['way'];
        }
    }

    protected function initCookies()
    {
        // Pagination
        if ((int)Tools::getValue($this->list_id . '_pagination_ajax')) {
            $this->_default_pagination = (int)Tools::getValue($this->list_id . '_pagination_ajax');
            if ($this->_default_pagination <= 0) {
                $this->_default_pagination = 100;
            }
            $this->setCookie('pagination_ajax', $this->_default_pagination);
        } else {
            $this->_default_pagination = $this->getCookie('pagination_ajax');
            if (!$this->_default_pagination) {
                $this->_default_pagination = 100;
            }
        }
        $this->_pagination = array($this->_default_pagination);

        // Colonnes par défaut
        if ($this->getCookie('column_position') === null) {
            $default_column = array();
            foreach ($this->fields_list as $field => $v) {
                if (!empty($v['default']) || $field == 'action') {
                    $default_column[] = $field;
                }
            }
            $this->setCookie('column_position', serialize($default_column));
        }

        $this->setColumns();

        // filtre
        if (!$this->getCookie('filters')) {
            $filter_fields = array(
                'id_category',
                'id_manufacturer',
                'id_supplier',
                'id_attribute_groupe',
                'id_attribute',
                'id_feature',
                'id_feature_value',
                'id_status_color',
                'query'
            );
            $filter_radio = array(
                'active',
                'pack',
                'onsale',
                'stock',
                'download',
                'no_image'
            );

            $filters = array();
            foreach ($filter_fields as $field) {
                if (!isset($filters[$field])) {
                    $filters[$field] = null;
                }
            }
            foreach ($filter_radio as $field) {
                if (!isset($filters[$field])) {
                    $filters[$field] = 2;
                }
            }
            $this->setCookie('filters', serialize($filters));
        }

        // order by
        if ($this->getCookie('order') === null) {
            $this->setCookie('order', serialize(array(
                'by' => $this->_defaultOrderBy,
                'way' => $this->_defaultOrderWay)));
        }
    }

    public function init()
    {
        parent::init();
        if ($this->ajax) {
            $this->content_only = true;
            $this->lite_display = true;
            $this->context->smarty->assign(array(
                'iso' => $this->context->language->iso_code,
                'img_dir' => _PS_IMG_,
                'shop_name' => Configuration::get('PS_SHOP_NAME'),
                'bootstrap' => $this->bootstrap,
            ));
        }
    }

    public function initContent()
    {
        $ajax = false;
        // pour retourner la liste des produits avec renderList() en ajax
        if ($this->return_result_ajax) {
            $this->ajax = false;
            $ajax = true;
        } elseif ($this->ajax) {
            return;
        }

        $this->initCookies();
        $this->initSql();

        $this->id_shop = Shop::getContextShopID();
        if (!Shop::isFeatureActive() && empty($this->id_shop)) {
            $this->id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');
        }
        //attributs
        $attributes = Attribute::getAttributes($this->context->language->id, true);

        $attributeJs = array();
        foreach ($attributes as $attribute) {
            $attributeJs[$attribute['id_attribute_group']][$attribute['id_attribute']] = $attribute['name'];
        }

        // valeurs caractéristiques
        $features_values = Db::getInstance()->executeS('
        SELECT f.id_feature, fv.`id_feature_value`, fvl.`value`
        FROM `' . _DB_PREFIX_ . 'feature` f
        ' . Shop::addSqlAssociation('feature', 'f') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'feature_value` fv ON fv.`id_feature` = f.`id_feature`
        LEFT JOIN `' . _DB_PREFIX_ . 'feature_value_lang` fvl ON (fv.`id_feature_value` = fvl.`id_feature_value`
         AND fvl.`id_lang` = ' . (int)$this->context->language->id . ')
        INNER JOIN `' . _DB_PREFIX_ . 'feature_product` fp ON (fv.`id_feature` = fp.`id_feature`
         AND fv.`id_feature_value` = fp.`id_feature_value`)
        INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (fp.`id_product` = p.`id_product` AND p.`active` = 1)
        GROUP BY fv.`id_feature_value`
        ORDER BY fvl.`value` ASC');

        // caractéristiques
        $features = Db::getInstance()->executeS('
        SELECT f.id_feature, fl.name
        FROM `' . _DB_PREFIX_ . 'feature` f
        ' . Shop::addSqlAssociation('feature', 'f') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` fl ON (f.`id_feature` = fl.`id_feature`
         AND `id_lang` = ' . (int)$this->context->language->id . ')
        ORDER BY `name` ASC');

        $feature_valuesJs = array();
        foreach ($features_values as $fv) {
            $feature_valuesJs[$fv['id_feature']][$fv['id_feature_value']] = $fv['value'];
        }

        if ($this->getCookie('show_filter') === null) {
            $this->setCookie('show_filter', true);
        }

        $categories = Category::getCategories($this->context->language->id, false);
        $current = current($categories);
        if ($current) {
            $current = $current[key($current)];
        }

        $this->getLanguages();

        $filters = unserialize($this->getCookie('filters'));
        $order_by = implode(':', unserialize($this->getCookie('order')));

        $this->context->smarty->assign(array(
            'languages' => $this->_languages,
            'defaultFormLanguage' => $this->context->language->id,
            'allowEmployeeFormLang' => $this->allow_employee_form_lang,
            'path_module' => '../modules/' . $this->module->name,
            'id_shop' => $this->id_shop,
            'columns' => $this->position_column,
            'attributeJs' => $attributeJs,
            'feature_valuesJs' => $feature_valuesJs,
            'show_filter' => $this->getCookie('show_filter'),
            'order_by_select' => $order_by,
            'categories' => self::recurseCategory(
                $categories,
                $current,
                $current['infos']['id_category'],
                $filters['id_category']
            ),
            'manufacturers' => Manufacturer::getManufacturers(
                false,
                $this->context->language->id,
                true,
                false,
                false,
                false
            ),
            'filters_selected' => $filters,
            'suppliers' => Supplier::getSuppliers(false, $this->context->language->id, true, false, false, false),
            'attributes_groups' => AttributeGroup::getAttributesGroups($this->context->language->id),
            'features' => $features,
            'country_display_tax_label' => $this->context->country->display_tax_label,
            'noTax' => Tax::excludeTaxeOption() ? true : false,
            'tax_rules_groups' => TaxRulesGroup::getTaxRulesGroups(true),
            'taxesRatesByGroup' => TaxRulesGroup::getAssociatedTaxRatesByIdCountry($this->context->country->id),
            'ecotaxTaxRate' => Configuration::get('PS_USE_ECOTAX') ? Tax::getProductEcotaxRate() : 0,
            'return_ajax' => $this->return_result_ajax,
            'currency' => $this->context->currency,
            'price_impact' => array('increase', 'reduce'),
            'pagination' => $this->_pagination,
            'dmu_pagination' => $this->_default_pagination,
            'combination_active' => Combination::isFeatureActive(),
            'feature_active' => Feature::isFeatureActive(),
            'link' => $this->context->link,
            'stock_managment' => Configuration::get('PS_STOCK_MANAGEMENT'),
            'responsive_table' => version_compare(_PS_VERSION_, '1.6.0.10', '>='),
            'ps1605' =>  version_compare(_PS_VERSION_, '1.6.0.6', '<')
        ));

        $this->show_toolbar = false;

        $this->bulk_actions = array(
            'clearStatus' => array(
                'text' => $this->l('Clear status'),
                'icon' => 'icon-ban'
            ),
            'neutralStatus' => array(
                'text' => $this->l('Neutral status'),
                'icon' => 'icon-circle text-muted'
            ),
            'greenStatus' => array(
                'text' => $this->l('Green status'),
                'icon' => 'icon-circle green_status'
            ),
            'redStatus' => array(
                'text' => $this->l('Red status'),
                'icon' => 'icon-circle red_status'
            ),
            'divider2' => array(
                'text' => 'divider'
            ),
            'increasePrice' => array(
                'text' => $this->l('Increase the price'),
                'icon' => 'icon-plus',
                'fancy' => 'bulk_increase_price'
            ),
            'reducePrice' => array(
                'text' => $this->l('Reduce the price'),
                'icon' => 'icon-minus',
                'fancy' => 'bulk_reduce_price'
            ),
            'divider3' => array(
                'text' => 'divider'
            ),
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        parent::initContent();

        // Supprime le lien d'aide vers prestashop.com
        $this->context->smarty->clearAssign('help_link');
        $this->ajax = $ajax;
    }

    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        $id_lang_shop = $this->id_shop;
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Pour le tri par prix TTC
        $nb = count($this->_list);
        if ($this->_list) {
            for ($i = 0; $i < $nb; $i++) {
                if (isset($this->_list[$i]['price_ti'])) {
                    $this->_list[$i]['price_tmp'] = Product::getPriceStatic(
                        $this->_list[$i]['id_product'],
                        true,
                        false,
                        $this->price_display_precision,
                        null,
                        false,
                        false
                    );
                    $price_tmp = true;
                }
                // url produit en front
                if (isset($this->fields_list['image_p'])) {
                    // 404
                    $img_404 = false;
                    if (!$this->_list[$i]['id_image']) {
                        if (version_compare(_PS_VERSION_, '1.7', '<')) {
                            $this->_list[$i]['id_image'] = $this->context->language->iso_code . '-default';
                        } else {
                            $img_404 = $this->context->language->iso_code;
                        }
                    }
                    $product = new Product($this->_list[$i]['id_product'], false, null, $this->id_shop);
                    $this->_list[$i]['product_url'] = $this->getPreviewUrl($product);
                    if (!Configuration::get('PS_LEGACY_IMAGES')) {
                        $this->_list[$i]['img_src'] = _PS_PROD_IMG_ .
                            Image::getImgFolderStatic($this->_list[$i]['id_image']) . $this->_list[$i]['id_image'] .
                            '-' . $this->fields_list['image_p']['link_img_large'] . '.jpg';
                        $file_img = _PS_PROD_IMG_DIR_ . Image::getImgFolderStatic($this->_list[$i]['id_image'])
                            . $this->_list[$i]['id_image'] . '-' . $this->fields_list['image_p']['link_img_large'] .
                            '.jpg';
                    } else {
                        $this->_list[$i]['img_src'] = _PS_PROD_IMG_ .
                            $this->_list[$i]['id_product'] . '-' . $this->_list[$i]['id_image'] .
                            '-' . $this->fields_list['image_p']['link_img_large'] . '.jpg';
                        $file_img = _PS_PROD_IMG_DIR_ . $this->_list[$i]['id_product'] . '-' .
                            $this->_list[$i]['id_image'] . '-' . $this->fields_list['image_p']['link_img_large'] .
                            '.jpg';
                    }
                    if (file_exists($file_img)) {
                        $this->_list[$i]['img_src'] .= '?' . filemtime($file_img);
                    }

                    if (version_compare(_PS_VERSION_, '1.7', '>=') && $img_404) {
                        $this->_list[$i]['img_src'] = _PS_PROD_IMG_ . $img_404 . '.jpg';
                    }
                }
            }
        }

        if (!empty($price_tmp)) {
            if ($this->_orderBy == 'ps.price') {
                if (Tools::strtolower($this->_orderWay) == 'desc') {
                    uasort($this->_list, 'cmpPriceDesc');
                } else {
                    uasort($this->_list, 'cmpPriceAsc');
                }
            }

            for ($i = 0; $this->_list && $i < $nb; $i++) {
                $this->_list[$i]['price_ti'] = $this->_list[$i]['price_tmp'];
                unset($this->_list[$i]['price_tmp']);
            }
        }
    }

    public function ajaxProcessGetTranslation()
    {
        $product = new Product(Tools::getValue('id_product'), false, Tools::getValue('id_lang'), $this->id_shop);
        echo isset($product->{Tools::getValue('field')}) ? $product->{Tools::getValue('field')} : '';
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $this->toolbar_btn['new']['href'] = 'index.php?controller=AdminProducts&add' . $this->table . '&token='
                . Tools::getAdminTokenLite('AdminProducts');
        } else {
            $this->toolbar_btn['new']['href'] = 'index.php/product/new?token='
                . Tools::getAdminToken((int)$this->context->employee->id);
        }
    }

    public function ajaxProcessBulk()
    {
        $products = explode(',', Tools::getValue('list_products'));
        if (!$products) {
            return;
        }
        $action = Tools::getValue('select');
        $status = array('redStatus' => 3, 'greenStatus' => 2, 'neutralStatus' => 1);

        // activé / désactivé
        if (in_array($action, array('enableSelection', 'disableSelection'))) {
            foreach ($products as $id_product) {
                $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
                $product->active = $action == 'enableSelection' ? true : false;
                $product->update();
            }
            // statut
        } elseif (isset($status[$action])) {
            foreach ($products as $id_product) {
                $exist = Db::getInstance()->getValue('
                    SELECT COUNT(*) FROM `' . _DB_PREFIX_ . $this->module->name . '_status`
                    WHERE `id_product` = ' . (int)$id_product);

                if ($exist > 0) {
                    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . $this->module->name . '_status`
					SET `status` = ' . (int)$status[$action] . '
					WHERE `id_product` = ' . (int)$id_product);
                } else {
                    Db::getInstance()->execute('
                    INSERT INTO `' . _DB_PREFIX_ . $this->module->name . '_status` (`id_product`,`status`)
					VALUES (' . (int)$id_product . ',' . (int)$status[$action] . ')');
                }
            }
        } elseif ($action == 'clearStatus') {
            foreach ($products as $id_product) {
                Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . $this->module->name . '_status
                 WHERE id_product = ' . (int)$id_product);
            }
        } elseif ($action == 'delete') {
            foreach ($products as $id_product) {
                $p = new Product($id_product);
                $p->delete();
            }
        }
    }

    public function ajaxProcessBulkComb()
    {
        $id_product = (int)Tools::getValue('id_product');
        $list_combinations = explode(',', Tools::getValue('list_combinations'));
        if (!$id_product || !$list_combinations) {
            return;
        }
        $action = Tools::getValue('select');
        if ($action == 'deleteComb') {
            foreach ($list_combinations as $id_product_attribute) {
                $return = $this->deleteProductCombination($id_product, $id_product_attribute);
                if ($return != 'ok') {
                    echo $return;

                    return;
                }
            }
            echo 'ok';
        }
    }

    public function ajaxProcessPriceImpact()
    {
        $increase = (bool)Tools::getValue('increase');
        $products = explode(',', Tools::getValue('list_products'));
        if (!$products) {
            return;
        }

        $valeur = str_replace(',', '.', Tools::getValue('value'));
        if (!Validate::isPrice($valeur)) {
            $this->jsonError($this->l('Price value is incorrect'));

            return;
        }

        $tax = Tools::getValue('tax');
        $type = Tools::getValue('type');

        foreach ($products as $id_product) {
            $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);

            if ($increase) {
                if ($type == 'amount') {
                    if ($tax == 'te') {
                        $product->price += $valeur;
                    }
                    if ($tax == 'ti') {
                        // Prix TTC sans ecotax
                        $spe = null;
                        $prix_ttc = Product::getPriceStatic(
                            $product->id,
                            true,
                            false,
                            $this->price_display_precision,
                            null,
                            false,
                            false,
                            1,
                            false,
                            null,
                            null,
                            null,
                            $spe,
                            false
                        );
                        $new_ttc = $prix_ttc + $valeur;
                        $product->price = ($new_ttc * $product->price) / $prix_ttc;
                    }
                } elseif ($type == 'percent') {
                    if ($tax == 'te') {
                        $product->price += (($product->price + $product->ecotax) * $valeur) / 100;
                    }
                    if ($tax == 'ti') {
                        $prix_ttc = Product::getPriceStatic(
                            $product->id,
                            true,
                            false,
                            $this->price_display_precision,
                            null,
                            false,
                            false
                        );
                        $prix_ttc += $prix_ttc * $valeur / 100;
                        $product->price = ($prix_ttc / ($product->getTaxesRate() / 100 + 1)) - $product->ecotax;
                    }
                }
            } else {
                if ($type == 'amount') {
                    if ($tax == 'te') {
                        $product->price -= $valeur;
                    }
                    if ($tax == 'ti') {
                        // Prix TTC sans ecotax
                        $spe = null;
                        $prix_ttc = Product::getPriceStatic(
                            $product->id,
                            true,
                            false,
                            $this->price_display_precision,
                            null,
                            false,
                            false,
                            1,
                            false,
                            null,
                            null,
                            null,
                            $spe,
                            false
                        );
                        $new_ttc = $prix_ttc - $valeur;
                        $product->price = ($new_ttc * $product->price) / $prix_ttc;
                    }
                }

                if ($type == 'percent') {
                    if ($tax == 'te') {
                        $product->price -= (($product->price + $product->ecotax) * $valeur) / 100;
                    }
                    if ($tax == 'ti') {
                        $prix_ttc = Product::getPriceStatic(
                            $product->id,
                            true,
                            false,
                            $this->price_display_precision,
                            null,
                            false,
                            false
                        );
                        $prix_ttc -= $prix_ttc * $valeur / 100;
                        $product->price = ($prix_ttc / ($product->getTaxesRate() / 100 + 1)) - $product->ecotax;
                    }
                }
            }
            $product->price = Tools::ps_round($product->price, 6);

            //gestion des erreurs
            if (!$this->checkFields($product, 'price')) {
                continue;
            }
            $product->update();
        }
        Product::flushPriceCache();
        $this->jsonConfirmation('');
    }

    public function getPreviewUrl(Product $product)
    {
        $id_lang = Configuration::get('PS_LANG_DEFAULT', null, null, Context::getContext()->shop->id);

        if (!ShopUrl::getMainShopDomain($this->id_shop)) {
            return false;
        }
        $preview_url = $this->context->link->getProductLink(
            $product,
            $this->getFieldValue($product, 'link_rewrite', $this->context->language->id),
            Category::getLinkRewrite($product->id_category_default, $this->context->language->id),
            null,
            $id_lang,
            $this->id_shop,
            0,
            Configuration::get('PS_REWRITING_SETTINGS')
        );

        if (!$product->active) {
            $admin_dir = dirname($_SERVER['PHP_SELF']);
            $admin_dir = Tools::substr($admin_dir, strrpos($admin_dir, '/') + 1);
            $preview_url .= ((strpos($preview_url, '?') === false) ? '?' : '&') . 'adtoken='
                . Tools::getAdminTokenLite('AdminProducts') . '&ad=' . $admin_dir . '&id_employee='
                . (int)$this->context->employee->id;
        }

        return $preview_url;
    }

    public function ajaxProcessChangeActive()
    {
        if (!Tools::getValue('id_product')) {
            return;
        }
        $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id, $this->id_shop);
        if ($product->active != Tools::getValue('active')) {
            $product->toggleStatus();
        }
        echo (int)$product->active;
    }

    public function ajaxProcessChangeAvailableNow()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Tools::getValue('id_lang');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $id_lang, $this->id_shop);
        $product->available_now = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($product, 'available_now', $id_lang)) {
            return;
        }

        if ($product->update()) {
            echo $product->available_now ? $product->available_now : '--';
        }
    }

    public function ajaxProcessChangeAvailableLater()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Tools::getValue('id_lang');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $id_lang, $this->id_shop);
        $product->available_later = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($product, 'available_later', $id_lang)) {
            return;
        }

        if ($product->update()) {
            echo $product->available_later ? $product->available_later : '--';
        }
    }

    public function ajaxProcessChangeWeight()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->weight = str_replace(',', '.', Tools::getValue('value'));

        //gestion des erreurs
        if (!$this->checkFields($product, 'weight')) {
            return;
        }

        if ($product->update()) {
            echo ($product->weight > 0) ? $product->weight . ' ' . Configuration::get('PS_WEIGHT_UNIT') : '--';
        }
    }

    public function ajaxProcessGetFields()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $field = Tools::getValue('field');
        $col_update = array();
        if (isset($this->fields_list[$field]['update'])
            || ($field == 'quantity_comb' && isset($this->fields_list['quantity']))
            || ($field == 'price_comb' && $id_product_attribute)) {
            $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
            $col = isset($this->fields_list[$field]['update']) ? $this->fields_list[$field]['update'] : array($field);
            if ($field == 'price_comb' && $id_product_attribute) {
                $col = array($field);
            }
            $col_update = $this->getFields($col, $product, false, $id_product_attribute);
        }
        $this->jsonConfirmation($col_update);
    }

    public function getFields($fields, Product $product, $lang = false, $id_product_attribute = false)
    {
        $return = array();
        $list_columns = array_flip(unserialize($this->getCookie('column_position')));

        foreach ($fields as $field) {
            if ($field == 'ecotax' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => Tools::displayPrice($this->getEcotax($product->$field))
                );
            } elseif (in_array($field, array('wholesale_price', 'price_te')) && isset($list_columns[$field])) {
                $field_name = $field;
                if ($field == 'price_te') {
                    $field = 'price';
                }
                $return[] = array(
                    'col' => $field_name,
                    'value' => Tools::displayPrice($product->$field),
                    'input_value' => $product->$field
                );
            } elseif ($field == 'price_ti' && isset($list_columns[$field])) {
                $value = $this->getPriceTi($product->id);
                $return[] = array(
                    'col' => $field,
                    'value' => Tools::displayPrice($value),
                    'input_value' => $value
                );
            } elseif ($field == 'quantity_comb' && isset($list_columns['quantity'])) {
                $return[] = array(
                    'col' => 'quantity',
                    'value' => StockAvailable::getQuantityAvailableByProduct($product->id, 0, $this->id_shop)
                );
            } elseif ($field == 'id_tax_rules_group' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => $this->getTaxName($product->$field)
                );
            } elseif ($field == 'unit_price_ratio' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => $product->unity ?
                        $this->getUnitPrice(
                            $product->unit_price_ratio,
                            array('price' => $product->price, 'unity' => $product->unity)
                        ) : '--'
                );
            } elseif ($field == 'weight' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => ($product->$field > 0) ?
                        Tools::ps_round($product->$field, 3) . ' ' . Configuration::get('PS_WEIGHT_UNIT') : '--',
                    'input_value' => $product->$field
                );
            } elseif ($field == 'margin' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => $this->getMargin(
                        $product->price,
                        array('id_product' => $product->id, 'wholesale_price' => $product->wholesale_price)
                    )
                );
            } elseif ($field == 'location' && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => $this->getLocation('', array('id_product' => $product->id))
                );
            } elseif ($field == 'price_comb' && $id_product_attribute) {
                $value = $this->getPriceFinal(
                    '',
                    array('id_product' => $product->id, 'id_product_attribute' => $id_product_attribute)
                );
                $return[] = array(
                    'col' => 'price_final',
                    'value' => Tools::displayPrice($value)
                );
            } elseif ($field == 'price_final' && isset($list_columns[$field])) {
                $value = $this->getPriceFinal('', array('id_product' => $product->id));
                $return[] = array(
                    'col' => $field,
                    'value' => Tools::displayPrice($value)
                );
            } elseif (in_array($field, array('width', 'height', 'depth')) && isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => ($product->$field > 0) ? Tools::ps_round($product->$field, 3)
                        . ' ' . Configuration::get('PS_DIMENSION_UNIT') : '--',
                    'input_value' => $product->$field
                );
            } elseif (isset($list_columns[$field]) && $lang) {
                $return[] = array(
                    'col' => $field,
                    'value' => ($product->{$field}[$this->context->language->id] !== '') ?
                        $product->{$field}[$this->context->language->id] : ''
                );
            } elseif (isset($list_columns[$field])) {
                $return[] = array(
                    'col' => $field,
                    'value' => ($product->$field !== '') ? $product->$field : '--'
                );
            }
        }

        return $return;
    }

    public function ajaxProcessChangeQuantity()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = Tools::getValue('id_product_attribute', null);
        if (!$id_product) {
            return;
        }

        $quantity = Tools::getValue('value');
        if (!Validate::isUnsignedInt($quantity)) {
            $quantity = 0;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if ($product->advanced_stock_management
            && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
            && StockAvailable::dependsOnStock($product->id, $this->id_shop)) {
            // gestion des stocks avancée
            $id_warehouse = $this->getIdWarehouse($id_product, $id_product_attribute);
            if (!$id_warehouse) {
                return;
            }
            if ($stock_mvt = StockMvt::getLastPositiveStockMvt($id_product, $id_product_attribute)) {
                $price = $stock_mvt['price_te'];
            } else {
                $price = $product->wholesale_price;
            }
            $update = false;
            $warehouse = new Warehouse($id_warehouse);
            $stock_manager = StockManagerFactory::getManager();
            $physical_quantity = $stock_manager->getProductPhysicalQuantities($id_product, $id_product_attribute);
            if ($quantity > $physical_quantity) {
                $quantity -= $physical_quantity;
                $id_stock_mvt_reason = Configuration::get('PS_STOCK_MVT_INC_REASON_DEFAULT');
                $update = $stock_manager->addProduct(
                    $id_product,
                    $id_product_attribute,
                    $warehouse,
                    $quantity,
                    $id_stock_mvt_reason,
                    $price
                );
            } elseif ($quantity < $physical_quantity) {
                $quantity = $physical_quantity - $quantity;
                $id_stock_mvt_reason = Configuration::get('PS_STOCK_MVT_DEC_REASON_DEFAULT');
                $update = $stock_manager->removeProduct(
                    $id_product,
                    $id_product_attribute,
                    $warehouse,
                    $quantity,
                    $id_stock_mvt_reason
                );
            }
            if ($update) {
                StockAvailable::synchronize($id_product);
            }
        } else {
            StockAvailable::setQuantity($id_product, $id_product_attribute, $quantity, $this->id_shop);
        }
        echo StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute, $this->id_shop);
    }

    public function ajaxProcessChangeQuantityComb()
    {
        $this->ajaxProcessChangeQuantity();
    }

    public function ajaxProcessChangeReference()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->reference = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($product, 'reference')) {
            return;
        }

        if ($product->update()) {
            echo $product->reference ? Tools::safeOutput($product->reference) : '--';
        }
    }

    public function ajaxProcessChangeWholesalePrice()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->wholesale_price = Tools::ps_round(str_replace(',', '.', Tools::getValue('value')), 6);

        //gestion des erreurs
        if (!$this->checkFields($product, 'wholesale_price')) {
            return;
        }

        if ($product->update()) {
            echo ($product->wholesale_price != 0) ? Tools::displayPrice($product->wholesale_price) : '--';
        }
    }

    public function ajaxProcessChangeWholesalePriceComb()
    {
        $id_product_attribute = Tools::getValue('id_product_attribute');
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->wholesale_price = Tools::ps_round(str_replace(',', '.', Tools::getValue('value')), 6);

        //gestion des erreurs
        if (!$this->checkFields($combination, 'wholesale_price')) {
            return;
        }

        if ($combination->update()) {
            echo ($combination->wholesale_price != 0) ? Tools::displayPrice($combination->wholesale_price) : '--';
        }
    }

    public function ajaxProcessChangeAdditionalShippingCost()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->additional_shipping_cost = (float)str_replace(',', '.', Tools::getValue('value'));

        //gestion des erreurs
        if (!$this->checkFields($product, 'additional_shipping_cost')) {
            return;
        }

        if ($product->update()) {
            echo ($product->additional_shipping_cost != 0) ?
                Tools::displayPrice($product->additional_shipping_cost) : '--';
        }
    }

    public function ajaxProcessChangeSupplierReference()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $value = Tools::getValue('value');
        if (!$id_product) {
            return;
        }
        if (!Validate::isReference($value)) {
            echo $this->l('The supplier reference field is invalid.');

            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->addSupplierReference(
            $product->id_supplier,
            $id_product_attribute,
            $value,
            null,
            $this->context->currency->id ? $this->context->currency->id : Configuration::get('PS_CURRENCY_DEFAULT')
        );

        $ref = ProductSupplier::getProductSupplierReference($id_product, $id_product_attribute, $product->id_supplier);
        echo $ref ? Tools::safeOutput($ref) : '--';
    }

    public function ajaxProcessChangeManufacturer()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->id_manufacturer = (int)Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($product, 'id_manufacturer')) {
            return;
        }

        if ($product->update()) {
            $manufacturer = Db::getInstance()->getValue('
            SELECT `name`
            FROM `' . _DB_PREFIX_ . 'manufacturer`
            WHERE `id_manufacturer` = ' . (int)$product->id_manufacturer);

            echo $manufacturer ? $manufacturer : '--';
        }
    }

    public function ajaxProcessChangeCategory()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_category = (int)Tools::getValue('value');
        if (!$id_product || !$id_category) {
            return;
        }
        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if (!Product::idIsOnCategoryId($id_product, array($id_category))) {
            $product->deleteCategory($product->id_category_default);
            $product->addToCategories($id_category);
        }
        $product->id_category_default = $id_category;

        //gestion des erreurs
        if (!$this->checkFields($product, 'id_category_default')) {
            return;
        }

        if ($product->update()) {
            $categories = Db::getInstance()->getValue('
                SELECT `name`
                FROM `' . _DB_PREFIX_ . 'category_lang`
                WHERE `id_category` = ' . (int)$product->id_category_default . '
                AND `id_lang` = ' . (int)$this->context->language->id . ' AND `id_shop` = ' . (int)$this->id_shop);

            echo Tools::safeOutput($categories);
        }
    }

    public function ajaxProcessChangeSupplier()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_supplier = (int)Tools::getValue('value');
        if (!$id_product) {
            return;
        }

        $associated_suppliers = ProductSupplier::getSupplierCollection($id_product);

        $to_add = true;
        foreach ($associated_suppliers as $as) {
            if ($id_supplier == $as->id_supplier) {
                $to_add = false;
            }
        }

        if ($to_add) {
            $product_supplier = new ProductSupplier();
            $product_supplier->id_product = $id_product;
            $product_supplier->id_product_attribute = 0;
            $product_supplier->id_supplier = $id_supplier;
            if ($this->context->currency->id) {
                $product_supplier->id_currency = (int)$this->context->currency->id;
            } else {
                $product_supplier->id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
            }
            $product_supplier->save();
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->id_supplier = $id_supplier;

        //gestion des erreurs
        if (!$this->checkFields($product, 'id_supplier')) {
            return;
        }

        if ($product->update()) {
            echo $product->id_supplier ? Tools::safeOutput(Supplier::getNameById($product->id_supplier)) : '--';
        }
    }

    public function ajaxProcessChangeName()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Tools::getValue('id_lang');

        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $id_lang, $this->id_shop);
        $product->name = Tools::getValue('value');

        // Valeur de la langue par défaut si vide
        if (!$product->name && $id_lang != Configuration::get('PS_LANG_DEFAULT')) {
            $product_default = new Product($id_product, false, Configuration::get('PS_LANG_DEFAULT'), $this->id_shop);
            $product->name = $product_default->name;
        }

        //gestion des erreurs
        if (!$this->checkFields($product, 'name', $id_lang)) {
            return;
        }

        if ($product->update()) {
            echo $product->name ? Tools::safeOutput($product->name) : '--';
        }
    }

    public function ajaxProcessChangeMetaTitle()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Tools::getValue('id_lang');

        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $id_lang, $this->id_shop);
        $product->meta_title = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($product, 'meta_title', $id_lang)) {
            return;
        }

        if ($product->update()) {
            echo $product->meta_title ? Tools::safeOutput($product->meta_title) : '--';
        }
    }

    public function getPriceTi($id_product)
    {
        return Product::getPriceStatic($id_product, true, false, $this->price_display_precision, null, false, false);
    }

    public function ajaxProcessChangePrices()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->wholesale_price = Tools::getValue('wholesale_price');
        $product->price = (float)str_replace(',', '.', Tools::getValue('price'));
        $product->id_tax_rules_group = (int)Tools::getValue('id_tax_rules_group');
        $ecotax = (float)str_replace(',', '.', Tools::getValue('ecotax'));
        $product->unit_price = (float)str_replace(',', '.', Tools::getValue('unit_price'));
        $product->unity = Tools::getValue('unity');
        $product->unit_price_ratio = $product->unit_price > 0 ? $product->price / $product->unit_price : 0;
        $product->on_sale = (int)Tools::getValue('on_sale');

        if ($ecotax > 0 && Configuration::get('PS_USE_ECOTAX')) {
            $ecotaxTaxRate = Tax::getProductEcotaxRate();
            $product->ecotax = $ecotax / ($ecotaxTaxRate / 100 + 1);
        }
        $fields_update = array(
            'wholesale_price',
            'price',
            'id_tax_rules_group',
            'unity',
            'unit_price_ratio',
            'on_sale',
            'ecotax'
        );
        //gestion des erreurs
        if (($error = $this->checkFields($product, $fields_update, false, false)) !== true) {
            $this->jsonError($error);

            return;
        }
        $product->update();

        $this->jsonConfirmation($this->getFields(array(
            'wholesale_price',
            'price_ti',
            'price_te',
            'id_tax_rules_group',
            'unit_price_ratio',
            'ecotax',
            'price_final'
        ), $product));
    }

    public function ajaxProcessChangePriceTi()
    {
        $id_product = (int)Tools::getValue('id_product');
        $price = str_replace(',', '.', Tools::getValue('value'));
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if ($product->unit_price_ratio > 0) {
            $product->unit_price = $product->price / $product->unit_price_ratio;
        }
        $product_tax_rate = $product->getTaxesRate();
        $product->price = Tools::ps_round($price / (1 + $product_tax_rate / 100), 6);
        if ($product->ecotax > 0 && Configuration::get('PS_USE_ECOTAX')) {
            $product->price = Tools::ps_round($product->price - $product->ecotax, 6);
        }
        if ($product->unit_price_ratio > 0) {
            $product->unit_price_ratio = $product->price / $product->unit_price;
        }

        //gestion des erreurs
        if (!$this->checkFields($product, array('price', 'unit_price_ratio'))) {
            return;
        }

        if ($product->update()) {
            echo Tools::displayPrice(
                Product::getPriceStatic($product->id, true, false, $this->price_display_precision, null, false, false)
            );
        }
    }

    public function ajaxProcessChangePriceTe()
    {
        $id_product = (int)Tools::getValue('id_product');
        $price = (float)str_replace(',', '.', Tools::getValue('value'));
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if ($product->unit_price_ratio > 0) {
            $product->unit_price = $product->price / $product->unit_price_ratio;
        }
        $product->price = Tools::ps_round($price, 6);
        if ($product->unit_price_ratio > 0) {
            $product->unit_price_ratio = $product->price / $product->unit_price;
        }

        //gestion des erreurs
        if (!$this->checkFields($product, array('price', 'unit_price_ratio'))) {
            return;
        }

        if ($product->update()) {
            echo Tools::displayPrice($product->price);
        }
    }

    public function ajaxProcessChangeDetails()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->ean13 = Tools::getValue('ean13');
        $product->upc = Tools::getValue('upc');
        $product->width = Tools::getValue('width');
        $product->height = Tools::getValue('height');
        $product->depth = Tools::getValue('depth');
        $product->weight = Tools::getValue('weight');
        $fields_update = array('ean13', 'upc', 'width', 'height', 'depth', 'weight');
        //gestion des erreurs
        if (($error = $this->checkFields($product, $fields_update, false, false)) !== true) {
            $this->jsonError($error);

            return;
        }
        $product->update();

        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $id_warehouse = $this->getIdWarehouse($id_product);
            if ($id_warehouse) {
                Warehouse::setProductLocation($id_product, 0, $id_warehouse, Tools::getValue('location'));
            }
        }

        $this->jsonConfirmation($this->getFields(array(
            'ean13',
            'upc',
            'width',
            'height',
            'depth',
            'weight',
            'location'
        ), $product));
    }

    /**
     * Vérification des champs d'un objet et affichage d'un message d'erreur si besoin
     * @param $obj
     * @param $lang
     * @param $echo
     * @param $fields_update
     * @return bool
     */
    public function checkFields(ObjectModel $obj, $fields_update = array(), $lang = false, $echo = true)
    {
        if (!is_array($fields_update)) {
            $fields_update = array($fields_update);
        }
        foreach ($fields_update as $field) {
            if ($lang) {
                $this->update_fields[$field][$lang] = true;
            } else {
                $this->update_fields[$field] = true;
            }
        }

        $obj->setFieldsToUpdate($this->update_fields);
        if ($lang) {
            $validateFields = self::validateFieldsLang($obj, true, $lang);
        } else {
            $validateFields = self::validateFields($obj, true);
        }
        if ($validateFields !== true) {
            if ($echo) {
                echo $validateFields;

                return false;
            }

            return $validateFields;
        }

        return true;
    }

    public static function validateFields(ObjectModel $obj, $error_return = false)
    {
        $def = ObjectModel::getDefinition($obj);
        foreach ($def['fields'] as $field => $data) {
            if (!empty($data['lang'])) {
                continue;
            }

            $message = $obj->validateField($field, $obj->$field, null, array(), true);
            if ($message !== true) {
                return $error_return ? $message : false;
            }
        }

        return true;
    }

    public function validateFieldsLang(ObjectModel $obj, $error_return = false, $id_lang = false)
    {
        $def = ObjectModel::getDefinition($obj);
        foreach ($def['fields'] as $field => $data) {
            if (empty($data['lang']) || ($this->update_fields && empty($this->update_fields[$field]))) {
                continue;
            }

            $values = $obj->$field;

            if (!is_array($values)) {
                $values = array($id_lang => $values);
            }

            foreach ($values as $id_lang => $value) {
                $message = $obj->validateField($field, $value, $id_lang, array(), true);
                if ($message !== true) {
                    return $error_return ? $message : false;
                }
            }
        }

        return true;
    }

    public function ajaxProcessChangeSeo()
    {
        $id_product = (int)Tools::getValue('seo_id_product');
        if (!$id_product) {
            return;
        }

        if (!$this->default_form_language) {
            $this->getLanguages();
        }

        $fields = array('meta_title', 'meta_description', 'link_rewrite');
        $product = new Product($id_product, false, null, $this->id_shop);
        foreach ($this->_languages as $lang) {
            $product->meta_title[$lang['id_lang']] =
                Tools::getValue('meta_title_seo_' . $product->id . '_' . $lang['id_lang']);
            $product->meta_description[$lang['id_lang']] =
                Tools::getValue('meta_description_seo_' . $product->id . '_' . $lang['id_lang']);
            $product->link_rewrite[$lang['id_lang']] =
                Tools::getValue('link_rewrite_seo_' . $product->id . '_' . $lang['id_lang']);

            //gestion des erreurs
            $error = $this->checkFields(
                $product,
                $fields,
                $lang['id_lang'],
                false
            );
            if ($error !== true) {
                $this->jsonError($error);

                return;
            }
        }

        $product->update();

        $this->jsonConfirmation($this->getFields($fields, $product, true));
    }

    public function ajaxProcessChangeDescriptions()
    {
        $id_product = (int)Tools::getValue('description_id_product');
        if (!$id_product) {
            return;
        }

        if (!$this->default_form_language) {
            $this->getLanguages();
        }
        $delete_tag = false;
        $tag_success = true;

        $product = new Product($id_product, false, null, $this->id_shop);
        foreach ($this->_languages as $lang) {
            $product->description_short[$lang['id_lang']] =
                Tools::getValue('description_short_desc_' . $product->id . '_' . $lang['id_lang']);
            $product->description[$lang['id_lang']] =
                Tools::getValue('description_desc_' . $product->id . '_' . $lang['id_lang']);

            // tags
            if (!$delete_tag) {
                $delete_tag = Tag::deleteTagsForProduct($product->id);
            }
            if ($tags = Tools::getValue('tags_' . $product->id . '_' . $lang['id_lang'])) {
                $tag_success &= Tag::addTags($lang['id_lang'], $product->id, $tags);
            }

            //gestion des erreurs
            if (!$this->checkFields($product, array('description_short', 'description'), $lang['id_lang'])) {
                return;
            }
        }

        $product->update();

        if (!$tag_success) {
            echo Tools::displayError('An error occurred while adding tags.');

            return;
        }

        echo 1;
    }

    public function deleteProductCombination($id_product, $id_product_attribute)
    {
        if (!$id_product || !$id_product_attribute) {
            return '';
        }
        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $depends_on_stock = StockAvailable::dependsOnStock($id_product, $this->id_shop);
        if ($product->advanced_stock_management
            && $depends_on_stock
            && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
            && StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute)) {
            return 'error';
        } else {
            $product->deleteAttributeCombination($id_product_attribute);
            $product->checkDefaultAttributes();
            if (!$product->hasAttributes()) {
                $product->cache_default_attribute = 0;
                $product->setFieldsToUpdate(array('cache_default_attribute' => true));
                $product->update();
            } else {
                Product::updateDefaultAttribute($id_product);
            }
            if ($depends_on_stock) {
                Stock::deleteStockByIds($id_product, $id_product_attribute);
            }
        }

        return 'ok';
    }

    public function ajaxProcessDeleteProductCombination()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        echo $this->deleteProductCombination($id_product, $id_product_attribute);
    }

    public function ajaxProcessDuplicate()
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $id_product_old = $product->id;
            if (empty($product->price) && Shop::getContext() == Shop::CONTEXT_GROUP) {
                $shops = ShopGroup::getShopsFromGroup(Shop::getContextShopGroupID());
                foreach ($shops as $shop) {
                    if ($product->isAssociatedToShop($shop['id_shop'])) {
                        $product_price = new Product($id_product_old, false, null, $this->id_shop);
                        $product->price = $product_price->price;
                    }
                }
            }
            unset($product->id);
            unset($product->id_product);
            $product->indexed = 0;
            $product->active = 0;
            if ($product->add() && Category::duplicateProductCategories($id_product_old, $product->id)
                && self::duplicateSuppliers($id_product_old, $product->id)
                && ($combination_images = Product::duplicateAttributes($id_product_old, $product->id)) !== false
                && GroupReduction::duplicateReduction($id_product_old, $product->id)
                && Product::duplicateAccessories($id_product_old, $product->id)
                && Product::duplicateFeatures($id_product_old, $product->id)
                && Product::duplicateSpecificPrices($id_product_old, $product->id)
                && Pack::duplicate($id_product_old, $product->id)
                && Product::duplicateCustomizationFields($id_product_old, $product->id)
                && Product::duplicateTags($id_product_old, $product->id)
                && Product::duplicateDownload($id_product_old, $product->id)) {
                if ($product->hasAttributes()) {
                    Product::updateDefaultAttribute($product->id);
                }

                if (Tools::getValue('copy_image')
                    && !Image::duplicateProductImages($id_product_old, $product->id, $combination_images)) {
                    $this->jsonError(Tools::displayError('An error occurred while copying images.', false));
                } else {
                    Hook::exec('actionProductAdd', array('id_product' => (int)$product->id, 'product' => $product));
                    if (in_array($product->visibility, array('both', 'search'))
                        && Configuration::get('PS_SEARCH_INDEXATION')) {
                        Search::indexation(false, $product->id);
                    }
                    $this->jsonConfirmation('');
                }
            } else {
                $this->jsonError(Tools::displayError('An error occurred while creating an object.', false));
            }
        }
    }

    public static function duplicateAttributes($id_product_old, $id_product_new)
    {
        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
            return Product::duplicateAttributes($id_product_old, $id_product_new);
        }

        $return = true;
        $combination_images = array();

        $result = Db::getInstance()->executeS('
		SELECT pa.*, product_attribute_shop.*
			FROM `' . _DB_PREFIX_ . 'product_attribute` pa
			' . Shop::addSqlAssociation('product_attribute', 'pa') . '
			WHERE pa.`id_product` = ' . (int)$id_product_old);
        $combinations = array();

        foreach ($result as $row) {
            $id_product_attribute_old = (int)$row['id_product_attribute'];
            if (!isset($combinations[$id_product_attribute_old])) {
                $id_combination = null;
                $id_shop = null;
                $result2 = Db::getInstance()->executeS('
				SELECT *
				FROM `' . _DB_PREFIX_ . 'product_attribute_combination`
					WHERE `id_product_attribute` = ' . $id_product_attribute_old);
            } else {
                $id_combination = (int)$combinations[$id_product_attribute_old];
                $id_shop = (int)$row['id_shop'];
                $context_old = Shop::getContext();
                $context_shop_id_old = Shop::getContextShopID();
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
            }

            $row['id_product'] = $id_product_new;
            unset($row['id_product_attribute']);

            $combination = new Combination($id_combination, null, $id_shop);
            foreach ($row as $k => $v) {
                $combination->$k = $v;
            }
            $return &= $combination->save();

            $id_product_attribute_new = (int)$combination->id;

            if ($result_images = Product::_getAttributeImageAssociations($id_product_attribute_old)) {
                $combination_images['old'][$id_product_attribute_old] = $result_images;
                $combination_images['new'][$id_product_attribute_new] = $result_images;
            }

            if (!isset($combinations[$id_product_attribute_old])) {
                $combinations[$id_product_attribute_old] = (int)$id_product_attribute_new;
                foreach ($result2 as $row2) {
                    $row2['id_product_attribute'] = $id_product_attribute_new;
                    $return &= Db::getInstance()->insert('product_attribute_combination', $row2);
                }
            } else {
                Shop::setContext($context_old, $context_shop_id_old);
            }

            //Copy suppliers
            $result3 = Db::getInstance()->executeS('
			SELECT *
			FROM `' . _DB_PREFIX_ . 'product_supplier`
			WHERE `id_product_attribute` = ' . (int)$id_product_attribute_old . '
			AND `id_product` = ' . (int)$id_product_old);

            foreach ($result3 as $row3) {
                unset($row3['id_product_supplier']);
                $row3['id_product'] = (int)$id_product_new;
                $row3['id_product_attribute'] = (int)$id_product_attribute_new;
                $return &= Db::getInstance()->insert('product_supplier', $row3);
            }
        }

        $impacts = Product::getAttributesImpacts($id_product_old);

        if (is_array($impacts) && count($impacts)) {
            $impact_sql = 'INSERT INTO `' . _DB_PREFIX_ . 'attribute_impact`
             (`id_product`, `id_attribute`, `weight`, `price`) VALUES ';

            $impacts = array_keys($impacts);
            foreach ($impacts as $id_attribute) {
                $impact_sql .= '(' . (int)$id_product_new . ', ' . (int)$id_attribute . ', '
                    . (float)$impacts[$id_attribute]['weight'] . ', ' . (float)$impacts[$id_attribute]['price'] . '),';
            }

            $impact_sql = substr_replace($impact_sql, '', -1);
            $impact_sql .= ' ON DUPLICATE KEY UPDATE `price` = VALUES(price), `weight` = VALUES(weight)';

            Db::getInstance()->execute($impact_sql);
        }

        return !$return ? false : $combination_images;
    }

    public static function duplicateSuppliers($id_product_old, $id_product_new)
    {
        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
            return Product::duplicateSuppliers($id_product_old, $id_product_new);
        }

        $result = Db::getInstance()->executeS('
		SELECT *
		FROM `' . _DB_PREFIX_ . 'product_supplier`
		WHERE `id_product` = ' . (int)$id_product_old . ' AND `id_product_attribute` = 0');

        foreach ($result as $row) {
            unset($row['id_product_supplier']);
            $row['id_product'] = (int)$id_product_new;
            if (!Db::getInstance()->insert('product_supplier', $row)) {
                return false;
            }
        }

        return true;
    }

    public function ajaxProcessChangeFeatures()
    {
        $id_product = (int)Tools::getValue('features_id_product');
        if (!$id_product) {
            return;
        }

        $features = Tools::getValue('feature_value');

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if (Validate::isLoadedObject($product)) {
            // delete all objects
            $product->deleteFeatures();

            // add new objects
            $languages = Language::getLanguages(false);
            foreach ($features as $key => $val) {
                if ($val) {
                    $product->addFeaturesToDB($key, $val);
                } elseif ($default_value = $this->checkFeatures($languages, $key)) {
                    $id_value = $product->addFeaturesToDB($key, 0, 1);
                    foreach ($languages as $language) {
                        $custom = Tools::getValue('custom_' . (int)$language['id_lang']);
                        if ($cust = $custom[$key]) {
                            $product->addFeaturesCustomToDB($id_value, $language['id_lang'], $cust);
                        } else {
                            $product->addFeaturesCustomToDB($id_value, $language['id_lang'], $default_value);
                        }
                    }
                }
            }
        }
        if ($this->errors) {
            $texte_erreur = '';
            foreach ($this->errors as $error) {
                $texte_erreur .= $error;
            }
            $this->jsonError($texte_erreur);
        } else {
            $this->jsonConfirmation('');
        }
    }

    protected function checkFeatures($languages, $feature_id)
    {
        $rules = call_user_func(array('FeatureValue', 'getValidationRules'), 'FeatureValue');
        $feature = Feature::getFeature(Configuration::get('PS_LANG_DEFAULT'), $feature_id);
        foreach ($languages as $language) {
            $custom = Tools::getValue('custom_' . (int)$language['id_lang']);
            if ($val = $custom[$feature_id]) {
                $currentLanguage = new Language($language['id_lang']);
                if (Tools::strlen($val) > $rules['sizeLang']['value']) {
                    $this->errors[] = sprintf(
                        Tools::displayError('The name for feature %1$s is too long in %2$s.'),
                        ' <b>' . $feature['name'] . '</b>',
                        $currentLanguage->name
                    );
                } elseif (!call_user_func(array('Validate', $rules['validateLang']['value']), $val)) {
                    $this->errors[] = sprintf(
                        Tools::displayError('A valid name required for feature. %1$s in %2$s.'),
                        ' <b>' . $feature['name'] . '</b>',
                        $currentLanguage->name
                    );
                }
                if (count($this->errors)) {
                    return 0;
                }
                if ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT')) {
                    return $val;
                }
            }
        }

        return 0;
    }

    public function ajaxProcessShowCombinations()
    {
        if (!Tools::getValue('id_product')) {
            return;
        }
        $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id, $this->id_shop);

        $this->fields_list = array();
        $this->fields_list['images'] = array(
            'title' => $this->l('Image'),
            'type' => 'dmu_image_comb',
            'align' => 'text-center'
        );
        $this->fields_list['attributes'] = array(
            'title' => $this->l('Attribute - value pair')
        );
        $this->fields_list['price_comb'] = array(
            'title' => (!$this->context->country->display_tax_label || Tax::excludeTaxeOption()) ?
                $this->l('Retail price') : (
                self::$pref['affichage_ttc'] ? $this->l('Retail price with tax') : $this->l('Pre-tax retail price')
                ),
            'type' => 'dmu_text_input',
            'price' => true,
            'align' => 'text-right'
        );
        $this->fields_list['price_final'] = array(
            'title' => $this->l('Final price TI'),
            'align' => 'text-right',
            'price' => true,
            'type' => 'dmu_text'
        );
        $this->fields_list['weight_comb'] = array(
            'title' => $this->l('Impact on weight'),
            'align' => 'text-right',
            'type' => 'dmu_text_input'
        );
        $this->fields_list['reference_comb'] = array(
            'title' => $this->l('Reference'),
            'type' => 'dmu_text_input'
        );
        $this->fields_list['supplier_reference_comb'] = array(
            'title' => $this->l('Supplier Reference'),
            'align' => 'left',
            'type' => 'dmu_text_input'
        );
        $this->fields_list['ean13_comb'] = array(
            'title' => $this->l('EAN-13'),
            'type' => 'dmu_text_input'
        );
        $this->fields_list['upc_comb'] = array(
            'title' => $this->l('UPC'),
            'type' => 'dmu_text_input'
        );
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $this->fields_list['location_comb'] = array(
                'title' => $this->l('Location (warehouse)'),
                'callback' => 'getLocation'
            );
        }
        if (Configuration::get('PS_USE_ECOTAX')) {
            $this->fields_list['ecotax_comb'] = array(
                'title' => $this->l('Ecotax (tax excl.)'),
                'align' => 'text-right',
                'price' => true,
                'type' => 'dmu_text_input'
            );
        }
        $this->fields_list['wholesale_price_comb'] = array(
            'title' => $this->l('Wholesale price'),
            'align' => 'text-right',
            'price' => true,
            'type' => 'dmu_text_input'
        );
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $this->fields_list['quantity_comb'] = array(
                'title' => $this->l('Quantity'),
                'align' => 'text-right',
                'badge_danger' => true,
                'type' => 'dmu_text_input'
            );
        }
        $this->fields_list['action'] = array(
            'title' => $this->l('Actions'),
            'align' => 'text-center',
            'class' => 'fixed-width-sm',
            'type' => 'dmu_action_comb'
        );

        foreach ($this->fields_list as &$value) {
            $value['combination'] = true;
            $value['search'] = false;

            if (isset($value['type']) && $value['type'] == 'dmu_text_input') {
                $value['class'] = 'show_dmu_text';
                $value['editable'] = true;
            }
        }

        if (method_exists('ImageType', 'getFormattedName')) {
            $image_type = ImageType::getFormattedName('small');
        } else {
            $image_type = ImageType::getFormatedName('small');
        }
        $combinations = $product->getAttributeCombinations($this->context->language->id);
        $comb_array = array();
        if (is_array($combinations)) {
            $combination_images = $product->getCombinationImages($this->context->language->id);
            foreach ($combinations as $combination) {
                $comb_array[$combination['id_product_attribute']]['id_product_attribute'] =
                    $combination['id_product_attribute'];
                $comb_array[$combination['id_product_attribute']]['id_images'] =
                    isset($combination_images[$combination['id_product_attribute']]) ?
                        $combination_images[$combination['id_product_attribute']] : array();
                $comb_array[$combination['id_product_attribute']]['images'] = array();
                $comb_array[$combination['id_product_attribute']]['attributes'][] = array(
                    $combination['group_name'],
                    $combination['attribute_name'],
                    $combination['id_attribute']
                );
                $comb_array[$combination['id_product_attribute']]['weight_comb'] =
                    ($combination['weight'] > 0) ?
                        Tools::ps_round($combination['weight'], 3) . ' ' . Configuration::get('PS_WEIGHT_UNIT') : '--';
                $comb_array[$combination['id_product_attribute']]['reference_comb'] = $combination['reference'];
                $comb_array[$combination['id_product_attribute']]['ean13_comb'] = $combination['ean13'];
                $comb_array[$combination['id_product_attribute']]['upc_comb'] = $combination['upc'];
                if (Configuration::get('PS_USE_ECOTAX')) {
                    $comb_array[$combination['id_product_attribute']]['ecotax_comb'] = (float)$combination['ecotax'];
                }
                $comb_array[$combination['id_product_attribute']]['wholesale_price_comb'] =
                    (float)$combination['wholesale_price'];
                if (!empty($combination['default_on'])) {
                    $comb_array[$combination['id_product_attribute']]['class'] = 'highlighted';
                }
            }
        }

        $warehouse_errors = array();
        foreach ($comb_array as $id_product_attribute => $product_attribute) {
            // Image
            foreach ($product_attribute['id_images'] as $k => $image) {
                $img_obj = new Image($image['id_image'], $this->context->language->id);
                $comb_array[$id_product_attribute]['images'][$image['id_image']] = _THEME_PROD_DIR_
                    . $img_obj->getExistingImgPath() . '-' . $image_type . '.jpg';
                $file_img = _PS_PROD_IMG_DIR_ . Image::getImgFolderStatic($image['id_image']) . $image['id_image']
                    . '-' . $image_type . '.jpg';
                if (file_exists($file_img)) {
                    $comb_array[$id_product_attribute]['images'][$image['id_image']] .= '?' . filemtime($file_img);
                }
            }

            $list = '';
            asort($product_attribute['attributes']);
            foreach ($product_attribute['attributes'] as $attribute) {
                $list .= $attribute[0] . ' - ' . $attribute[1] . ', ';
            }
            $list = rtrim($list, ', ');
            $comb_array[$id_product_attribute]['attributes'] = $list;
            $comb_array[$id_product_attribute]['supplier_reference_comb'] =
                ProductSupplier::getProductSupplierReference(
                    $product->id,
                    $id_product_attribute,
                    $product->id_supplier
                );

            if (Configuration::get('PS_STOCK_MANAGEMENT')) {
                // Emplacement dans l'entrepôt
                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $comb_array[$id_product_attribute]['location_comb'] = '';
                }

                $comb_array[$id_product_attribute]['quantity_comb'] =
                    (string)StockAvailable::getQuantityAvailableByProduct(
                        $product->id,
                        $id_product_attribute,
                        $this->id_shop
                    );
            }
            $comb_array[$id_product_attribute]['price_comb'] =
                Product::getPriceStatic(
                    $product->id,
                    self::$pref['affichage_ttc'],
                    $id_product_attribute,
                    $this->price_display_precision,
                    null,
                    false,
                    false
                );
            $comb_array[$id_product_attribute]['price_final'] =
                Product::getPriceStatic(
                    $product->id,
                    self::$pref['affichage_ttc'],
                    $id_product_attribute,
                    $this->price_display_precision,
                    null,
                    false,
                    true
                );
            if (isset($comb_array[$id_product_attribute]['class'])
                && $comb_array[$id_product_attribute]['class'] == 'highlighted') {
                $comb_array[$id_product_attribute]['action'] = 'default';
            } else {
                $comb_array[$id_product_attribute]['action'] = '';
            }
            // Erreur entrepôt
            if (!$this->getIdWarehouse($product->id, $id_product_attribute)) {
                foreach ($this->errors as $error) {
                    $warehouse_errors[] = $error;
                }
            }
        }

        $this->errors = array();
        $helper = new HelperList();
        $helper->bootstrap = true;
        $helper->identifier = 'id_product_attribute';
        $helper->table_id = 'combinations-list';
        $helper->list_id = 'combinations';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;
        $helper->simple_header = true;
        $helper->show_toolbar = false;
        $helper->shopLinkType = $this->shopLinkType;
        $helper->actions = $this->actions;
        $helper->list_skip_actions = $this->list_skip_actions;
        $helper->colorOnBackground = true;
        $helper->force_show_bulk_actions = true;
        $helper->bulk_actions = array(
            'deleteComb' => array(
                'text' => $this->l('Delete selected combinations'),
                'confirm' => $this->l('Delete selected combinations?')
            )
        );
        $helper->override_folder = $this->tpl_folder;

        $this->context->smarty->assign(array(
            'return_ajax' => true,
            'popin_combinations' => true,
            'imageType' => $image_type,
            'combination_active' => Combination::isFeatureActive(),
            'id_product' => $product->id,
            'advanced_stock_management' => Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
                && $product->advanced_stock_management && StockAvailable::dependsOnStock($product->id, $this->id_shop),
            'warehouse_errors' => array_unique($warehouse_errors)
        ));

        $list = $helper->generateList($comb_array, $this->fields_list);
        $images = Image::getImages($this->context->language->id, $product->id);
        foreach ($images as $k => $image) {
            $img_obj = new Image($image['id_image'], $this->context->language->id);
            $images[$k]['img_src'] = _THEME_PROD_DIR_ . $img_obj->getExistingImgPath() . '-' . $image_type . '.jpg';
            $file_img = _PS_PROD_IMG_DIR_ . Image::getImgFolderStatic($image['id_image']) . $image['id_image'] . '-'
                . $image_type . '.jpg';
            if (file_exists($file_img)) {
                $images[$k]['img_src'] .= '?' . filemtime($file_img);
            }
        }

        $tpl = $this->createTemplate('combinations.tpl');
        $tpl->assign(array(
            'product_name' => Product::getProductName($product->id),
            'id_product' => $product->id,
            'product' => $product,
            'list' => $list,
            'images' => $images,
            'imageType' => $image_type
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessDeleteCombinationsImages()
    {
        $list_combinations = Tools::getValue('list_combinations');
        if ($list_combinations) {
            // On va supprimer les images de ces déclinaisons
            Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_attribute_image
             WHERE id_product_attribute IN (' . $list_combinations . ')');
        }
    }

    public function ajaxProcessAssocCombinationsImages()
    {
        $list_images = explode(',', Tools::getValue('list_images'));
        $list_combinations = explode(',', Tools::getValue('list_combinations'));

        foreach ($list_combinations as $id_combination) {
            $combination = new Combination($id_combination, null, $this->id_shop);
            $combination->setImages($list_images);
        }
    }

    public function ajaxProcessDefaultProductCombination()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        if ($id_product && $id_product_attribute) {
            $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
            $product->deleteDefaultAttributes();
            if ($product->setDefaultAttribute($id_product_attribute)) {
                echo 'ok';
            }
        }
    }

    public function ajaxProcessShowDescriptions()
    {
        if (!$this->default_form_language) {
            $this->getLanguages();
        }
        $product = new Product(Tools::getValue('id_product'), false, null, $this->id_shop);
        $tpl = $this->createTemplate('descriptions.tpl');

        $iso_tiny_mce = $this->context->language->iso_code;
        $iso_tiny_mce = file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso_tiny_mce . '.js') ?
            $iso_tiny_mce : 'en';
        $tpl->assign(array(
            'id_lang' => $this->context->language->id,
            'ad' => dirname($_SERVER['PHP_SELF']),
            'iso_tiny_mce' => $iso_tiny_mce,
            'product' => $product,
            'languages' => $this->_languages,
            'PS_PRODUCT_SHORT_DESC_LIMIT' => Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT') ?
                Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT') : 400
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessShowSeo()
    {
        if (!$this->default_form_language) {
            $this->getLanguages();
        }
        $product = new Product(Tools::getValue('id_product'), false, null, $this->id_shop);
        $tpl = $this->createTemplate('seo.tpl');

        $tpl->assign(array(
            'id_lang' => $this->context->language->id,
            'product' => $product,
            'languages' => $this->_languages
        ));

        $this->content = $tpl->fetch();
    }

    public function getLocation($location, $tr)
    {
        $location = '';
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $warehouses = Db::getInstance()->executeS('
                SELECT wpl.location, w.reference FROM `' . _DB_PREFIX_ . 'warehouse_product_location` wpl
                LEFT JOIN `' . _DB_PREFIX_ . 'warehouse` w ON (w.`id_warehouse` = wpl.`id_warehouse`)
                WHERE ' . (isset($tr['id_product']) ? 'wpl.`id_product` = ' . (int)$tr['id_product'] . ' AND ' : '') . '
                wpl.`id_product_attribute` = ' . (isset($tr['id_product_attribute']) ?
                    (int)$tr['id_product_attribute'] : 0));
            if ($warehouses && $this->nb_warehouse > 1) {
                foreach ($warehouses as $warehouse) {
                    if ($warehouse['location']) {
                        $location .= $warehouse['reference'] . ' : <strong>' . $warehouse['location']
                            . '</strong><br/>';
                    }
                }
            } elseif ($warehouses) {
                $location = $warehouses[0]['location'];
            }
        }

        return $location;
    }

    public function getIdWarehouse($id_product, $id_product_attribute = false)
    {
        $id_warehouse = 0;
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            // déclinaison déjà associé à en entrepôt
            $warehouse = Db::getInstance()->executeS(
                'SELECT id_warehouse FROM `' . _DB_PREFIX_ . 'warehouse_product_location`
                 WHERE id_product = ' . (int)$id_product . ' AND id_product_attribute = ' . (int)$id_product_attribute
            );
            if ($warehouse && count($warehouse) == 1) {
                $id_warehouse = $warehouse[0]['id_warehouse'];
            } elseif ($warehouse && count($warehouse) > 1) {
                // Pour passer le validateur de Prestashop avec la limite à 120 caractères
                $error1 = $this->l('You must go through advanced stock management');
                $error2 = $this->l(' to change the quantity if you use multiple warehouses.');
                $this->errors = array($error1 . $error2);
            }
            // Aucun entrepôt associé
            if (!$id_warehouse && !$this->errors) {
                if ($id_product_attribute) {
                    $this->errors = array($this->l('You must associate the combinations to a warehouse.'));
                } else {
                    $this->errors = array($this->l('You must associate the product to a warehouse.'));
                }
                // Entrepôt par défaut si défini
                $id_warehouse = (int)Configuration::get('PS_DEFAULT_WAREHOUSE_NEW_PRODUCT');
                if (!$id_warehouse) {
                    // Si un seul entrepôt
                    $warehouse = Db::getInstance()->executeS('SELECT id_warehouse FROM `' . _DB_PREFIX_ . 'warehouse`
                     WHERE deleted = 0');
                    if ($warehouse && count($warehouse) == 1) {
                        $id_warehouse = $warehouse[0]['id_warehouse'];
                    }
                }
            }
        }

        return $id_warehouse;
    }

    public function ajaxProcessShowDetails()
    {
        $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id, $this->id_shop);

        $url_product = '';
        $url_warehouse = '';
        $is_combination = false;
        $nb_warehouse = 0;
        $location = '';

        // Emplacement pour la gestion des stocks avancée
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $token = Tools::getAdminToken(
                'AdminProducts' . (int)Tab::getIdFromClassName('AdminProducts')
                . (int)$this->context->cookie->id_employee
            );
            $url_product = 'index.php?controller=AdminProducts&id_product=' . (int)$product->id
                . '&key_tab=Warehouses&updateproduct&token=' . $token;
            // seulement si il n'y pas de déclinaison
            $is_combination = Db::getInstance()->getValue(
                'SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'product_attribute` WHERE `id_product` = ' . (int)$product->id
            );
            if (!$is_combination) {
                $token = Tools::getAdminToken(
                    'AdminWarehouses' . (int)Tab::getIdFromClassName('AdminWarehouses')
                    . (int)$this->context->cookie->id_employee
                );
                $url_warehouse = 'index.php?controller=AdminWarehouses&addwarehouse&token=' . $token;

                $warehouses = Db::getInstance()->executeS('
									SELECT wpl.location FROM `' . _DB_PREFIX_ . 'warehouse_product_location` wpl
									LEFT JOIN `' . _DB_PREFIX_ . 'warehouse` w ON
									 (w.`id_warehouse` = wpl.`id_warehouse`)
									WHERE wpl.`id_product` = ' . (int)$product->id . '
									 AND wpl.`id_product_attribute` = 0');
                $nb_warehouse = count($warehouses);
                if ($nb_warehouse == 1) {
                    $location = $warehouses[0]['location'];
                } else {
                    // Entrepôt par défaut si défini
                    $nb_warehouse = (int)Configuration::get('PS_DEFAULT_WAREHOUSE_NEW_PRODUCT');
                    if (!$nb_warehouse) {
                        // seulement si il y a qu'un seul entrepôt
                        $nb_warehouse = $this->nb_warehouse;
                    }
                }
            }
        }
        $tpl = $this->createTemplate('details.tpl');
        $tpl->assign(array(
            'product' => $product,
            'url_product' => $url_product,
            'url_warehouse' => $url_warehouse,
            'advanced_stock_management' => Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
            'is_combination' => $is_combination,
            'nb_warehouse' => $nb_warehouse,
            'location' => $location,
            'dimension_unit' => Configuration::get('PS_DIMENSION_UNIT'),
            'weight_unit' => Configuration::get('PS_WEIGHT_UNIT')
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessShowPrices()
    {
        $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id, $this->id_shop);
        $product->ecotax = Tools::ps_round($product->ecotax * (1 + Tax::getProductEcotaxRate() / 100), 2);

        $tax_rules_groups = TaxRulesGroup::getTaxRulesGroups(true);

        $tpl = $this->createTemplate('prices.tpl');
        $tpl->assign(array(
            'product' => $product,
            'country_display_tax_label' => $this->context->country->display_tax_label,
            'noTax' => Tax::excludeTaxeOption() ? true : false,
            'currency' => $this->context->currency,
            'link' => $this->context->link,
            'unit_price' => ($product->unit_price_ratio != 0) ?
                Tools::ps_round($product->price / $product->unit_price_ratio, 6) : 0,
            'ps_tax' => Configuration::get('PS_TAX'),
            'tax_rules_groups' => $tax_rules_groups,
            'ps_use_ecotax' => Configuration::get('PS_USE_ECOTAX')
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessShowFeatures()
    {
        if (!$this->default_form_language) {
            $this->getLanguages();
        }
        $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id, $this->id_shop);

        $tpl = $this->createTemplate('features.tpl');

        $features = Feature::getFeatures(
            $this->context->language->id,
            (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP)
        );

        foreach ($features as $k => $tab_features) {
            $features[$k]['current_item'] = false;
            $features[$k]['val'] = array();

            $custom = true;
            foreach ($product->getFeatures() as $tab_products) {
                if ($tab_products['id_feature'] == $tab_features['id_feature']) {
                    $features[$k]['current_item'] = $tab_products['id_feature_value'];
                }
            }

            $features[$k]['featureValues'] = FeatureValue::getFeatureValuesWithLang(
                $this->context->language->id,
                (int)$tab_features['id_feature']
            );
            if (count($features[$k]['featureValues'])) {
                foreach ($features[$k]['featureValues'] as $value) {
                    if ($features[$k]['current_item'] == $value['id_feature_value']) {
                        $custom = false;
                    }
                }
            }

            if ($custom) {
                $feature_values_lang = FeatureValue::getFeatureValueLang($features[$k]['current_item']);
                foreach ($feature_values_lang as $feature_value) {
                    $features[$k]['val'][$feature_value['id_lang']] = $feature_value;
                }
            }
        }

        $tpl->assign(array(
            'product' => $product,
            'link' => $this->context->link,
            'id_lang' => $this->context->language->id,
            'languages' => $this->_languages,
            'features' => $features,
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessChangeEan13()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->ean13 = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'ean13')) {
            return;
        }

        if ($product->update()) {
            echo ($product->ean13 !== '') ? $product->ean13 : '--';
        }
    }

    public function ajaxProcessChangeWidth()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->width = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'width')) {
            return;
        }

        if ($product->update()) {
            echo $product->width ? Tools::ps_round($product->width, 3) . ' '
                . Configuration::get('PS_DIMENSION_UNIT') : '--';
        }
    }

    public function ajaxProcessChangeHeight()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->height = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'height')) {
            return;
        }

        if ($product->update()) {
            echo $product->height ? Tools::ps_round($product->height, 3) . ' '
                . Configuration::get('PS_DIMENSION_UNIT') : '--';
        }
    }

    public function ajaxProcessChangeDepth()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->depth = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'depth')) {
            return;
        }

        if ($product->update()) {
            echo $product->depth ? Tools::ps_round($product->depth, 3) . ' '
                . Configuration::get('PS_DIMENSION_UNIT') : '--';
        }
    }

    public function ajaxProcessChangeMinimalQuantity()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->minimal_quantity = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'minimal_quantity')) {
            return;
        }

        if ($product->update()) {
            echo $product->minimal_quantity ? $product->minimal_quantity : '--';
        }
    }

    public function ajaxProcessChangeEan13Comb()
    {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->ean13 = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($combination, 'ean13')) {
            return;
        }

        if ($combination->update()) {
            echo ($combination->ean13 !== '') ? $combination->ean13 : '--';
        }
    }

    public function ajaxProcessChangeUpc()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $product->upc = Tools::getValue('value');
        //gestion des erreurs
        if (!$this->checkFields($product, 'upc')) {
            return;
        }

        if ($product->update()) {
            echo $product->upc ? $product->upc : '--';
        }
    }

    public function ajaxProcessChangeUpcComb()
    {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->upc = Tools::getValue('value');

        //gestion des erreurs
        if (!$this->checkFields($combination, 'upc')) {
            return;
        }

        if ($combination->update()) {
            echo $combination->upc ? $combination->upc : '--';
        }
    }

    public function ajaxProcessChangeLocationComb()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        if (!$id_product || !$id_product_attribute) {
            return;
        }

        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $id_warehouse = 0;
            // déclinaison déjà associé à en entrepôt
            $warehouse = Db::getInstance()->executeS(
                'SELECT id_warehouse FROM `' . _DB_PREFIX_ . 'warehouse_product_location`
                    WHERE id_product = ' . (int)$id_product . ' AND id_product_attribute = '
                . (int)$id_product_attribute
            );
            if ($warehouse && count($warehouse) == 1) {
                $id_warehouse = $warehouse[0]['id_warehouse'];
            }
            // Aucun entrepôt associé
            if (!$id_warehouse) {
                // Entrepôt par défaut si défini
                $id_warehouse = (int)Configuration::get('PS_DEFAULT_WAREHOUSE_NEW_PRODUCT');
                if (!$id_warehouse) {
                    // Si un seul entrepôt
                    $warehouse = Db::getInstance()->executeS(
                        'SELECT id_warehouse FROM `' . _DB_PREFIX_
                        . 'warehouse` WHERE deleted = 0'
                    );
                    if ($warehouse && count($warehouse) == 1) {
                        $id_warehouse = $warehouse[0]['id_warehouse'];
                    }
                }
            }
            if ($id_warehouse) {
                Warehouse::setProductLocation(
                    $id_product,
                    $id_product_attribute,
                    $id_warehouse,
                    Tools::getValue('value')
                );
                echo Warehouse::getProductLocation($id_product, $id_product_attribute, $id_warehouse);
            } else {
                echo $this->l('No warehouse selected');
            }
        }
    }

    public function ajaxProcessChangeEcotax()
    {
        $id_product = (int)Tools::getValue('id_product');
        $value = (float)str_replace(',', '.', Tools::getValue('value'));
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        $ecotaxTaxRate = 0;
        if ($product->unit_price_ratio > 0) {
            $product->unit_price = $product->price / $product->unit_price_ratio;
        }
        $product->price += $product->ecotax;
        if ($value > 0 && Configuration::get('PS_USE_ECOTAX')) {
            $ecotaxTaxRate = Tax::getProductEcotaxRate();
            $product->ecotax = Tools::ps_round($value / ($ecotaxTaxRate / 100 + 1), 6);
        } else {
            $product->ecotax = 0;
        }
        $product->price = Tools::ps_round($product->price - $product->ecotax, 6);
        if ($product->unit_price_ratio > 0) {
            $product->unit_price_ratio = $product->price / $product->unit_price;
        }
        //gestion des erreurs
        if (!$this->checkFields($product, array('unit_price_ratio', 'ecotax'))) {
            return;
        }

        if ($product->update()) {
            echo $product->ecotax ? Tools::displayPrice($product->ecotax * ($ecotaxTaxRate / 100 + 1)) : '--';
        }
    }

    public function ajaxProcessChangeEcotaxComb()
    {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $value = str_replace(',', '.', Tools::getValue('value'));
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        if ($value > 0 && Configuration::get('PS_USE_ECOTAX')) {
            $combination->ecotax = Tools::ps_round($value, 6);
        } else {
            $combination->ecotax = 0;
        }

        //gestion des erreurs
        if (!$this->checkFields($combination, 'ecotax')) {
            return;
        }

        if ($combination->update()) {
            echo $combination->ecotax ? Tools::displayPrice($combination->ecotax) : '--';
        }
    }

    public function ajaxProcessChangePriceComb()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $price = str_replace(',', '.', Tools::getValue('value'));
        if (!$id_product) {
            return;
        }

        $product = new Product($id_product, false, $this->context->language->id, $this->id_shop);
        if (self::$pref['affichage_ttc']) {
            $product_tax_rate = $product->getTaxesRate();
            $price /= $product_tax_rate / 100 + 1;
        }
        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->price = Tools::ps_round($price - $product->price - $product->ecotax, 6);

        //gestion des erreurs
        if (!$this->checkFields($combination, 'price')) {
            return;
        }

        if ($combination->update()) {
            echo Tools::displayPrice(Product::getPriceStatic(
                $id_product,
                self::$pref['affichage_ttc'],
                $id_product_attribute,
                $this->price_display_precision,
                null,
                false,
                false
            ));
        }
    }

    public function ajaxProcessChangeWeightComb()
    {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->weight = str_replace(',', '.', Tools::getValue('value'));

        //gestion des erreurs
        if (!$this->checkFields($combination, 'weight')) {
            return;
        }

        if ($combination->update()) {
            echo ($combination->weight > 0) ? Tools::ps_round($combination->weight, 3) . ' '
                . Configuration::get('PS_WEIGHT_UNIT') : '--';
        }
    }

    public function ajaxProcessChangeReferenceComb()
    {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $value = Tools::getValue('value');
        if (!$id_product_attribute) {
            return;
        }

        $combination = new Combination($id_product_attribute, null, $this->id_shop);
        $combination->reference = $value;

        //gestion des erreurs
        if (!$this->checkFields($combination, 'reference')) {
            return;
        }

        if ($combination->update()) {
            echo $combination->reference;
        }
    }

    public function ajaxProcessChangeSupplierReferenceComb()
    {
        $this->ajaxProcessChangeSupplierReference();
    }

    public function ajaxProcessChangeOrder()
    {
        $order_by = explode(':', Tools::getValue('order_by'));
        if (count($order_by) == 2) {
            $this->setCookie('order', serialize(array('by' => $order_by[0], 'way' => $order_by[1])));
        }
    }

    public function getStatus($id_product)
    {
        return (int)Db::getInstance()->getValue('
					SELECT `status`
					FROM `' . _DB_PREFIX_ . $this->module->name . '_status`
					WHERE `id_product` = ' . (int)$id_product);
    }

    public function getCommentStatus($id_product)
    {
        return Db::getInstance()->getValue('
					SELECT `comment`
					FROM `' . _DB_PREFIX_ . $this->module->name . '_status`
					WHERE `id_product` = ' . (int)$id_product);
    }

    public function ajaxProcessShowStatus()
    {
        $id_product = (int)Tools::getValue('id_product');
        $tpl = $this->createTemplate('status.tpl');
        $tpl->assign(array(
            'product_name' => Product::getProductName($id_product),
            'id_product' => $id_product,
            'status' => self::getStatus($id_product),
            'comment' => self::getCommentStatus($id_product),
        ));

        $this->content = $tpl->fetch();
    }

    public function ajaxProcessChangeStatus()
    {
        $id_product = (int)Tools::getValue('id_product');
        if (!$id_product) {
            return;
        }

        echo Db::getInstance()->execute(
            'REPLACE INTO `' . _DB_PREFIX_ . $this->module->name . '_status` (`id_product`,`status`,`comment`)
                VALUES (' . (int)$id_product . ',' . (int)Tools::getValue('status') . ',\''
            . pSQL(Tools::getValue('comment_status')) . '\')'
        );
    }

    /**
     * Vide tous les cookie de recherche pour réinitiliser le formulaire
     */
    public function ajaxProcessClearCookie()
    {
        $this->setCookie('filters', null);
    }

    public function ajaxProcessShowFilter()
    {
        $this->setCookie('show_filter', (int)Tools::getValue('show_filter'));
    }

    protected function getCookie($var)
    {
        return isset($this->context->cookie->{$this->module->name . '_' . $var}) ?
            $this->context->cookie->{$this->module->name . '_' . $var} : null;
    }

    protected function setCookie($var, $value)
    {
        $this->context->cookie->{$this->module->name . '_' . $var} = $value;
    }
}
