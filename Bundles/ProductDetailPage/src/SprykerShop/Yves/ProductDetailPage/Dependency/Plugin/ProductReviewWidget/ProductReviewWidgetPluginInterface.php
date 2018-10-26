<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReviewWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductReviewWidget\Widget\ProductDetailPageReviewWidget instead.
 */
interface ProductReviewWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductReviewWidgetPlugin';

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void;
}
