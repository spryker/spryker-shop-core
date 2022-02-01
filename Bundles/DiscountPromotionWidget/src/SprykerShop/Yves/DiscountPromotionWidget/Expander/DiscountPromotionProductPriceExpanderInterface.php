<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;

interface DiscountPromotionProductPriceExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandWithDiscountPromotionalPrice(
        PromotionItemTransfer $promotionItemTransfer,
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer;
}
