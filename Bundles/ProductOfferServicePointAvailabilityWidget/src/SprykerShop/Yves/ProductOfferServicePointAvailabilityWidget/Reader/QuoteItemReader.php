<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface;

class QuoteItemReader implements QuoteItemReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface
     */
    protected ProductOfferServicePointAvailabilityWidgetToCartClientInterface $cartClient;

    /**
     * @param \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface $cartClient
     */
    public function __construct(
        ProductOfferServicePointAvailabilityWidgetToCartClientInterface $cartClient
    ) {
        $this->cartClient = $cartClient;
    }

    /**
     * @param list<string> $groupKeys
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getItemsFromQuote(array $groupKeys): array
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if ($groupKeys) {
            return $this->extractItemsFromQuoteByGroupKeys($quoteTransfer, $groupKeys);
        }

        return $quoteTransfer->getItems()->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<string> $groupKeys
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsFromQuoteByGroupKeys(
        QuoteTransfer $quoteTransfer,
        array $groupKeys
    ): array {
        $itemTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getGroupKeyOrFail(), $groupKeys, true)) {
                $itemTransfers[] = $itemTransfer;
            }
        }

        return $itemTransfers;
    }
}
