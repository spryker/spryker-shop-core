<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

interface CatalogPageToProductCategoryFilterClientInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer[] $facets
     * @param array $productCategoryFilters
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function updateFacetsByCategory(array $facets, array $productCategoryFilters): array;

    /**
     * @param array $facets
     * @param int $idCategory
     * @param string $localeName
     *
     * @return array
     */
    public function updateCategoryFacets(array $facets, $idCategory, $localeName);
}
