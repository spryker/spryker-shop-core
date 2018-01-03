<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

class CatalogPageToProductCategoryFilterClientBridge implements CatalogPageToProductCategoryFilterClientInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterClientInterface
     */
    protected $productCategoryFilterClient;

    /**
     * @param \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterClientInterface $productCategoryFilterClient
     */
    public function __construct($productCategoryFilterClient)
    {
        $this->productCategoryFilterClient = $productCategoryFilterClient;
    }

    /**
     * @param int $idCategory
     * @param string $localeName
     *
     * @return array
     */
    public function getProductCategoryFiltersForCategoryByLocale($idCategory, $localeName)
    {
        return $this->productCategoryFilterClient->getProductCategoryFiltersForCategoryByLocale($idCategory, $localeName);
    }

    /**
     * @param array $facets
     * @param array $productCategoryFilters
     *
     * @return array
     */
    public function updateFacetsByCategory(array $facets, array $productCategoryFilters)
    {
        return $this->productCategoryFilterClient->updateFacetsByCategory($facets, $productCategoryFilters);
    }
}
