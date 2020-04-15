<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Grouper;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Extractor\ItemExtractorInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Extractor\ItemExtractorInterface
     */
    protected $itemExtractor;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface
     */
    protected $quoteChecker;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Extractor\ItemExtractorInterface $itemExtractor
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface $quoteChecker
     */
    public function __construct(
        QuoteRequestAgentPageToShipmentServiceInterface $shipmentService,
        ItemExtractorInterface $itemExtractor,
        QuoteCheckerInterface $quoteChecker
    ) {
        $this->shipmentService = $shipmentService;
        $this->itemExtractor = $itemExtractor;
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingAddress(QuoteTransfer $quoteTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return [];
        }

        return $this->shipmentService
            ->groupItemsByShipment($this->itemExtractor->extractItemsWithShipmentAddress($quoteTransfer))
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingMethod(QuoteTransfer $quoteTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return [];
        }

        return $this->shipmentService
            ->groupItemsByShipment($this->itemExtractor->extractItemsWithShipmentMethod($quoteTransfer))
            ->getArrayCopy();
    }
}
