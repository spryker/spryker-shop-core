<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\Listener;

use Generated\Shared\Transfer\ReturnItemTransfer;
use Symfony\Component\Form\FormEvent;

class ReturnItemsFormEventsListener implements ReturnItemsFormEventsListenerInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function mapReturnItemTransfersUuid(FormEvent $event): FormEvent
    {
        $returnItemData = $event->getData();

        if ($returnItemData[ReturnItemTransfer::UUID]) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $returnItemData[ReturnItemTransfer::ORDER_ITEM];

            $returnItemData[ReturnItemTransfer::UUID] = $itemTransfer->getUuid();
        }

        $event->setData($returnItemData);

        return $event;
    }
}
