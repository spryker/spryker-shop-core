<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;

class CustomerPageToSalesReturnClientBridge implements CustomerPageToSalesReturnClientInterface
{
    /**
     * @var \Spryker\Client\SalesReturn\SalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @param \Spryker\Client\SalesReturn\SalesReturnClientInterface $salesReturnClient
     */
    public function __construct($salesReturnClient)
    {
        $this->salesReturnClient = $salesReturnClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        return $this->salesReturnClient->getReturns($returnFilterTransfer);
    }
}
