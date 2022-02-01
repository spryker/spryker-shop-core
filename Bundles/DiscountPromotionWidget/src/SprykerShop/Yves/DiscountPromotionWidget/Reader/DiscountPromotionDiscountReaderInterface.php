<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionDiscountReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, array<string>>
     */
    public function getAbstractSkusGroupedByIdDiscount(QuoteTransfer $quoteTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\DiscountTransfer>
     */
    public function getUniqueDiscounts(QuoteTransfer $quoteTransfer): array;
}
