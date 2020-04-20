<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

use Exception;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class CustomerPageToSalesClientBridge implements CustomerPageToSalesClientInterface
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
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedOrder(OrderListTransfer $orderListTransfer)
    {
        return $this->salesClient->getPaginatedOrder($orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverview(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        return $this->salesClient->getPaginatedCustomerOrdersOverview($orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->salesClient->getOrderDetails($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param bool|null $isOrderSearchEnabled
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer, ?bool $isOrderSearchEnabled = true): OrderListTransfer
    {
        if (!$isOrderSearchEnabled || !method_exists($this->salesClient, 'searchOrders')) {
            throw new Exception('Order Search works since v11.*');
        }

        return $this->salesClient->searchOrders($orderListTransfer);
    }
}
