<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Extractor;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface;

class ItemExtractor implements ItemExtractorInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface
     */
    protected $quoteChecker;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Checker\QuoteCheckerInterface $quoteChecker
     */
    public function __construct(QuoteCheckerInterface $quoteChecker)
    {
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function extractItemsWithShipment(QuoteTransfer $quoteTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return $quoteTransfer->getItems()->getArrayCopy();
        }

        $itemTransfersWithShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->quoteChecker->isItemWithShipmentAddress($itemTransfer)) {
                $itemTransfersWithShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithShipment;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function extractItemsWithoutShipment(QuoteTransfer $quoteTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteTransfer)) {
            return [];
        }

        $itemTransfersWithoutShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->quoteChecker->isItemWithShipmentAddress($itemTransfer)) {
                $itemTransfersWithoutShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithoutShipment;
    }
}
