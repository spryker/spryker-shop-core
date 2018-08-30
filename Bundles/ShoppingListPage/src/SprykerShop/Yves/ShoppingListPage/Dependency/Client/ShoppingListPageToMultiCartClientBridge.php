<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class ShoppingListPageToMultiCartClientBridge implements ShoppingListPageToMultiCartClientInterface
{
    /**
     * @var \Spryker\Client\MultiCart\MultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Client\MultiCart\MultiCartClientInterface $multiCartClient
     */
    public function __construct($multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @param int $idQuote
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        return $this->multiCartClient->findQuoteById($idQuote);
    }
}
