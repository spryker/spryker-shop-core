<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\FacetFilter;

use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use SprykerShop\Yves\CatalogPage\CatalogPageConfig;

class FacetFilter implements FacetFilterInterface
{
    /**
     * @var \SprykerShop\Yves\CatalogPage\CatalogPageConfig
     */
    protected $catalogPageConfig;

    /**
     * @param \SprykerShop\Yves\CatalogPage\CatalogPageConfig $catalogPageConfig
     */
    public function __construct(CatalogPageConfig $catalogPageConfig)
    {
        $this->catalogPageConfig = $catalogPageConfig;
    }

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
                $filteredFacets = $this->filterRangeSearchResults($facet, $filteredFacets);

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

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param array<\Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer> $filteredFacets
     *
     * @return array<\Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer>
     */
    protected function filterRangeSearchResults(RangeSearchResultTransfer $rangeSearchResultTransfer, array $filteredFacets): array
    {
        if ($this->isRangeFilterVisible($rangeSearchResultTransfer)) {
            $filteredFacets[] = $rangeSearchResultTransfer;
        }

        return $filteredFacets;
    }

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     *
     * @return bool
     */
    protected function isRangeFilterVisible(RangeSearchResultTransfer $rangeSearchResultTransfer): bool
    {
        if ($this->catalogPageConfig->isVisibleEmptyRangeFilters()) {
            return true;
        }

        return $rangeSearchResultTransfer->getActiveMaxOrFail() || $rangeSearchResultTransfer->getActiveMinOrFail();
    }
}
