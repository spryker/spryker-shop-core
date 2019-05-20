<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressSaver implements SaverInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected $existingShipments = [];

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface $customerService
     */
    public function __construct(
        CheckoutPageToCustomerClientInterface $customerClient,
        CheckoutPageToCustomerServiceInterface $customerService
    ) {
        $this->customerClient = $customerClient;
        $this->customerService = $customerService;
    }

    /**
     * Guest customer takes data from form directly mapped by symfony forms.
     * Logged in customer takes data by id from current CustomerTransfer stored in session.
     * If it's new address it's saved when order is created in CustomerOrderSaverPlugin.
     * The selected addresses will be updated to default billing and shipping address.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(Request $request, AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        $this->setQuoteShippingAddress($quoteTransfer);
        $this->setItemsShippingAddress($quoteTransfer);
        $this->setBillingAddress($quoteTransfer);
        $this->setQuoteShipment($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setQuoteShippingAddress(QuoteTransfer $quoteTransfer): void
    {
        $addressTransfer = $quoteTransfer->getShippingAddress();

        if ($addressTransfer === null) {
            return;
        }

        $isDefaultShipping = $addressTransfer->getIsDefaultShipping();
        $this->prepareShippingAddress($addressTransfer);
        $addressTransfer->setIsDefaultShipping($isDefaultShipping);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemsShippingAddress(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();

            if ($shipmentTransfer === null) {
                continue;
            }

            $addressTransfer = $shipmentTransfer->getShippingAddress();
            if ($quoteTransfer->getShippingAddress()->getIdCustomerAddress() === null ||
                $this->isAddressEmpty($addressTransfer)) {
                $addressTransfer = $quoteTransfer->getShippingAddress();
            }

            $itemTransfer->setShipment($this->getItemShipment($addressTransfer));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setBillingAddress(QuoteTransfer $quoteTransfer): void
    {
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

        if ($quoteTransfer->getBillingSameAsShipping() === true && $quoteTransfer->getShippingAddress() === null) {
            $quoteTransfer->setBillingAddress(null);

            return;
        }

        if ($quoteTransfer->getBillingSameAsShipping() === true) {
            $quoteTransfer->setBillingAddress(clone $quoteTransfer->getShippingAddress());
            $quoteTransfer->getBillingAddress()->setIsDefaultBilling(true);

            return;
        }

        if ($billingAddressTransfer !== null && $billingAddressTransfer->getIdCustomerAddress() !== null) {
            $billingAddressTransfer = $this->hydrateCustomerAddress(
                $billingAddressTransfer,
                $this->customerClient->getCustomer()
            );

            $quoteTransfer->setBillingAddress($billingAddressTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setQuoteShipment(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->setShipment(new ShipmentTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function hydrateCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer): AddressTransfer
    {
        if ($customerTransfer->getAddresses() === null) {
            return $addressTransfer;
        }

        foreach ($customerTransfer->getAddresses()->getAddresses() as $customerAddressTransfer) {
            if ($addressTransfer->getIdCustomerAddress() === $customerAddressTransfer->getIdCustomerAddress()) {
                $addressTransfer->fromArray($customerAddressTransfer->toArray());
                break;
            }
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipment(AddressTransfer $addressTransfer): ShipmentTransfer
    {
        return (new ShipmentTransfer())
            ->setShippingAddress($addressTransfer)
            ->setMethod(new ShipmentMethodTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $itemShippingAddress
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getItemShipment(AddressTransfer $itemShippingAddress): ShipmentTransfer
    {
        $shippingAddress = $this->prepareShippingAddress($itemShippingAddress);
        $addressHash = $this->getAddressHash($shippingAddress);

        if (isset($this->existingShipments[$addressHash])) {
            return $this->existingShipments[$addressHash];
        }

        /**
         * @todo Check that every shipping address for multiple shipment process should be default.
         */
        $shippingAddress->setIsDefaultShipping(true);
        $shipmentTransfer = $this->createShipment($shippingAddress);
        $this->existingShipments[$addressHash] = $shipmentTransfer;

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function prepareShippingAddress(AddressTransfer $shippingAddress): AddressTransfer
    {
        if ($shippingAddress->getIdCustomerAddress() !== null) {
            $shippingAddress = $this->hydrateCustomerAddress(
                $shippingAddress,
                $this->customerClient->getCustomer()
            );
        }

        return $shippingAddress;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressHash(AddressTransfer $addressTransfer): string
    {
        return $this->customerService->getUniqueAddressKey($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    protected function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool
    {
        if ($addressTransfer === null) {
            return true;
        }

        return $addressTransfer->getIdCustomerAddress() === null
            && empty($addressTransfer->getFirstName())
            && empty($addressTransfer->getLastName());
    }
}
