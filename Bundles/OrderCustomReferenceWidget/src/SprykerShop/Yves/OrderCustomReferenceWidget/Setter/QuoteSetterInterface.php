<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Setter;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteSetterInterface
{
    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuote(
        string $orderCustomReference,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer;
}
