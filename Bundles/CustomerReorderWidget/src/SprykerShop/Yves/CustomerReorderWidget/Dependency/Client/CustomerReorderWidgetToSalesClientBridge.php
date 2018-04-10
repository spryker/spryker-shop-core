<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

use Generated\Shared\Transfer\OrderTransfer;

class CustomerReorderWidgetToSalesClientBridge implements CustomerReorderWidgetToSalesClientInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->salesClient->getOrderDetails($orderTransfer);
    }
}
