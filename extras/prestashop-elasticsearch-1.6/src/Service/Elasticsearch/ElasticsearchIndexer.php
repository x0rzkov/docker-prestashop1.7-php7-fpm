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

namespace Invertus\Brad\Service\Elasticsearch;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Exception;
use Invertus\Brad\Logger\LoggerInterface;
use Invertus\Brad\Service\Elasticsearch\Builder\DocumentBuilder;
use Invertus\Brad\Service\Elasticsearch\Builder\IndexBuilder;
use Product;

/**
 * Class ElasticsearchIndexer
 *
 * @package Invertus\Brad\Service\Elasticsearch
 */
class ElasticsearchIndexer
{
    /**
     * @var ElasticsearchManager
     */
    private $manager;

    /**
     * @var DocumentBuilder
     */
    private $documentBuilder;

    /**
     * @var IndexBuilder
     */
    private $indexBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ElasticsearchIndexer constructor.
     *
     * @param ElasticsearchManager $manager
     * @param DocumentBuilder $documentBuilder
     * @param IndexBuilder $indexBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(ElasticsearchManager $manager, DocumentBuilder $documentBuilder, IndexBuilder $indexBuilder, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->documentBuilder = $documentBuilder;
        $this->indexBuilder = $indexBuilder;
        $this->logger = $logger;
    }

    /**
     * Create Elasticsearch index if it does not exist for given shop
     *
     * @param int $idShop
     *
     * @return bool
     */
    public function createIndex($idShop)
    {
        if ($this->manager->isIndexCreated($idShop)) {
            return true;
        }

        $params = [];
        $params['index'] = $this->manager->getIndexPrefix().$idShop;
        $params['body'] = $this->indexBuilder->buildIndex($idShop);

        $client = $this->manager->getClient();

        try {
            $response = $client->indices()->create($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to create index: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return $response['acknowledged'];
    }

    /**
     * Delete index
     *
     * @param int $idShop
     *
     * @return bool
     */
    public function deleteIndex($idShop)
    {
        if (!$this->manager->isIndexCreated($idShop)) {
            return true;
        }

        $params = [];
        $params['index'] = $this->manager->getIndexPrefix().$idShop;

        $client = $this->manager->getClient();

        try {
            $response = $client->indices()->delete($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to delete index: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return $response['acknowledged'];
    }

    /**
     * Update index settings
     *
     * @param int $idShop
     * @param array $settings
     *
     * @return bool
     */
    public function updateIndex($idShop, array $settings)
    {
        if (!$this->manager->isIndexCreated($idShop)) {
            return false;
        }

        $params = [];
        $params['index'] = $this->manager->getIndexPrefix().$idShop;
        $params['body'] = $settings;

        $client = $this->manager->getClient();

        try {
            $response = $client->indices()->putSettings($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to update index: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return (bool) $response['acknowledged'];
    }

    /**
     * Index given product
     *
     * @param Product $product
     * @param int $idShop
     *
     * @return bool
     */
    public function indexProduct(Product $product, $idShop)
    {
        $productBody = $this->documentBuilder->buildProductBody($product);
        $productPricesBody = $this->documentBuilder->buildProductPriceBody($product, $idShop);

        $body = array_merge($productBody, $productPricesBody);

        $params = [
            'index' => $this->manager->getIndexPrefix().$idShop,
            'type' => 'products',
            'id' => $product->id,
            'body' => $body,
        ];

        $client = $this->manager->getClient();

        try {
            $response = $client->index($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to index product: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return $response['created'];
    }

    /**
     * Check if product is already indexed
     *
     * @param Product $product
     * @param int $idShop
     *
     * @return bool
     */
    public function isIndexedProduct(Product $product, $idShop)
    {
        $params = [];
        $params['index'] = $this->manager->getIndexPrefix().$idShop;
        $params['type'] = 'products';
        $params['id'] = $product->id;

        $client = $this->manager->getClient();

        try {
            $response = $client->get($params);
        } catch (Missing404Exception $e) {
            return false;
        } catch (Exception $e) {
            $message = sprintf('Failed to find product with id %s: %s', $product->id, $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return $response['found'];
    }

    /**
     * Index products with bulk action
     *
     * @param array
     *
     * @return int Number of indexed products
     */
    public function indexBulk(array $params)
    {
        $client = $this->manager->getClient();

        try {
            $response = $client->bulk($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to perform bulk indexing: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        if ($response['errors']) {
            $indexedProductsCount = 0;

            foreach ($response['items'] as $item) {
                $itemStatus = $item['index']['status'];
                if ($itemStatus >= 200 && $itemStatus < 300) {
                    $indexedProductsCount++;
                }
            }

            return $indexedProductsCount;
        }

        return count($response['items']);
    }

    /**
     * Delete product
     *
     * @param Product $product
     * @param int $idShop
     *
     * @return bool
     */
    public function deleteProduct(Product $product, $idShop)
    {
        $params = [];
        $params['index'] = $this->manager->getIndexPrefix().$idShop;
        $params['type'] = 'products';
        $params['id'] = $product->id;

        $client = $this->manager->getClient();

        try {
            $response = $client->delete($params);
        } catch (Exception $e) {
            $message = sprintf('Failed to delete product: %s', $e->getMessage());
            $this->logger->log($message);
            return false;
        }

        return $response['found'];
    }
}
