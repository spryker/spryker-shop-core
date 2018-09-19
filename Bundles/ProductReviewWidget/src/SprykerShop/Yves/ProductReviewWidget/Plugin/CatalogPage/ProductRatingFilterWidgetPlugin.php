<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\CatalogPage;

use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\ProductReviewWidget\ProductRatingFilterWidgetPluginInterface;
use SprykerShop\Yves\ProductReviewWidget\Widget\ProductRatingFilterWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use \SprykerShop\Yves\ProductReviewWidget\Widget\ProductRatingFilterWidget instead.
 *
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductRatingFilterWidgetPlugin extends AbstractWidgetPlugin implements ProductRatingFilterWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request): void
    {
        $widget = new ProductRatingFilterWidget($rangeSearchResultTransfer, $request);

        $this->parameters = $widget->getParameters();
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
        return ProductRatingFilterWidget::getTemplate();
    }

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
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
