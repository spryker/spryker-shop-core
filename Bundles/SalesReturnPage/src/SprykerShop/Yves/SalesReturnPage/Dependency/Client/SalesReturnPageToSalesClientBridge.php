<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Dependency\Client;

use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;

class SalesReturnPageToSalesClientBridge implements SalesReturnPageToSalesClientInterface
{
    /**
     * @var \Spryker\Client\Sales\SalesClientInterface
     */
    protected $salesClient;

    /**
     * @param \Spryker\Client\Sales\SalesClientInterface $salesClient
     */
    public function __construct($salesClient)
    {
        $this->salesClient = $salesClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        return $this->salesClient->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);
    }
}
