<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Grouper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestPage\Filter\ItemsFilterInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Filter\ItemsFilterInterface
     */
    protected $itemsFilter;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\QuoteRequestPage\Filter\ItemsFilterInterface $itemsFilter
     */
    public function __construct(
        QuoteRequestPageToShipmentServiceInterface $shipmentService,
        ItemsFilterInterface $itemsFilter
    ) {
        $this->shipmentService = $shipmentService;
        $this->itemsFilter = $itemsFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingAddress(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        if ($this->itemsFilter->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return [];
        }

        return $this->shipmentService
            ->groupItemsByShipment($this->itemsFilter->getItemsWithShipment($quoteTransfer))
            ->getArrayCopy();
    }
}
