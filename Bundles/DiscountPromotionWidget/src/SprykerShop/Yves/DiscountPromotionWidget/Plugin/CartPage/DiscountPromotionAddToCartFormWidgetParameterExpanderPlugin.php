<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\AddToCartFormWidgetParameterExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class DiscountPromotionAddToCartFormWidgetParameterExpanderPlugin extends AbstractPlugin implements AddToCartFormWidgetParameterExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds form name postfix parameter if a promotion item is set in `ProductViewTransfer`.
     *
     * @api
     *
     * @param array<string, mixed> $formParameters
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formParameters, ProductViewTransfer $productViewTransfer): array
    {
        return $this->getFactory()
            ->createCartFormWidgetParameterExpander()
            ->expand($formParameters, $productViewTransfer);
    }
}
