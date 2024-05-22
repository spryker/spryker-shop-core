<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Implement this plugin interface to expand the rendered mini cart HTML before returning it in response.
 */
interface MiniCartViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided `MiniCartView.content`.
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
    ): MiniCartViewTransfer;
}
