<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Widget;

use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductRatingFilterWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request)
    {
        $this->addParameter('filter', $rangeSearchResultTransfer)
            ->addParameter('filterValue', $this->getFilterValue($rangeSearchResultTransfer, $request))
            ->addParameter('maximumRating', $this->getMaximumRating());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductRatingFilterWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/views/catalog-rating-filter/catalog-rating-filter.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return float|null
     */
    protected function getFilterValue(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request): ?float
    {
        return $request->query->has($rangeSearchResultTransfer->getConfig()->getParameterName()) ?
            $rangeSearchResultTransfer->getActiveMin() :
            null;
    }

    /**
     * @return int
     */
    protected function getMaximumRating(): int
    {
        return $this->getFactory()
            ->getProductReviewClient()
            ->getMaximumRating();
    }
}
