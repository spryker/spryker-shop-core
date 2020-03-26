<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandShipmentForBundleItems(QuoteTransfer $quoteTransfer, array $bundleItems): QuoteTransfer;
}
