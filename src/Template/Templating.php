<?php
/**
 * Copyright (c) 2016-2017 Invertus, JSC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Invertus\Brad\Template;

use Configuration;
use Context;
use Image;
use ImageType;
use Invertus\Brad\Config\Setting;
use Invertus\Brad\DataType\FilterData;
use Manufacturer;
use Module;
use Tools;

/**
 * Class Templating
 *
 * @package Invertus\Brad\Template
 */
class Templating
{
    const FILENAME = 'Templating';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var string
     */
    private $bradTemplatesDir;

    /**
     * @var \Core_Foundation_Database_EntityManager
     */
    private $em;

    /**
     * TemplateBuilder constructor.
     *
     * @param string $bradTemplates
     * @param $em
     */
    public function __construct($bradTemplates, $em)
    {
        $this->context = Context::getContext();
        $this->bradTemplatesDir = $bradTemplates;
        $this->em = $em;
    }

    /**
     * Build filters html template
     *
     * @param FilterData $filterData
     * @param array $aggregations
     *
     * @return string
     */
    public function renderFiltersBlockTemplate(FilterData $filterData, array $aggregations)
    {
        $isAggregationsOn = (bool) Configuration::get(Setting::DISPLAY_NUMBER_OF_MATCHING_PRODUCTS);
        $hideZeroFilters  = (bool) Configuration::get(Setting::HIDE_FILTERS_WITH_NO_PRODUCTS);

        $this->context->smarty->assign([
            'filters'           => $filterData->getFilters(),
            'aggregations'      => $aggregations,
            'p'                 => $filterData->getPage(),
            'n'                 => $filterData->getSize(),
            'orderby'           => $filterData->getOrderBy(),
            'orderway'          => $filterData->getOrderWay(),
            'aggregations_on'   => $isAggregationsOn,
            'hide_zero_filters' => $hideZeroFilters,
        ]);

        return $this->context->smarty->fetch($this->bradTemplatesDir.'front/filter-template.tpl');
    }

    /**
     * Build filter results html template
     *
     * @param array $products
     * @param $productsCount
     *
     * @return string
     */
    public function renderProductsTemplate(array $products, $productsCount)
    {
        if (empty($products)) {
            return $this->context->smarty->fetch($this->bradTemplatesDir.'front/no-products-found.tpl');
        }

        $frontController = Context::getContext()->controller;
        $frontController->addColorsToProductList($products);

        $this->context->smarty->assign([
            'products'        => $products,
            'search_products' => $products,
            'nbProducts'      => (int) $productsCount,
            'homeSize'        => Image::getSize(ImageType::getFormatedName('home')),
        ]);

        $renderedList = $this->context->smarty->fetch(_PS_THEME_DIR_.'product-list.tpl');

        return $renderedList;
    }

    /**
     * Render pagination template
     *
     * @param int $productsCount
     * @param int $page
     * @param int $n
     *
     * @return string
     */
    public function renderPaginationTemplate($productsCount, $page, $n)
    {
        $range = 2;

        if ($page > ($productsCount / $n)) {
            $page = ceil($productsCount / $n);
        }

        $pagesCount = ceil($productsCount / $n);

        $start = $page - $range;
        $stop  = $page + $range;

        if ($start < 1) {
            $start = 1;
        }

        if ($stop > $pagesCount) {
            $stop = $pagesCount;
        }

        $this->context->smarty->assign([
            'nb_products'       => $productsCount,
            'pages_nb'          => $pagesCount,
            'p'                 => $page,
            'n'                 => $n,
            'range'             => $range,
            'start'             => $start,
            'stop'              => $stop,
            'paginationId'      => 'bottom',
            'products_per_page' => (int) Configuration::get('PS_PRODUCTS_PER_PAGE'),
            'current_url'       => 'pagination',
        ]);

        return $this->context->smarty->fetch(_PS_THEME_DIR_.'pagination.tpl');
    }

    /**
     * Render selected filters
     *
     * @param array $selectedFilters
     *
     * @return string
     */
    public function renderSelectedFilters($selectedFilters)
    {
        if (empty($selectedFilters)) {
            return '';
        }

        $formattedSelectedFilters = [];

        foreach ($selectedFilters as $key => $selectedValues) {
            if (!isset($formattedSelectedFilters[$key]['name'])) {
                $formattedSelectedFilters[$key]['name'] = $this->getTranslation($key);
            }

            foreach ($selectedValues as $selectedValue) {

                $value = is_array($selectedValue)
                    ? sprintf('%s:%s', $selectedValue['min_value'], $selectedValue['max_value'])
                    : $selectedValue;

                $displayValue = $this->getValueDisplay($key, $selectedValue);

                $formattedSelectedFilters[$key]['values'][] = [
                    'filter'        => $key,
                    'filter_value'  => $value,
                    'display_value' => $displayValue,
                ];
            }

        }

        $this->context->smarty->assign([
            'formatted_selected_filters' => $formattedSelectedFilters,
        ]);

        return $this->context->smarty->fetch($this->bradTemplatesDir.'front/selected-filters.tpl');
    }

    /**
     * Render category count template
     *
     * @param int $productsCount
     *
     * @return string
     */
    public function renderCategoryCountTemplate($productsCount)
    {
        $this->context->smarty->assign([
            'nb_products' => (int) $productsCount,
        ]);

        return $this->context->smarty->fetch(_PS_THEME_DIR_.'category-count.tpl');
    }

    /**
     * Get filter translation
     *
     * @param string $key
     *
     * @return string
     */
    protected function getTranslation($key)
    {
        /** @var \Brad $brad */
        $brad = Module::getInstanceByName('brad');

        $staticTranslations = [
            'price'        => $brad->l('Price', self::FILENAME),
            'manufacturer' => $brad->l('Manufacturer', self::FILENAME),
            'quantity'     => $brad->l('Quantity', self::FILENAME),
            'category'     => $brad->l('Category', self::FILENAME),
            'weight'       => $brad->l('Weight', self::FILENAME),
        ];

        if (isset($staticTranslations[$key])) {
            return $staticTranslations[$key];
        }

        $idShop = $this->context->shop->id;
        $idLang = $this->context->language->id;

        $featuresRep = $this->em->getRepository('BradFeature');
        $attributeGroupRep = $this->em->getRepository('BradAttributeGroup');

        $featuresNames = $featuresRep->findNames($idLang, $idShop);
        $attribtueGroupsNames = $attributeGroupRep->findNames($idLang, $idShop);

        if (0 === strpos($key, 'feature')) {
            $idFeature = explode('_', $key)[1];
            return $featuresNames[$idFeature];
        }

        if (0 === strpos($key, 'attribute_group')) {
            $idAttributeGroup = explode('_', $key)[2];
            return $attribtueGroupsNames[$idAttributeGroup];
        }

        return $brad->l('Unknown', self::FILENAME);
    }

    /**
     * Get value to be displayed
     *
     * @param string $key
     * @param mixed $value
     *
     * @return string
     */
    protected function getValueDisplay($key, $value)
    {
        $brad = Module::getInstanceByName('brad');
        $idShop = $this->context->shop->id;
        $idLang = $this->context->language->id;

        if ('price' == $key) {
            $minPrice = Tools::displayPrice($value['min_value']);
            $maxPrice = Tools::displayPrice($value['max_value']);
            return sprintf('%s - %s', $minPrice, $maxPrice);
        } elseif ('manufacturer' == $key) {
            return Manufacturer::getNameById($value);
        } elseif ('quantity' == $key) {
            return $value ? $brad->l('In Stock', self::FILENAME) : $brad->l('Out of stock', self::FILENAME);
        } elseif ('category' == $key) {
            $categoryRep = $this->em->getRepository('BradCategory');
            $categoriesNames = $categoryRep->findAllCategoryNamesAndIds($idLang, $idShop);
            return $categoriesNames[$value];
        } elseif ('weight' == $key) {
            return sprintf('%s - %s', $value['min_value'], $value['max_value']);
        } elseif (0 === strpos($key, 'feature')) {
            $featuresRep = $this->em->getRepository('BradFeature');
            $idFeature = explode('_', $key)[1];
            $featuresValues = $featuresRep->findFeaturesValues($idFeature, $idLang);
            return $featuresValues[$idFeature][$value]['name'];
        } elseif (0 === strpos($key, 'attribute_group')) {
            $attributeGroupRep = $this->em->getRepository('BradAttributeGroup');
            $idAttributeGroup = explode('_', $key)[2];
            $attribtueGroupsValuesNames = $attributeGroupRep->findAttributesGroupsValues($idAttributeGroup, $idLang, $idShop);
            return $attribtueGroupsValuesNames[$idAttributeGroup][$value]['name'];
        }

        return $brad->l('Unknown value', self::FILENAME);
    }
}
