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
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[]
     */
    protected $addressTransferExpanderPlugins;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected $existingShipments = [];

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface $customerService
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[] $addressTransferExpanderPlugins
     */
    public function __construct(
        CheckoutPageToCustomerServiceInterface $customerService,
        CheckoutPageToCustomerClientInterface $customerClient,
        array $addressTransferExpanderPlugins
    ) {
        $this->customerService = $customerService;
        $this->customerClient = $customerClient;
        $this->addressTransferExpanderPlugins = $addressTransferExpanderPlugins;
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
        $customerTransfer = $this->getCustomerTransfer();

        if ($customerTransfer === null) {
            return $quoteTransfer;
        }

        $quoteTransfer = $this->setQuoteShippingAddress($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->setItemsShippingAddress($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->setBillingAddress($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->setQuoteShipment($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShippingAddress(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        $addressTransfer = $quoteTransfer->getShippingAddress();

        if ($addressTransfer === null) {
            return $quoteTransfer;
        }

        $isDefaultShipping = $addressTransfer->getIsDefaultShipping();
        $addressTransfer = $this->expandAddressTransfer($addressTransfer, $customerTransfer);
        $addressTransfer->setIsDefaultShipping($isDefaultShipping);

        return $quoteTransfer->setShippingAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setItemsShippingAddress(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();

            if ($shipmentTransfer === null) {
                continue;
            }

            $addressTransfer = $shipmentTransfer->getShippingAddress();
            if ($quoteTransfer->getShippingAddress()->getIdCustomerAddress() === null ||
                $this->customerService->isAddressEmpty($addressTransfer)) {
                $addressTransfer = $quoteTransfer->getShippingAddress();
            }

            $itemTransfer->setShipment($this->getItemShipment($addressTransfer, $customerTransfer));
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setBillingAddress(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getBillingSameAsShipping() === true) {
            $billingAddressTransfer = $this->copyShippingAddress($quoteTransfer->getShippingAddress());
            $quoteTransfer->setBillingAddress($billingAddressTransfer);

            return $quoteTransfer;
        }

        $billingAddressTransfer = $quoteTransfer->getBillingAddress();
        if ($billingAddressTransfer === null || $billingAddressTransfer->getIdCustomerAddress() === null) {
            return $quoteTransfer;
        }

        $billingAddressTransfer = $this->expandAddressTransfer($billingAddressTransfer, $customerTransfer);
        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setShipment(new ShipmentTransfer());
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getItemShipment(AddressTransfer $itemShippingAddress, CustomerTransfer $customerTransfer): ShipmentTransfer
    {
        $shippingAddress = $this->expandAddressTransfer($itemShippingAddress, $customerTransfer);
        $addressHash = $this->customerService->getUniqueAddressKey($shippingAddress);

        if (isset($this->existingShipments[$addressHash])) {
            return $this->existingShipments[$addressHash];
        }

        $shipmentTransfer = $this->createShipment($shippingAddress);
        $this->existingShipments[$addressHash] = $shipmentTransfer;

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function copyShippingAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        if ($addressTransfer === null) {
            return null;
        }

        return (clone $addressTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomerTransfer(): ?CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandAddressTransfer(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer): AddressTransfer
    {
        foreach ($this->addressTransferExpanderPlugins as $addressTransferExpanderPlugin) {
            $addressTransfer = $addressTransferExpanderPlugin->expand($addressTransfer, $customerTransfer);
        }

        return $addressTransfer;
    }
}
