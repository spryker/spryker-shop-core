<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

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
     * @return void
     */
    public function fill(OrderTransfer $orderTransfer): void
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer = $this->setShippingAddress($orderTransfer, $quoteTransfer);
        $quoteTransfer = $this->setBillingAddress($orderTransfer, $quoteTransfer);
        if ($quoteTransfer->getBillingAddress()->toArray() == $quoteTransfer->getShippingAddress()->toArray()) {
            $quoteTransfer->setBillingSameAsShipping(true);
        }
        $quoteTransfer = $this->setShipment($orderTransfer, $quoteTransfer);

        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShippingAddress(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$orderTransfer->getShippingAddress()) {
            return $quoteTransfer;
        }

        $shippingAddressTransfer = $orderTransfer->getShippingAddress();
        $shippingAddressTransfer->setFKCustomer($orderTransfer->getFkCustomer());

        $idCustomerAddress = $this->getIdCustomerAddress($orderTransfer, $shippingAddressTransfer);
        $shippingAddressTransfer->setIdCustomerAddress($idCustomerAddress);

        $quoteTransfer->setShippingAddress($shippingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setBillingAddress(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$orderTransfer->getBillingAddress()) {
            return $quoteTransfer;
        }

        $billingAddressTransfer = $orderTransfer->getBillingAddress();
        $billingAddressTransfer->setFKCustomer($orderTransfer->getFkCustomer());

        $idCustomerAddress = $this->getIdCustomerAddress($orderTransfer, $billingAddressTransfer);
        $billingAddressTransfer->setIdCustomerAddress($idCustomerAddress);

        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipment(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$orderTransfer->getShipmentMethods()) {
            return $quoteTransfer;
        }
        $shipmentMethodTransfer = $orderTransfer->getShipmentMethods()[0];
        $idShipmentMethod = $this->getIdShipmentMethod($quoteTransfer, $orderTransfer, $shipmentMethodTransfer);
        $shipmentMethodTransfer->setIdShipmentMethod($idShipmentMethod);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setShipmentSelection($idShipmentMethod);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $quoteTransfer->setShipment($shipmentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return int|null
     */
    protected function getIdCustomerAddress(OrderTransfer $orderTransfer, AddressTransfer $addressTransfer): ?int
    {
        $addressTransfer = clone $addressTransfer;
        foreach ($orderTransfer->getCustomer()->getAddresses()->getAddresses() as $currentAddressTransfer) {
            $currentAddressTransfer = clone $currentAddressTransfer;
            $idCustomerAddress = $currentAddressTransfer->getIdCustomerAddress();

            $currentAddressTransfer->setIdCustomerAddress(null);
            $currentAddressArray = $currentAddressTransfer->toArray();
            $addressTransfer->setIdSalesOrderAddress(null);
            $addressArray = $addressTransfer->toArray();

            if ($currentAddressArray == $addressArray) {
                return $idCustomerAddress;
            }
        }

        return null;
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
    ): ?int
    {
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($orderTransfer->getCurrencyIsoCode());
        $quoteTransfer->setCurrency($currencyTransfer);

        $shipmentMethodsTransfer = $this->shipmentClient
            ->getMethods($quoteTransfer)
            ->getMethods();
        $quoteTransfer->setCurrency(null);

        foreach ($shipmentMethodsTransfer as $currentMethodTransfer) {
            if ($currentMethodTransfer->getName() === $shipmentMethodTransfer->getName()) {
                return $currentMethodTransfer->getIdShipmentMethod();
            }
        }

        return null;
    }
}
