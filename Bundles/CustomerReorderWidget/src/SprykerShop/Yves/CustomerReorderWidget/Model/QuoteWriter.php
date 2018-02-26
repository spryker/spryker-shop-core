<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToShipmentClientInterface;

class QuoteWriter implements QuoteWriterInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToShipmentClientInterface $shipmentClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        CustomerReorderWidgetToShipmentClientInterface $shipmentClient
    ) {
        $this->cartClient = $cartClient;
        $this->shipmentClient = $shipmentClient;
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
            $idShipmentMethod = $this->getIdShipmentMethod($quoteTransfer, $orderTransfer, $shipmentMethodTransfer);

            if (!$idShipmentMethod) {
                continue;
            }

            $shipmentMethodTransfer->setIdShipmentMethod($idShipmentMethod);
            $shipmentTransfer = new ShipmentTransfer();
            $shipmentTransfer->setShipmentSelection($idShipmentMethod);
            $shipmentTransfer->setMethod($shipmentMethodTransfer);

            $quoteTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return int|null
     */
    protected function getIdShipmentMethod(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): ?int {
        $notEnoughData = !$orderTransfer->getCurrencyIsoCode() || !$shipmentMethodTransfer->getName();

        if ($notEnoughData) {
            return null;
        }

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($orderTransfer->getCurrencyIsoCode());
        $quoteTransfer->setCurrency($currencyTransfer);

        $shipmentMethodTransfers = $this->shipmentClient
            ->getMethods($quoteTransfer)
            ->getMethods();
        $quoteTransfer->setCurrency(null);

        foreach ($shipmentMethodTransfers as $currentMethodTransfer) {
            if ($currentMethodTransfer->getName() === $shipmentMethodTransfer->getName()) {
                return $currentMethodTransfer->getIdShipmentMethod();
            }
        }

        return null;
    }
}
