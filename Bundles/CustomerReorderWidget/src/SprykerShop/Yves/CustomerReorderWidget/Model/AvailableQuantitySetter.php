<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;

class AvailableQuantitySetter implements AvailableQuantitySetterInterface
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

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function setAvailableQuantity(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            $spyAvailabilityAbstractTransfer = $this->getAvailabilityAbstractByItemTransfer($item);

            foreach ($spyAvailabilityAbstractTransfer->getSpyAvailabilities() as $spyAvailability) {
                if ($spyAvailability->getSku() !== $item->getSku()) {
                    continue;
                }

                if ($spyAvailability->getIsNeverOutOfStock()) {
                    continue;
                }

                if ($spyAvailability->getQuantity() === 0) {
                    continue;
                }

                if ($spyAvailability->getQuantity() >= $item->getQuantity()) {
                    continue;
                }

                $item->setQuantity($spyAvailability->getQuantity());
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    protected function getAvailabilityAbstractByItemTransfer(ItemTransfer $itemTransfer): SpyAvailabilityAbstractEntityTransfer
    {
        $itemTransfer->requireIdProductAbstract();

        return $this->availabilityStorageClient->getAvailabilityAbstract($itemTransfer->getIdProductAbstract());
    }
}
