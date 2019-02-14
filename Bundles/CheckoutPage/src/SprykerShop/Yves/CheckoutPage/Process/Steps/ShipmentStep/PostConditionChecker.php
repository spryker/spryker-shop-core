<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

class PostConditionChecker implements PostConditionCheckerInterface
{
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
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getExpense() === null) {
                return false;
            }
        }

        return true;
    }
}
