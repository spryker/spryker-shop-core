<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

class PostConditionChecker implements PostConditionCheckerInterface
{
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(AbstractTransfer $quoteTransfer): bool
    {
        return $this->isShipmentSet($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getItems()->count() === 0) {
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || !$this->checkShipmentExpenseSetInQuote($quoteTransfer, $itemTransfer->getShipment())
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    protected function checkShipmentExpenseSetInQuote(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): bool
    {
        $itemShipmentKey = $this->getItemShipmentKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentKey = $this->getItemShipmentKey($expenseTransfer->getShipment());
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE
                && $expenseShipmentKey === $itemShipmentKey
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function getItemShipmentKey(ShipmentTransfer $shipmentTransfer): string
    {
        return md5(sprintf(
            static::SHIPMENT_TRANSFER_KEY_PATTERN,
            $shipmentTransfer->getMethod() ? $shipmentTransfer->getMethod()->getIdShipmentMethod() : '',
            $shipmentTransfer->getShippingAddress() ? $shipmentTransfer->getShippingAddress()->serialize() : '',
            $shipmentTransfer->getRequestedDeliveryDate()
        ));
    }
}
