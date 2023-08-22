<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentPage\Sanitizer;

class ItemSanitizer implements ItemSanitizerInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function sanitizeShipment(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setShipment(null);
        }

        return $itemTransfers;
    }
}
