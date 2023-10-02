<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Expander;

use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;

class CustomerAddressViewDataExpander implements AddressViewDataExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_HAS_CUSTOMER_ADDRESSES = 'hasCustomerAddresses';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected CheckoutPageToCustomerClientInterface $customerClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     */
    public function __construct(CheckoutPageToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array
    {
        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer || !$customerTransfer->getAddresses()) {
            $viewData[static::PARAMETER_HAS_CUSTOMER_ADDRESSES] = false;

            return $viewData;
        }

        $viewData[static::PARAMETER_HAS_CUSTOMER_ADDRESSES] = count($customerTransfer->getAddressesOrFail()->getAddresses()) > 0;

        return $viewData;
    }
}
