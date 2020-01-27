<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Grouper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(QuoteRequestPageToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingAddress(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        if ($this->hasItemsWithEmptyShippingAddress($quoteTransfer)) {
            return [];
        }

        return $this->shipmentService
            ->groupItemsByShipment($quoteTransfer->getItems())
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemsWithEmptyShippingAddress(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment() || !$itemTransfer->getShipment()->getShippingAddress()) {
                return true;
            }
        }

        return false;
    }
}
