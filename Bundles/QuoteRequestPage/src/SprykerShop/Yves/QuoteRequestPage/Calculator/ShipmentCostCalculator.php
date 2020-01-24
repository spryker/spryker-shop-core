<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Calculator;

use Generated\Shared\Transfer\QuoteRequestTransfer;

class ShipmentCostCalculator implements ShipmentCostCalculatorInterface
{
    /**
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return int
     */
    public function calculateTotalShipmentCosts(QuoteRequestTransfer $quoteRequestTransfer): int
    {
        $shipmentTotal = 0;
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentTotal += $expenseTransfer->getSumPrice();
        }

        return $shipmentTotal;
    }
}
