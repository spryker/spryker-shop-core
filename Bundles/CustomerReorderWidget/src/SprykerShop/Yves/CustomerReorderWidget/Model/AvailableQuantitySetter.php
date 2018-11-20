<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\OrderTransfer;

class AvailableQuantitySetter implements AvailableQuantitySetterInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Model\AvailabilityReaderInterface
     */
    protected $availabilityReader;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Model\AvailabilityReaderInterface $availabilityReader
     */
    public function __construct(AvailabilityReaderInterface $availabilityReader)
    {
        $this->availabilityReader = $availabilityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function setAvailableQuantity(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            $spyAvailabilityAbstractTransfer = $this->availabilityReader->getAvailabilityAbstractByItemTransfer($item);

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
}
