<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\CartPage;

use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\MiniCartViewExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartBlockMiniCartViewExpanderPlugin extends AbstractPlugin implements MiniCartViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided `MiniCartView.content` with mini cart view.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MiniCartViewTransfer $miniCartViewTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MiniCartViewTransfer
     */
    public function expand(
        MiniCartViewTransfer $miniCartViewTransfer,
        QuoteTransfer $quoteTransfer
    ): MiniCartViewTransfer {
        return $this->getFactory()
            ->createMiniCartViewExpander()
            ->expand($miniCartViewTransfer, $quoteTransfer);
    }
}
