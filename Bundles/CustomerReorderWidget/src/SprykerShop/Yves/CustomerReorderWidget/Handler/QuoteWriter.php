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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function fill(OrderTransfer $orderTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer = $this->setShippingAddress($orderTransfer, $quoteTransfer);
        $quoteTransfer = $this->setBillingAddress($orderTransfer, $quoteTransfer);
        $quoteTransfer = $this->setSameAddresses($quoteTransfer);
        $quoteTransfer = $this->setShipment($orderTransfer, $quoteTransfer);

        $this->cartClient->storeQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShippingAddress(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $notEnoughData = !$orderTransfer->getShippingAddress() || !$orderTransfer->getFkCustomer();

        if ($notEnoughData) {
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
        $notEnoughData = !$orderTransfer->getBundleItems() || !$orderTransfer->getFkCustomer();

        if ($notEnoughData) {
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setSameAddresses(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getBillingAddress() || !$quoteTransfer->getBillingAddress()) {
            return $quoteTransfer;
        }

        if ($this->compareAddresses($quoteTransfer->getShippingAddress(), $quoteTransfer->getBillingAddress())) {
            $quoteTransfer->setBillingSameAsShipping(true);
        }

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
        $noAddresses = !$orderTransfer->getCustomer() || !$orderTransfer->getCustomer()->getAddresses();

        if ($noAddresses) {
            return null;
        }

        $addressTransfer = clone $addressTransfer;
        foreach ($orderTransfer->getCustomer()->getAddresses()->getAddresses() as $currentAddressTransfer) {
            if (!$this->compareAddresses($addressTransfer, $currentAddressTransfer)) {
                continue;
            }

            return $currentAddressTransfer->getIdCustomerAddress();
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
    ): ?int {
        $notEnoughData = !$orderTransfer->getCurrencyIsoCode() || !$shipmentMethodTransfer->getName();

        if ($notEnoughData) {
            return null;
        }

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

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $leftAddressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $rightAddressTransfer
     *
     * @return bool
     */
    protected function compareAddresses(AddressTransfer $leftAddressTransfer, AddressTransfer $rightAddressTransfer): bool
    {
        $leftAddressArray = $this->cleanUpAddress($leftAddressTransfer)->toArray();
        $rightAddressArray = $this->cleanUpAddress($rightAddressTransfer)->toArray();

        return $leftAddressArray == $rightAddressArray;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function cleanUpAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $cleanedAddressTransfer = clone $addressTransfer;
        $cleanedAddressTransfer
            ->setIsDefaultShipping(null)
            ->setIsDefaultBilling(null)
            ->setIdSalesOrderAddress(null)
            ->setIdCustomerAddress(null)
            ->setCustomerId(null);

        return $cleanedAddressTransfer;
    }
}
