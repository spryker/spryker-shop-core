<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class SharedCartWidgetToSharedCartClientBridge implements SharedCartWidgetToSharedCartClientInterface
{
    /**
     * @var \Spryker\Client\SharedCart\SharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @param \Spryker\Client\SharedCart\SharedCartClientInterface $sharedCartClient
     */
    public function __construct($sharedCartClient)
    {
        $this->sharedCartClient = $sharedCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->sharedCartClient->getQuoteAccessLevel($quoteTransfer);
    }
}
