<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Search\PageIndexMap;
use ReflectionClass;
use Spryker\Client\Catalog\CatalogClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class ProductFinder implements ProductFinderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param string $locale
     * @param QuickOrderPageConfig $config
     */
    public function __construct(
        Store $store,
        SearchClientInterface $searchClient,
        ProductStorageClientInterface $productStorageClient,
        string $locale,
        QuickOrderPageConfig $config
    ) {
        $this->store = $store;
        $this->searchClient = $searchClient;
        $this->productStorageClient = $productStorageClient;
        $this->locale = $locale;
        $this->config = $config;
    }

    /**
     * @param string $searchString
     * @param string|null $searchField
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getSearchResults(string $searchString, string $searchField = null, int $limit = null): array
    {
        $searchString = $this->buildSearchQueryString($searchString, $searchField);

        $data = $this->searchClient
            ->searchQueryString($searchString, $limit)
            ->getResponse()
            ->getData();

        $searchResults = array_map(function ($data) {
            return $data['_source'][PageIndexMap::SEARCH_RESULT_DATA];
        }, $data['hits']['hits']);

        return $this->getProductViewTransfers($searchResults);
    }

    /**
     * @param string $searchString
     * @param string|null $searchField
     *
     * @return string
     */
    protected function buildSearchQueryString(string $searchString, string $searchField = null): string
    {
        $searchFieldMapping = $this->config->getSearchFieldMapping();
        $searchField = $searchFieldMapping[$searchField] ?? PageIndexMap::FULL_TEXT;

        $queryData = [
            PageIndexMap::IS_ACTIVE . ':' . 'true',
            PageIndexMap::LOCALE . ':' . $this->locale,
            PageIndexMap::STORE . ':' . $this->store->getStoreName(),
            PageIndexMap::TYPE . ':' . 'product_abstract',
            $searchField . ':' . "\"$searchString\"",
        ];

        return implode(' AND ', $queryData);
    }

    /**
     * @param array $searchResults
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getProductViewTransfers(array $searchResults): array
    {
        $productViewTransfers = [];

        foreach ($searchResults as $productAbstractData) {
            $data = $this->productStorageClient
                ->getProductAbstractStorageData($productAbstractData['id_product_abstract'], $this->locale);

            $idProductConcreteCollection = $data['attribute_map']['product_concrete_ids'] ?? [];

            foreach ($idProductConcreteCollection as $sku => $idProductConcrete) {
                $data['id_product_concrete'] = $idProductConcrete;
                $data['sku'] = $sku;

                $productViewTransfers[] = $this->productStorageClient
                    ->mapProductStorageData($data, $this->locale);
            }
        }

        return $productViewTransfers;
    }
}
