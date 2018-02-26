<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;

class QuoteWriter implements QuoteWriterInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient
    ) {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function fill(OrderTransfer $orderTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer = $this->setShipments($orderTransfer, $quoteTransfer);

        $this->cartClient->storeQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipments(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$orderTransfer->getShipmentMethods()) {
            return $quoteTransfer;
        }
        foreach ($orderTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $shipmentTransfer = new ShipmentTransfer();
            $shipmentTransfer->setShipmentSelection($shipmentMethodTransfer->getIdShipmentMethod());
            $shipmentTransfer->setMethod($shipmentMethodTransfer);

            $quoteTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}
