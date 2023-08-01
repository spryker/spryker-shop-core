<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Cleaner;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function cleanShipmentTypeUuidFromQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
