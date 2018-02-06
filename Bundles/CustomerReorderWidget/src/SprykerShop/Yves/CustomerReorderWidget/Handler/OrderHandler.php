<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;

class OrderHandler implements OrderHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface $salesClient
     */
    public function __construct(
        CustomerReorderWidgetToSalesClientInterface $salesClient
    ) {
        $this->salesClient = $salesClient;
    }

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function getOrderTransfer(int $idSalesOrder, CustomerTransfer $customerTransfer): ?OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

        return $orderTransfer;
    }
}
