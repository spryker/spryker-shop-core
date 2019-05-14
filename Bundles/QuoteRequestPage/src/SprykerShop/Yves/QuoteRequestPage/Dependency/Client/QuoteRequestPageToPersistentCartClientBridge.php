<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

class QuoteRequestPageToPersistentCartClientBridge implements QuoteRequestPageToPersistentCartClientInterface
{
    /**
     * @var \Spryker\Client\PersistentCart\PersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Client\PersistentCart\PersistentCartClientInterface $persistentCartClient
     */
    public function __construct($persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reloadQuoteForCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->persistentCartClient->reloadQuoteForCustomer($customerTransfer);
    }
}
