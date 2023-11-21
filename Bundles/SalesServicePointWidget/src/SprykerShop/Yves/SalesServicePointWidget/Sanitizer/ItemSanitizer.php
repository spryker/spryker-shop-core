<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesServicePointWidget\Sanitizer;

class ItemSanitizer implements ItemSanitizerInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function sanitizeServicePoint(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setSalesOrderItemServicePoint();
        }

        return $itemTransfers;
    }
}
