<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Handler;

use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToCustomerClientInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderCancelHandler implements OrderCancelHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        OrderCancelWidgetToCustomerClientInterface $customerClient
    ) {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(Request $request): OrderCancelResponseTransfer
    {
        // TODO: Implement cancelOrder() method.

        return new OrderCancelResponseTransfer();
    }
}
