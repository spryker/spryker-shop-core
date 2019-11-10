<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\FacetFilter;

use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;

class FacetFilter implements FacetFilterInterface
{
    /**
     * @param array $facets
     *
     * @return array
     */
    public function getFilteredFacets(array $facets): array
    {
        $filteredFacets = [];

        foreach ($facets as $facet) {
            if ($facet instanceof RangeSearchResultTransfer) {
                $filteredFacets[] = $facet;

                continue;
            }

            if (!$facet instanceof FacetSearchResultTransfer) {
                continue;
            }

            if ($facet->getValues()->count() === 0) {
                continue;
            }

            $filteredFacets[] = $facet;
        }

        return $filteredFacets;
    }
}
