<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Grouper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestPage\Extractor\ItemExtractorInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Extractor\ItemExtractorInterface
     */
    protected $itemExtractor;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface
     */
    protected $quoteChecker;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\QuoteRequestPage\Extractor\ItemExtractorInterface $itemExtractor
     * @param \SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface $quoteChecker
     */
    public function __construct(
        QuoteRequestPageToShipmentServiceInterface $shipmentService,
        ItemExtractorInterface $itemExtractor,
        QuoteCheckerInterface $quoteChecker
    ) {
        $this->shipmentService = $shipmentService;
        $this->itemExtractor = $itemExtractor;
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShippingAddress(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteRequestTransfer)) {
            return [];
        }

        return $this->shipmentService
            ->groupItemsByShipment($this->itemExtractor->extractItemsWithShipment($quoteRequestTransfer))
            ->getArrayCopy();
    }
}
