<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\ProductColorGroupWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductColorGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class AddToCartUrlProductViewExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands product view data transfer object with an add to cart URL.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        return $this->getFactory()
            ->getCartClient()
            ->expandProductViewWithCartData($productViewTransfer);
    }
}
