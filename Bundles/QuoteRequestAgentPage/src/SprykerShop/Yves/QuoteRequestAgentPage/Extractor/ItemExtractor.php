<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Extractor;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface;

class ItemExtractor implements ItemExtractorInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface
     */
    protected $quoteChecker;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Checker\QuoteCheckerInterface $quoteChecker
     */
    public function __construct(QuoteCheckerInterface $quoteChecker)
    {
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function extractItemsWithShipment(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteRequestTransfer)) {
            return $quoteTransfer->getItems()->getArrayCopy();
        }

        $itemTransfersWithShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->isItemWithShipment($itemTransfer)) {
                $itemTransfersWithShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithShipment;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function extractItemsWithoutShipment(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        if ($this->quoteChecker->isQuoteLevelShipmentUsed($quoteRequestTransfer)) {
            return [];
        }

        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();
        $itemTransfersWithoutShipment = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemWithShipment($itemTransfer)) {
                $itemTransfersWithoutShipment[] = $itemTransfer;
            }
        }

        return $itemTransfersWithoutShipment;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemWithShipment(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment()
            && $itemTransfer->getShipment()->getMethod()
            && $itemTransfer->getShipment()->getShippingAddress();
    }
}
