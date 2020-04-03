<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface $salesClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        CustomerReorderWidgetToSalesClientInterface $salesClient,
        CustomerReorderWidgetToCustomerClientInterface $customerClient
    ) {
        $this->salesClient = $salesClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        $orderTransfer = $this->prepareOrderTransferForSearch($idSalesOrder);

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function hasIncompatibleItems(OrderTransfer $orderTransfer): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() || $itemTransfer->getAmountSalesUnit()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function prepareOrderTransferForSearch(int $idSalesOrder): OrderTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setCustomer($customerTransfer);

        return $orderTransfer;
    }
}
