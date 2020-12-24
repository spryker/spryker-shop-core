<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\DiscountPromotionWidget\Widget\CartDiscountPromotionProductListWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class CartDiscountPromotionProductListWidgetCacheKeyGeneratorPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Disables caching for the `CartDiscountPromotionProductListWidget`.
     *
     * @api
     *
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelatedWidgetClassName(): string
    {
        return CartDiscountPromotionProductListWidget::class;
    }
}
