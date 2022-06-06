<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Sanitizer;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ItemSanitizer implements ItemSanitizerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function sanitizeRemunerationAmount(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setRemunerationAmount(null);
        }

        return $itemTransfers;
    }
}
