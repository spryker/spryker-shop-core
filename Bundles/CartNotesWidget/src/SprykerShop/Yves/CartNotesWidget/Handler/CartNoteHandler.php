<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Handler;

use SprykerShop\Yves\CartNotesWidget\Dependency\Client\CartNotesWidgetToCartClientInterface;

class CartNoteHandler implements CartNoteHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CartNotesWidget\Dependency\Client\CartNotesWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\CartNotesWidget\Dependency\Client\CartNotesWidgetToCartClientInterface $cartClient
     */
    public function __construct(CartNotesWidgetToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $note
     *
     * @return void
     */
    public function setNoteToQuote($note)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        $quoteTransfer->setCartNote($note);

        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function setNoteToQuoteItem($note, $sku, $groupKey = null)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        $quoteItemTransfer = $this->findItem($quoteTransfer, $sku, $groupKey);

        if ($quoteItemTransfer) {
            $quoteItemTransfer->setCartNote($note);
            $this->cartClient->storeQuote($quoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItem($quoteTransfer, $sku, $groupKey = null)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
