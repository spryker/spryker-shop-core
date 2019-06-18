<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressStepExecutor implements StepExecutorInterface
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
    protected $createdShipmentsWithShippingAddressesList = [];

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        $customerTransfer = $this->getCustomerTransfer();

        if ($customerTransfer === null) {
            return $quoteTransfer;
        }

        if ($quoteTransfer->getItems()->count() < 1) {
            return $quoteTransfer;
        }

        $quoteTransfer = $this->hydrateItemLevelShippingAddresses($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->hydrateBillingAddress($quoteTransfer, $customerTransfer);
//        $quoteTransfer = $this->setItemLevelIsAddressSavingSkipped($quoteTransfer);
        $quoteTransfer = $this->setQuoteShippingAddress($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function hydrateItemLevelShippingAddresses(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();
            $itemTransfer->getShipment()->requireShippingAddress();

            $shipmentTransfer = $this->getShipmentWithUniqueShippingAddress(
                $itemTransfer->getShipment(),
                $customerTransfer
            );
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function hydrateBillingAddress(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getBillingSameAsShipping() === true) {
            $firstItemTransfer = $quoteTransfer->getItems()[0];
            $billingAddressTransfer = $this->copyShippingAddress($firstItemTransfer->getShipment()->getShippingAddress());
            $quoteTransfer->setBillingAddress($billingAddressTransfer);

            return $quoteTransfer;
        }

        $billingAddressTransfer = $quoteTransfer->getBillingAddress();
        if ($billingAddressTransfer === null) {
            return $quoteTransfer;
        }

        $billingAddressTransfer = $this->expandAddressTransfer($billingAddressTransfer, $customerTransfer);
        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentWithUniqueShippingAddress(ShipmentTransfer $shipmentTransfer, CustomerTransfer $customerTransfer): ShipmentTransfer
    {
        $shippingAddressTransfer = $shipmentTransfer->getShippingAddress();
        $shippingAddressTransfer = $this->expandAddressTransfer($shippingAddressTransfer, $customerTransfer);
        $addressHash = $this->customerService->getUniqueAddressKey($shippingAddressTransfer);

        if (isset($this->createdShipmentsWithShippingAddressesList[$addressHash])) {
            return $this->createdShipmentsWithShippingAddressesList[$addressHash];
        }

        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);
        $this->createdShipmentsWithShippingAddressesList[$addressHash] = $shipmentTransfer;

        return $shipmentTransfer;
    }

    /**
     * @return bool
     */
    protected function hasQuoteMultiShippingAddresses(): bool
    {
        return count($this->createdShipmentsWithShippingAddressesList) > 1;
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setItemLevelIsAddressSavingSkipped(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->hasQuoteMultiShippingAddresses()) {
            return $quoteTransfer;
        }

        /**
         * Change this after form changes will be done.
         */
        $quoteLevelIsAddressSavingSkipped = $quoteTransfer->getIsAddressSavingSkipped();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();
            $itemTransfer->getShipment()->requireShippingAddress();

            $itemTransfer->getShipment()
                ->getShippingAddress()
                ->setIsAddressSavingSkipped($quoteLevelIsAddressSavingSkipped);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShippingAddress(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->hasQuoteMultiShippingAddresses()) {
            return $quoteTransfer;
        }

        $firstItemTransfer = $quoteTransfer->getItems()[0];
        $firstItemTransfer->requireShipment();

        return $quoteTransfer->setShippingAddress($firstItemTransfer->getShipment()->getShippingAddress());
    }
}
