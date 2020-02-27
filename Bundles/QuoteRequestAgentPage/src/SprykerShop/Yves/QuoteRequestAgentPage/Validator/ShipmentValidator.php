<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestClientInterface;

class ShipmentValidator implements ShipmentValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct(QuoteRequestAgentPageToQuoteRequestClientInterface $quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function validate(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        if (!$this->quoteRequestClient->isQuoteRequestForQuoteWithCustomShipmentPrice($quoteTransfer)) {
            return true;
        }

        return $this->validateItemLevelShipment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateItemLevelShipment(QuoteTransfer $quoteTransfer): bool
    {
        $itemTransfersWithShipment = array_filter(
            $quoteTransfer->getItems()->getArrayCopy(),
            function (ItemTransfer $itemTransfer): bool {
                return $itemTransfer->getShipment() && $itemTransfer->getShipment()->getMethod();
            }
        );

        return !$itemTransfersWithShipment || count($itemTransfersWithShipment) === count($quoteTransfer->getItems());
    }
}
