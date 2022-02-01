<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

class DiscountPromotionDiscountReader implements DiscountPromotionDiscountReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, array<string>>
     */
    public function getAbstractSkusGroupedByIdDiscount(QuoteTransfer $quoteTransfer): array
    {
        $abstractSkusGroupedByIdDiscount = [];

        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $abstractSkusGroupedByIdDiscount[$promotionItemTransfer->getDiscountOrFail()->getIdDiscountOrFail()][] = $promotionItemTransfer->getAbstractSkuOrFail();
        }

        return $abstractSkusGroupedByIdDiscount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\DiscountTransfer>
     */
    public function getUniqueDiscounts(QuoteTransfer $quoteTransfer): array
    {
        $discountTransfers = [];

        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $discountTransfer = $promotionItemTransfer->getDiscountOrFail();
            if (!array_key_exists($discountTransfer->getIdDiscountOrFail(), $discountTransfers)) {
                $discountTransfers[$discountTransfer->getIdDiscountOrFail()] = $discountTransfer;
            }
        }

        return $discountTransfers;
    }
}
