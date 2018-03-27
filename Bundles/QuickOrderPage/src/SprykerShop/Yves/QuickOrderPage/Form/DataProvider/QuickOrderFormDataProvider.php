<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Form\OrderItemEmbeddedForm;

class QuickOrderFormDataProvider implements QuickOrderFormDataProviderInterface
{
    /**
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function getQuickOrderTransfer(array $orderItems = [], $emptyOrderItemsNumber = null): QuickOrderTransfer
    {
        $quickOrder = new QuickOrderTransfer();
        $orderItemCollection = new ArrayObject($orderItems);
        $quickOrder->setItems($orderItemCollection);

        if ($orderItemCollection->count() === 0) {
            $this->appendEmptyOrderItems($quickOrder, $emptyOrderItemsNumber);
        }

        return $quickOrder;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     * @param int $itemsNumber
     *
     * @return void
     */
    public function appendEmptyOrderItems(QuickOrderTransfer $quickOrder, int $itemsNumber = 1): void
    {
        $orderItemCollection = $quickOrder->getItems();
        for ($i = 0; $i < $itemsNumber; $i++) {
            $orderItemCollection->append(new QuickOrderItemTransfer());
        }
    }

    /**
     * @param array $formDataItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function getOrderItemsFromFormData(array $formDataItems): array
    {
        $orderItems = [];

        foreach ($formDataItems as $item) {
            $orderItems[] = (new QuickOrderItemTransfer())
                ->setSku($item[OrderItemEmbeddedForm::FILED_SKU])
                ->setQty($item[OrderItemEmbeddedForm::FILED_QTY] ? (int)$item[OrderItemEmbeddedForm::FILED_QTY] : null);
        }

        return $orderItems;
    }
}
