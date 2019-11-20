<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\FacetFilter;

interface FacetFilterInterface
{
    /**
     * @param array $facets
     *
     * @return array
     */
    public function getFilteredFacets(array $facets): array;
}
