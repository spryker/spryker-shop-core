<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Mapper\CustomerMapperInterface;

class CustomerAddressExpander implements CustomerAddressExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Mapper\CustomerMapperInterface
     */
    protected $customerMapper;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Mapper\CustomerMapperInterface $customerMapper
     */
    public function __construct(CustomerMapperInterface $customerMapper)
    {
        $this->customerMapper = $customerMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expandWithCustomerAddress(AddressTransfer $addressTransfer, ?CustomerTransfer $customerTransfer): AddressTransfer
    {
        if ($customerTransfer === null) {
            return $addressTransfer;
        }

        if ($addressTransfer->getFkCustomer() === null) {
            $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        }

        if ($customerTransfer->getAddresses() === null || $addressTransfer->getIdCustomerAddress() === null) {
            return $addressTransfer;
        }

        foreach ($customerTransfer->getAddresses()->getAddresses() as $customerAddressTransfer) {
            if ($addressTransfer->getIdCustomerAddress() !== $customerAddressTransfer->getIdCustomerAddress()) {
                continue;
            }

            return $this->customerMapper
                ->mapCustomerAddressTransferToAddressTransfer($customerAddressTransfer, $addressTransfer);
        }

        return $addressTransfer;
    }
}
