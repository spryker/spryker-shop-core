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
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressSaver instead.
 */
class AddressSaverWithoutMultiShipment implements SaverInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     */
    public function __construct(CheckoutPageToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function save(Request $request, AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

        if ($shippingAddressTransfer !== null && $shippingAddressTransfer->getIdCustomerAddress() !== null) {
            $shippingAddressTransfer = $this->hydrateCustomerAddress(
                $shippingAddressTransfer,
                $customerTransfer
            );

            $quoteTransfer->setShippingAddress($shippingAddressTransfer);
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

        $quoteTransfer = $this->setQuoteLevelShipmentData($quoteTransfer);

        return $quoteTransfer;
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
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteLevelShipmentData(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setShipment(new ShipmentTransfer());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment(null);
        }

        return $quoteTransfer;
    }
}
