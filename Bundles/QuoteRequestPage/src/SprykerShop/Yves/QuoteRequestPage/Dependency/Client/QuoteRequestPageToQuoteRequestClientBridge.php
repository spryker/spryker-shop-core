<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteRequestPageToQuoteRequestClientBridge implements QuoteRequestPageToQuoteRequestClientInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\QuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequest\QuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct($quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function create(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->create($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(
        CustomerTransfer $customerTransfer
    ): QuoteRequestCollectionTransfer {
        return $this->quoteRequestClient->getCustomerQuoteRequestCollection($customerTransfer);
    }
}
