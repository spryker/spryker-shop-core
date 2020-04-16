<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Grouper;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingAddress(QuoteTransfer $quoteTransfer): array;
}
