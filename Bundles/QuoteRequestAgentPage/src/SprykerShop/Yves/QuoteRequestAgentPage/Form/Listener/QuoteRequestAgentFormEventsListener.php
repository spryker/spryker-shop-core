<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form\Listener;

use Symfony\Component\Form\FormEvent;

class QuoteRequestAgentFormEventsListener implements QuoteRequestAgentFormEventsListenerInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function copySubmittedShipmentMethodPricesToItemShipmentMethods(FormEvent $event): FormEvent
    {
        /** @var \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer */
        $shipmentGroupTransfer = $event->getData();

        $shipmentMethodSourcePrice = $shipmentGroupTransfer->getShipment()->getMethod()->getSourcePrice();

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->getMethod()->setSourcePrice($shipmentMethodSourcePrice);
        }

        $event->setData($shipmentGroupTransfer);

        return $event;
    }

    /**
     * @deprecated Will be removed without replacement. BC-reason only.
     *
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function copySubmittedItemShipmentMethodPricesToQuoteShipmentMethod(FormEvent $event): FormEvent
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer */
        $quoteRequestVersionTransfer = $event->getForm()->getParent()->getParent()->getData();
        /** @var \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer */
        $shipmentGroupTransfer = $event->getData();

        $quoteShipmentTransfer = $quoteRequestVersionTransfer->getQuote()->getShipment();

        if (!$quoteShipmentTransfer || !$quoteShipmentTransfer->getMethod()) {
            return $event;
        }

        $itemShipmentTransfer = $shipmentGroupTransfer->getShipment();

        if (!$itemShipmentTransfer || !$itemShipmentTransfer->getMethod()) {
            return $event;
        }

        $itemShipmentMethodSourcePriceTransfer = $itemShipmentTransfer->getMethod()->getSourcePrice();
        $quoteShipmentTransfer->getMethod()->setSourcePrice($itemShipmentMethodSourcePriceTransfer);

        $event->setData($shipmentGroupTransfer);

        return $event;
    }
}
