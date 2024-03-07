<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Dependency\Client;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;

class PaymentPageToSalesClientBridge implements PaymentPageToSalesClientInterface
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
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        if (!method_exists($this->salesClient, 'cancelOrder')) {
            return (new OrderCancelResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())
                    ->setValue('SalesClient::cancelOrder() is not implemented in the current version of the Sales module. Please update the module to the version 11.45.0 or higher.'));
        }

        return $this->salesClient->cancelOrder($orderCancelRequestTransfer);
    }
}
