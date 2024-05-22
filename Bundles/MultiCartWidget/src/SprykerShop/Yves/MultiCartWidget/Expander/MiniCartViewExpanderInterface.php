<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Expander;

use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MiniCartViewExpanderInterface
{
    /**
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
