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

namespace Invertus\Brad\Service;

use Configuration;
use Context;
use Invertus\Brad\Config\Sort;
use Tools;

/**
 * Class UrlParser
 *
 * @package Invertus\Brad\Service
 */
class UrlParser
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var array After parsing url selected filters are stored here
     */
    private $selectedFilters = [];

    /**
     * @var string Selected filters query string
     */
    private $queryString = '';

    /**
     * UrlParser constructor.
     */
    public function __construct()
    {
        $this->context = Context::getContext();
    }

    /**
     * Parse url to get selected filters
     *
     * @param array $query
     */
    public function parse(array $query)
    {
        $extraParams = [];

        foreach ($query as $filterName => $filterValue) {
            if (!$this->checkIfFilter($filterName)) {
                if ($this->checkIfExtraParam($filterName)) {
                    $extraParams[$filterName] = $filterValue;
                }
                continue;
            }

            $this->addQueryStringParam($filterName, $filterValue);
            $values = explode('-', $filterValue);

            foreach ($values as $value) {
                $this->selectedFilters[$filterName][] = $this->parseValue($value);
            }
        }

        foreach ($extraParams as $name => $value) {
            $this->addQueryStringParam($name, $value);
        }
    }

    /**
     * @return array
     */
    public function getSelectedFilters()
    {
        return $this->selectedFilters;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Get selected page
     *
     * return int
     */
    public function getPage()
    {
        $page = (int) Tools::getValue('p', 1);

        if (0 >= $page) {
            $page = 1;
        }

        return $page;
    }

    /**
     * Get selected size
     *
     * @return int
     */
    public function getSize()
    {
        $size = (int) Tools::getValue('n');

        if (0 >= $size) {
            $size = isset($this->context->cookie->nb_item_per_page) ?
                (int) $this->context->cookie->nb_item_per_page :
                (int) Configuration::get('PS_PRODUCTS_PER_PAGE');
        }

        return $size;
    }

    /**
     * Get order by
     *
     * @return string
     */
    public function getOrderBy()
    {
        $orderBy = Tools::getValue('orderby');

        $availableOrderBy = [Sort::BY_NAME, Sort::BY_PRICE, Sort::BY_QUANTITY, Sort::BY_REFERENCE, Sort::BY_RELEVANCE];

        if (!in_array($orderBy, $availableOrderBy)) {
            $orderBy = Sort::BY_RELEVANCE;
        }

        return $orderBy;
    }

    /**
     * Get order way
     *
     * @return string
     */
    public function getOrderWay()
    {
        $orderWay = Tools::getValue('orderway');

        $ways = [Sort::WAY_ASC, Sort::WAY_DESC];

        if (!in_array($orderWay, $ways)) {
            $orderWay = Sort::WAY_ASC;
        }

        return $orderWay;
    }

    /**
     * Get search query
     *
     * @return string
     */
    public function getSearchQuery()
    {
        $searchQuery = Tools::getValue('search_query', '');

        return $searchQuery;
    }

    /**
     * Get current category ID
     *
     * @return int
     */
    public function getIdCategory()
    {
        $idCategory = (int) Tools::getValue('id_category');

        if (!is_int($idCategory) || !$idCategory) {
            $idCategory = (int) Configuration::get('PS_HOME_CATEGORY');
        }

        return $idCategory;
    }

    /**
     * Get available filters
     *
     * @param string $filterName
     *
     * @return bool
     */
    protected function checkIfFilter($filterName)
    {
        $staticFilterNames = [
            'category',
            'price',
            'quantity',
            'manufacturer',
            'weight',
        ];

        if (in_array($filterName, $staticFilterNames) ||
            0 === strpos($filterName, 'feature_') ||
            0 === strpos($filterName, 'attribute_group_')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function addQueryStringParam($key, $value)
    {
        $defaults['orderby'] = Sort::BY_RELEVANCE;
        $defaults['orderway'] = Sort::WAY_ASC;
        $defaults['p'] = 1;
        $defaults['n'] = (int) Configuration::get('PS_PRODUCTS_PER_PAGE');
        $defaultKeys = array_keys($defaults);

        if (in_array($key, $defaultKeys) && $value == $defaults[$key]) {
            return;
        }

        if (empty($this->queryString)) {
            $this->queryString = sprintf('%s=%s', $key, $value);
            return;
        }

        $this->queryString .= sprintf('&%s=%s', $key, $value);
    }

    /**
     * Check if it is extra param
     *
     * @param string $filterName
     *
     * @return bool
     */
    protected function checkIfExtraParam($filterName)
    {
        $extraParams = ['orderby', 'orderway', 'p', 'n'];

        return in_array($filterName, $extraParams);
    }

    /**
     * Parse given value
     *
     * @param $value
     *
     * @return string|array
     */
    protected function parseValue($value)
    {
        if (false !== strpos($value, ':')) {
            list($minValue, $maxValue) = explode(':', $value);

            return [
                'min_value' => $minValue,
                'max_value' => $maxValue,
            ];
        }

        return $value;
    }
}
