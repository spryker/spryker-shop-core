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
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();

        if($shippingAddressTransfer === null) {
            return $quoteTransfer;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

        if ($shippingAddressTransfer->getIdCustomerAddress() !== null) {
            $shippingAddressTransfer = $this->hydrateCustomerAddress(
                $shippingAddressTransfer,
                $customerTransfer
            );

            // todo: get rid it before release
            $quoteTransfer->setShippingAddress($shippingAddressTransfer);
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shippingAddressTransfer->setIsDefaultShipping(true);
            $itemTransfer->setShipment($this->createShipmentTransfer($shippingAddressTransfer));
        }

        if ($quoteTransfer->getBillingSameAsShipping() === true) {
            $quoteTransfer->setBillingAddress(clone $quoteTransfer->getShippingAddress());
        } elseif ($billingAddressTransfer !== null && $billingAddressTransfer->getIdCustomerAddress() !== null) {
            $billingAddressTransfer = $this->hydrateCustomerAddress(
                $billingAddressTransfer,
                $customerTransfer
            );

            $quoteTransfer->setBillingAddress($billingAddressTransfer);
        }
        $quoteTransfer->getShippingAddress()->setIsDefaultShipping(true);
        $quoteTransfer->getBillingAddress()->setIsDefaultBilling(true);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        // todo: Please move this logic in a new method and call it here like `\SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep::isShipmentSet`
        // todo: Split Delivery. Get rid of it, before release
        if ($quoteTransfer->getShippingAddress() === null || $quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getShippingAddress() === null) {
                return false;
            }
        }

        // todo: Split Delivery check multiple addresses flag in case billing address is the same as Shipping address
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
    protected function createShipmentTransfer(AddressTransfer $addressTransfer): ShipmentTransfer
    {
        return (new ShipmentTransfer())
            ->setShippingAddress($addressTransfer);
    }
}
