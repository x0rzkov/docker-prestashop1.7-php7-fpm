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

namespace Invertus\Brad\Service\Elasticsearch\Builder;

use Category;
use Context;
use Invertus\Brad\Converter\NameConverter;
use Invertus\Brad\DataType\FilterData;
use Invertus\Brad\DataType\FilterStruct;
use Invertus\Brad\Repository\CategoryRepository;
use Invertus\Brad\Util\Arrays;
use Module;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FilterAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\RangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\RangeQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;

/**
 * Class FilterQueryBuilder
 *
 * @package Invertus\Brad\Service\Elasticsearch\Builder
 */
class FilterQueryBuilder extends AbstractQueryBuilder
{
    /**
     * Build filters query by given data
     *
     * @param FilterData $filterData
     * @param bool $countQuery
     *
     * @return array
     */
    public function buildFilterQuery(FilterData $filterData, $countQuery = false)
    {
        $query = $this->getProductQueryBySelectedFilters(
            $filterData->getSelectedFilters(),
            $filterData->getIdCategory()
        );

        if ($countQuery) {
            return $query->toArray();
        }

        $orderBy  = $filterData->getOrderBy();
        $orderWay = $filterData->getOrderWay();
        $sort     = $this->buildOrderQuery($orderBy, $orderWay);

        $query->addSort($sort);
        $query->setFrom($filterData->getFrom());
        $query->setSize($filterData->getSize());

        return $query->toArray();
    }

    /**
     * Build aggregations query
     *
     * @param FilterData $filterData
     *
     * @return array
     */
    public function buildAggregationsQuery(FilterData $filterData)
    {
        $searchQuery        = new Search();
        $filters            = $filterData->getFilters(false);
        $selectedFilters    = $filterData->getSelectedFilters();
        $hasSelectedFilters = !empty($selectedFilters);
        $idCategory         = $filterData->getIdCategory();

        if (empty($filters)) {
            return [];
        }

        /** @var FilterStruct $filter */
        foreach ($filters as $filter) {
            $fieldName = NameConverter::getElasticsearchFieldName($filter->getInputName());

            if (!$hasSelectedFilters) {
                $query = $this->getQueryFromCategories($idCategory);
            } else {
                $query = $this->getAggsQuery($selectedFilters, $filter->getInputName());
                $categoriesQuery = $this->getQueryFromCategories($idCategory);
                $query->add($categoriesQuery, BoolQuery::SHOULD);
            }

            if (in_array($filter->inputName, ['price', 'weight'])) {
                $ranges = [];

                $criterias = $filter->getCriterias();
                $lastKey = Arrays::getLastKey($criterias);

                foreach ($criterias as $key => $criteria) {
                    // Simple hack to make last value inclusive
                    $extraAmount = ($lastKey == $key) ? 0.01 : 0;
                    list($from, $to) = explode(':', $criteria['value']);
                    $ranges[] = ['key' => $criteria['value'], 'from' => (float) $from, 'to' => (float) $to + $extraAmount];
                }

                $aggregation = new RangeAggregation($fieldName, $fieldName, $ranges, true);
            } else {
                $aggregation = new TermsAggregation($fieldName, $fieldName);
            }

            $filterAggregation = new FilterAggregation($fieldName, $query);
            $filterAggregation->addAggregation($aggregation);
            $searchQuery->addAggregation($filterAggregation);
        }

        return $searchQuery->toArray();
    }

    /**
     * Get aggregation query
     *
     * @param array $selectedFilters
     * @param string $aggregationInputName
     *
     * @return BoolQuery
     */
    protected function getAggsQuery($selectedFilters, $aggregationInputName)
    {
        $boolQuery = new BoolQuery();

        foreach ($selectedFilters as $name => $values) {
            if (empty($values)) {
                continue;
            }

            if ($name == $aggregationInputName) {
                continue;
            }

            if (in_array($name, ['price', 'weight'])) {
                $query = $this->getBoolShouldRangeQuery($name, $values);
            } else {
                $query = $this->getBoolShouldTermQuery($name, $values);
            }

            $boolQuery->add($query);
        }

        return $boolQuery;
    }

    /**
     * Get search values by selected filters
     *
     * @param array $selectedFilters
     * @param int $idCategory
     *
     * @return Search
     */
    protected function getProductQueryBySelectedFilters(array $selectedFilters, $idCategory)
    {
        $searchQuery = new Search();
        $boolMustFilterQuery = new BoolQuery();

        $skipCategoriesQuery = false;

        foreach ($selectedFilters as $name => $values) {
            if (0 === strpos($name, 'feature') || 0 === strpos($name, 'attribute_group')) {
                $boolShouldTermQuery = $this->getBoolShouldTermQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldTermQuery);
            } elseif ('price' == $name) {
                $boolShouldRangeQuery = $this->getBoolShouldRangeQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldRangeQuery);
            } elseif ('manufacturer' == $name) {
                $boolShouldTermQuery = $this->getBoolShouldTermQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldTermQuery);
            } elseif ('weight' == $name) {
                $boolShouldTermQuery = $this->getBoolShouldRangeQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldTermQuery);
            } elseif ('quantity' == $name) {
                $boolShouldTermQuery = $this->getBoolShouldTermQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldTermQuery);
            } elseif ('category' == $name) {
                $boolShouldTermQuery = $this->getBoolShouldTermQuery($name, $values);
                $boolMustFilterQuery->add($boolShouldTermQuery);

                if (!empty($searchValues['categories'])) {
                    $skipCategoriesQuery = true;
                }
            }
        }

        $boolShouldCategoriesQuery = $this->getQueryFromCategories($idCategory);
        $boolMustCategoriesQuery   = new BoolQuery();

        if ($boolShouldCategoriesQuery instanceof BuilderInterface) {
            $boolMustCategoriesQuery->add($boolShouldCategoriesQuery);
        }

        if (!empty($boolMustFilterQuery->getQueries())) {
            $searchQuery->addQuery($boolMustFilterQuery);
        }

        if (!$skipCategoriesQuery) {
            $searchQuery->addQuery($boolMustCategoriesQuery, BoolQuery::MUST);
        }

        return $searchQuery;
    }

    /**
     * Get subcategories query
     *
     * @param int $idCategory
     *
     * @return BoolQuery|null
     */
    protected function getQueryFromCategories($idCategory)
    {
        $context = Context::getContext();
        $idLang = $context->language->id;
        $idShop = $context->shop->id;

        /** @var \Brad $brad */
        $brad = Module::getInstanceByName('brad');
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $brad->getContainer()->get('em')->getRepository('BradCategory');

        $inputName = 'category';
        $category = new Category($idCategory);

        $subCategories = $categoryRepository->findChildCategoriesNamesAndIds($category, $idLang, $idShop);

        if (empty($subCategories)) {
            return $this->getBoolShouldTermQuery($inputName, [$idCategory]);
        }

        $values = array_map(function($subCategory) {
            return (int) $subCategory['id_category'];
        }, $subCategories);

        $boolShouldTermQuery = $this->getBoolShouldTermQuery($inputName, $values);

        return $boolShouldTermQuery;
    }

    /**
     * Get bool should query with terms query inside
     *
     * @param string $filterIntputName
     * @param array $values
     *
     * @return BoolQuery
     */
    protected function getBoolShouldTermQuery($filterIntputName, array $values)
    {
        $fieldName = NameConverter::getElasticsearchFieldName($filterIntputName);

        $boolShouldQuery = new BoolQuery();

        foreach ($values as $value) {
            if ('category' == $filterIntputName) {
                $value = (int) $value;
            }

            $termQuery = new TermQuery($fieldName, $value);
            $boolShouldQuery->add($termQuery, BoolQuery::SHOULD);
        }

        return $boolShouldQuery;
    }

    /**
     * Get bool should query with ranges inside
     *
     * @param string $filterName
     * @param array $values
     *
     * @return BoolQuery
     */
    protected function getBoolShouldRangeQuery($filterName, array $values)
    {
        $fieldName = NameConverter::getElasticsearchFieldName($filterName);

        $boolShouldQuery = new BoolQuery();

        foreach ($values as $value) {
            if (empty($value)) {
                continue;
            }

            $params = [
                'gte'   => (float) $value['min_value'],
                // Simple hack to include last value
                'lt'  => (float) $value['max_value'] + 0.01,
            ];

            $rangeQuery = new RangeQuery($fieldName, $params);
            $boolShouldQuery->add($rangeQuery, BoolQuery::SHOULD);
        }

        return $boolShouldQuery;
    }

    /**
     * Get bool must term query
     *
     * @param string $filterIntputName
     * @param array $values
     *
     * @return BoolQuery
     */
    protected function getBoolMustTermQuery($filterIntputName, $values)
    {
        $fieldName = NameConverter::getElasticsearchFieldName($filterIntputName);

        $boolMustQuery = new BoolQuery();

        foreach ($values as $value) {
            $termQuery = new TermQuery($fieldName, $value);
            $boolMustQuery->add($termQuery);
        }

        return $boolMustQuery;
    }
}
