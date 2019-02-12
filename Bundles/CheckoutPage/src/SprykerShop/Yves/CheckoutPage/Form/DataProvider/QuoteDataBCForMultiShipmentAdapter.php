<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * @deprecated Will be removed in next major release.
 */
class QuoteDataBCForMultiShipmentAdapter implements QuoteDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function adapt(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->assertThatQuoteHasNoShipment($quoteTransfer)) {
            $quoteTransfer = $this->setQuoteShipment($quoteTransfer);
        }

        if ($this->assertThatQuoteHasNoShipmentMethod($quoteTransfer)) {
            $quoteTransfer = $this->setQuoteShipmentMethod($quoteTransfer);
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShipmentMethod($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentMethodForBC($itemTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setShipment(new ShipmentTransfer());

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment()->getMethod() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShipmentMethod(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->getShipment()->setMethod(new ShipmentMethodTransfer());

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShipmentMethod(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getMethod() !== null);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        return $quoteTransfer->getShipment();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentMethodTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getMethod() !== null) {
            return $itemTransfer->getShipment()->getMethod();
        }

        return $quoteTransfer->getShipment()->getMethod();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentMethodForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $shipmentMethodTransfer = $this->getShipmentMethodTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);
    }
}
