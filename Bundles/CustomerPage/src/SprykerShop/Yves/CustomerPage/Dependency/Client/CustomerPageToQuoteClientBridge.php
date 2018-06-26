<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function __construct(QuoteClientInterface $quoteClient)
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
