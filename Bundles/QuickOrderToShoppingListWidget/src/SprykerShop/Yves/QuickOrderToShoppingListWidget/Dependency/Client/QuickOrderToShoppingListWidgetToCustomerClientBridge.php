<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderToShoppingListWidget\Dependency\Client;

class QuickOrderToShoppingListWidgetToCustomerClientBridge implements QuickOrderToShoppingListWidgetToCustomerClientInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }


    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->customerClient->isLoggedIn();
    }
}
