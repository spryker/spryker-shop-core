<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Reader;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;

class ReturnReader implements ReturnReaderInterface
{
    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface $salesReturnClient
     */
    public function __construct(SalesReturnPageToSalesReturnClientInterface $salesReturnClient)
    {
        $this->salesReturnClient = $salesReturnClient;
    }

    /**
     * @param string $returnReference
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    public function findReturnByReference(string $returnReference, string $customerReference): ?ReturnTransfer
    {
        $returnFilterTransfer = (new ReturnFilterTransfer())
            ->setReturnReference($returnReference)
            ->setCustomerReference($customerReference);

        return $this->salesReturnClient
            ->getReturns($returnFilterTransfer)
            ->getReturns()
            ->getIterator()
            ->current();
    }
}
