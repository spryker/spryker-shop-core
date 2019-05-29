<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Model\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;

class CustomerAddressExpander implements CustomerAddressExpanderInterface
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
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expand(AddressTransfer $addressTransfer): AddressTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();
        if (!$this->isCustomerAddressExists($customerTransfer) || $addressTransfer->getIdCustomerAddress() === null) {
            return $addressTransfer;
        }

        foreach ($customerTransfer->getAddresses()->getAddresses() as $customerAddressTransfer) {
            if ($addressTransfer->getIdCustomerAddress() === $customerAddressTransfer->getIdCustomerAddress()) {
                $this->expandAddressTransferWithCustomerAddressData($addressTransfer, $customerAddressTransfer);
                break;
            }
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $customerAddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandAddressTransferWithCustomerAddressData(AddressTransfer $addressTransfer, AddressTransfer $customerAddressTransfer): AddressTransfer
    {
        return $addressTransfer->fromArray($customerAddressTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function isCustomerAddressExists(?CustomerTransfer $customerTransfer): bool
    {
        return $customerTransfer !== null && $customerTransfer->getAddresses() !== null;
    }
}
