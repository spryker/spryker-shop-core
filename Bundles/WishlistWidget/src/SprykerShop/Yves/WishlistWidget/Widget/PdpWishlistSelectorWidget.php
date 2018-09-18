<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class PdpWishlistSelectorWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('product', $productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'PdpWishlistSelectorWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@WishlistWidget/views/pdp-wishlist-selector/pdp-wishlist-selector.twig';
    }
}
