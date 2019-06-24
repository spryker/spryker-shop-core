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
     * @var bool
     */
    protected $hasQuoteDataItemLevelShippingAddresses;

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $customerTransfer = $this->getCustomerTransfer();

        $quoteTransfer = $this->hydrateItemLevelShippingAddresses($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->hydrateBillingAddress($quoteTransfer, $customerTransfer);
        $quoteTransfer = $this->setQuoteShippingAddress($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function hydrateItemLevelShippingAddresses(
        QuoteTransfer $quoteTransfer,
        ?CustomerTransfer $customerTransfer
    ): QuoteTransfer {
        if ($quoteTransfer->getItems()->count() < 1) {
            return $quoteTransfer;
        }

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
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function hydrateBillingAddress(QuoteTransfer $quoteTransfer, ?CustomerTransfer $customerTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getBillingSameAsShipping() === true) {
            if ($this->hasQuoteDataItemLevelShippingAddresses($quoteTransfer)) {
                $firstItemTransfer = current($quoteTransfer->getItems());
                $shippingAddressTransfer = $firstItemTransfer->getShipment()->getShippingAddress();
            } else {
                /**
                 * @deprecated Exists for Backward Compatibility reasons only.
                 */
                $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
            }
            $billingAddressTransfer = $this->copyShippingAddress($shippingAddressTransfer);
            $quoteTransfer->setBillingAddress($billingAddressTransfer);

            return $quoteTransfer;
        }

        $billingAddressTransfer = $quoteTransfer->getBillingAddress();
        if ($billingAddressTransfer === null) {
            return $quoteTransfer;
        }

        $billingAddressTransfer = $this->expandAddressTransfer($billingAddressTransfer, $customerTransfer);

        return $quoteTransfer->setBillingAddress($billingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentWithUniqueShippingAddress(
        ShipmentTransfer $shipmentTransfer,
        ?CustomerTransfer $customerTransfer
    ): ShipmentTransfer {
        $addressTransfer = $shipmentTransfer->requireShippingAddress()->getShippingAddress();
        $addressTransfer = $this->expandAddressTransfer($addressTransfer, $customerTransfer);
        $addressHash = $this->getUniqueAddressKeyWithoutIdCustomerAddress($addressTransfer);

        if (isset($this->createdShipmentsWithShippingAddressesList[$addressHash])) {
            return $this->createdShipmentsWithShippingAddressesList[$addressHash];
        }

        $shipmentTransfer->setShippingAddress($addressTransfer);
        $this->createdShipmentsWithShippingAddressesList[$addressHash] = $shipmentTransfer;

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasQuoteDataItemLevelShippingAddresses(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->hasQuoteDataItemLevelShippingAddresses !== null) {
            return $this->hasQuoteDataItemLevelShippingAddresses;
        }

        if ($quoteTransfer->getItems()->count() < 1) {
            $this->hasQuoteDataItemLevelShippingAddresses = false;
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                $this->hasQuoteDataItemLevelShippingAddresses = false;
                return false;
            }
        }

        $this->hasQuoteDataItemLevelShippingAddresses = true;
        return true;
    }

    /**
     * @return bool
     */
    protected function hasQuoteMultiShippingAddresses(): bool
    {
        return count($this->createdShipmentsWithShippingAddressesList) > 1;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function copyShippingAddress(?AddressTransfer $addressTransfer): ?AddressTransfer
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
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandAddressTransfer(
        AddressTransfer $addressTransfer,
        ?CustomerTransfer $customerTransfer
    ): AddressTransfer {
        foreach ($this->addressTransferExpanderPlugins as $addressTransferExpanderPlugin) {
            $addressTransfer = $addressTransferExpanderPlugin->expand($addressTransfer, $customerTransfer);
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getUniqueAddressKeyWithoutIdCustomerAddress(AddressTransfer $addressTransfer): string
    {
        $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
        $addressTransfer->setIdCustomerAddress(null);

        $addressHash = $this->customerService->getUniqueAddressKey($addressTransfer);

        $addressTransfer->setIdCustomerAddress($idCustomerAddress);

        return $addressHash;
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

        $firstItemTransfer = current($quoteTransfer->getItems());
        if (!$firstItemTransfer) {
            return $quoteTransfer;
        }

        $firstItemTransfer->requireShipment();

        return $quoteTransfer->setShippingAddress($firstItemTransfer->getShipment()->getShippingAddress());
    }
}
