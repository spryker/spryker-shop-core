<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\Listener;

use SprykerShop\Yves\SalesReturnPage\Form\ReturnItemsForm;
use Symfony\Component\Form\FormEvent;

class ReturnItemsFormEventsListener implements ReturnItemsFormEventsListenerInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function mappReturnItemTransfersUuid(FormEvent $event): FormEvent
    {
        $returnItemData = $event->getData();

        if ($returnItemData[ReturnItemsForm::FIELD_UUID]) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $returnItemData[ReturnItemsForm::FIELD_ORDER_ITEM];

            $returnItemData[ReturnItemsForm::FIELD_UUID] = $itemTransfer->getUuid();
        }

        $event->setData($returnItemData);

        return $event;
    }
}
