<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

use Spryker\Client\Quote\QuoteClientInterface;

class CustomerPageToQuoteClientBridge implements CustomerPageToQuoteClientInteface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * CustomerPageToQuoteClientBridge constructor.
     *
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->quoteClient->getQuote();
    }
}
