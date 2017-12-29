<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\CatalogPage;

use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\ProductReviewWidget\ProductRatingFilterWidgetPluginInterface;
use SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductReviewWidgetFactory getFactory()
 */
class ProductRatingFilterWidgetPlugin extends AbstractWidgetPlugin implements ProductRatingFilterWidgetPluginInterface
{

    /**
     * @param RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param Request $request
     *
     * @return void
     */
    public function initialize(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request): void
    {
        $this
            ->addParameter('filter', $rangeSearchResultTransfer)
            ->addParameter('filterValue', $this->getFilterValue($rangeSearchResultTransfer, $request))
            ->addParameter('maximumRating', $this->getMaximumRating());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/_catalog-page/rating-filter.twig';
    }

    /**
     * @param RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param Request $request
     *
     * @return float|null
     */
    protected function getFilterValue(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request)
    {
        return $request->query->has($rangeSearchResultTransfer->getConfig()->getParameterName()) ?
            $rangeSearchResultTransfer->getActiveMin() :
            null;
    }

    /**
     * @return int
     */
    protected function getMaximumRating()
    {
        return $this->getFactory()
            ->getProductReviewClient()
            ->getMaximumRating();
    }
}
