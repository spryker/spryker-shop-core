<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class CartNoteWidgetToCartNoteClientBridge implements CartNoteWidgetToCartNoteClientInterface
{
    /**
     * @var \Spryker\Client\CartNote\CartNoteClientInterface
     */
    protected $cartNoteClient;

    /**
     * @param \Spryker\Client\CartNote\CartNoteClientInterface $cartNoteClient
     */
    public function __construct($cartNoteClient)
    {
        $this->cartNoteClient = $cartNoteClient;
    }

    /**
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuoteItem(string $note, string $sku, ?string $groupKey = null): QuoteResponseTransfer
    {
        return $this->cartNoteClient->setNoteToQuoteItem($note, $sku, $groupKey);
    }

    /**
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuote(string $note): QuoteResponseTransfer
    {
        return $this->cartNoteClient->setNoteToQuote($note);
    }
}
