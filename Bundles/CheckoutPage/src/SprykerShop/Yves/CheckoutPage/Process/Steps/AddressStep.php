<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;
use Symfony\Component\HttpFoundation\Request;

class AddressStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected $existingShipments;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct(
        CheckoutPageToCustomerClientInterface $customerClient,
        CheckoutPageToCalculationClientInterface $calculationClient,
        $stepRoute,
        $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->calculationClient = $calculationClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer)
    {
        return true;
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
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $this->setShippingAddresses($quoteTransfer);
        $this->setBillingAddress($quoteTransfer);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        if ($this->hasItemsWithEmptyShippingAddresses($quoteTransfer)) {
            return false;
        }

        if ($this->isSplitDelivery($quoteTransfer) && $quoteTransfer->getBillingSameAsShipping()) {
            return false;
        }

        $shippingIsEmpty = $this->isAddressEmpty($quoteTransfer->getShippingAddress());
        $billingIsEmpty = $quoteTransfer->getBillingSameAsShipping() === false && $this->isAddressEmpty($quoteTransfer->getBillingAddress());

        if ($shippingIsEmpty || $billingIsEmpty) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function hydrateCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer)
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
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    protected function isAddressEmpty(?AddressTransfer $addressTransfer = null)
    {
        if ($addressTransfer === null) {
            return true;
        }

        $hasName = (!empty($addressTransfer->getFirstName()) && !empty($addressTransfer->getLastName()));
        if (!$addressTransfer->getIdCustomerAddress() && !$hasName) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.address.title';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer)
    {
        return $this->postCondition($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer)
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipment(AddressTransfer $addressTransfer): ShipmentTransfer
    {
        return (new ShipmentTransfer())
            ->setShippingAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isSplitDelivery(AbstractTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress()->getIdCustomerAddress() === CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setShippingAddresses(AbstractTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemShippingAddress = $itemTransfer->getShipment()->getShippingAddress();
            $itemTransfer->setShipment(
                $this->getItemShipment($itemShippingAddress)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setBillingAddress(AbstractTransfer $quoteTransfer): void
    {
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

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

        $shippingAddress->setIsDefaultShipping(true);

        return $shippingAddress;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getAddressHash(AddressTransfer $addressTransfer): string
    {
        return md5($addressTransfer->serialize());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemsWithEmptyShippingAddresses(AbstractTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $this->isAddressEmpty($itemTransfer->getShipment()->getShippingAddress())) {
                return true;
            }
        }

        return false;
    }
}
