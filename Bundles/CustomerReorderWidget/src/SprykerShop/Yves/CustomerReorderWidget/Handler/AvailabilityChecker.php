<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;

class AvailabilityChecker implements AvailabilityCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct(CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    public function checkBySalesItem(ItemTransfer $itemTransfer): bool
    {
        $itemTransfer
            ->requireIdProductAbstract()
            ->requireSku();

        $availabilityTransfer = $this->availabilityStorageClient
            ->getProductAvailabilityByIdProductAbstract($itemTransfer->getIdProductAbstract());
        $productsAvailability = $availabilityTransfer->getConcreteProductAvailableItems();

        if (!$productsAvailability) {
            return false;
        }
        if (empty($productsAvailability[$itemTransfer->getSku()])) {
            return false;
        }

        $availability = $availabilityTransfer->getConcreteProductAvailableItems()[$itemTransfer->getSku()];

        return $availability;
    }
}
