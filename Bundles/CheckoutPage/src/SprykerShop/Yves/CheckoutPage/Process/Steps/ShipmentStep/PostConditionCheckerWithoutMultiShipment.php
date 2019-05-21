<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep\PostConditionChecker instead.
 */
class PostConditionCheckerWithoutMultiShipment implements PostConditionCheckerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(AbstractTransfer $quoteTransfer): bool
    {
        if ($this->hasOnlyGiftCardItems($quoteTransfer)) {
            return true;
        }

        if (!$this->isShipmentSet($quoteTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment()) {
            return false;
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                return $quoteTransfer->getShipment()->getShipmentSelection() !== null;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasOnlyGiftCardItems(QuoteTransfer $quoteTransfer): bool
    {
        $onlyGiftCardItems = true;
        foreach ($quoteTransfer->getItems() as $item) {
            $isGiftCard = $item->getGiftCardMetadata() ? $item->getGiftCardMetadata()->getIsGiftCard() : false;
            $onlyGiftCardItems &= $isGiftCard;
        }

        return (bool)$onlyGiftCardItems;
    }
}
