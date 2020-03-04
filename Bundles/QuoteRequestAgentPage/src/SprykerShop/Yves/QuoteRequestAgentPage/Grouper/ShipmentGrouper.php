<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Grouper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Filter\ItemsFilterInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Filter\ItemsFilterInterface
     */
    protected $itemsFilter;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Filter\ItemsFilterInterface $itemsFilter
     */
    public function __construct(
        QuoteRequestAgentPageToShipmentServiceInterface $shipmentService,
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
