<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Calculator;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface ShipmentCostCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return int
     */
    public function calculateTotalShipmentCosts(QuoteRequestTransfer $quoteRequestTransfer): int;
}
