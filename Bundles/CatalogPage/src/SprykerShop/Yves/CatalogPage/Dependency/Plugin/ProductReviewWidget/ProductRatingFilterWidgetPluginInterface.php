<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Plugin\ProductReviewWidget;

use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Symfony\Component\HttpFoundation\Request;

interface ProductRatingFilterWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductRatingFilterWidgetPlugin';

    /**
     * @param RangeSearchResultTransfer $rangeSearchResultTransfer
     * @param Request $request
     *
     * @return void
     */
    public function initialize(RangeSearchResultTransfer $rangeSearchResultTransfer, Request $request): void;
}
