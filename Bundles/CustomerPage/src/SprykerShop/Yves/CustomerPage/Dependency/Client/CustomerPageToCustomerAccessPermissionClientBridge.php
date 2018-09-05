<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

class CustomerPageToCustomerAccessPermissionClientBridge implements CustomerPageToCustomerAccessPermissionClientInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessPermission\CustomerAccess\CustomerAccessInterface
     */
    protected $customerAccessPermissionClient;

    /**
     * @param \Spryker\Client\CustomerAccessPermission\CustomerAccess\CustomerAccessInterface $customerAccessPermissionClient
     */
    public function __construct($customerAccessPermissionClient)
    {
        $this->customerAccessPermissionClient = $customerAccessPermissionClient;
    }

    /**
     * @param string $customerSecuredPattern
     *
     * @return string
     */
    public function getCustomerSecuredPatternAccordingCustomerAccess(string $customerSecuredPattern): string
    {
        return $this->customerAccessPermissionClient->getCustomerSecuredPatternAccordingCustomerAccess($customerSecuredPattern);
    }
}
