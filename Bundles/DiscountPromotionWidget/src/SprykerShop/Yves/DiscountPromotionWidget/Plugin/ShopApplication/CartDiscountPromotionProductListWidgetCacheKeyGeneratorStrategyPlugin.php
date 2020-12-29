<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\DiscountPromotionWidget\Widget\CartDiscountPromotionProductListWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class CartDiscountPromotionProductListWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
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
    public function getWidgetClassName(): string
    {
        return CartDiscountPromotionProductListWidget::class;
    }
}
