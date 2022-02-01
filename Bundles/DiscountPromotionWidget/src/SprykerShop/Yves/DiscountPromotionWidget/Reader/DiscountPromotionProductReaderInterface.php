<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;

interface DiscountPromotionProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getPromotionProducts(QuoteTransfer $quoteTransfer, Request $request, string $locale): array;
}
