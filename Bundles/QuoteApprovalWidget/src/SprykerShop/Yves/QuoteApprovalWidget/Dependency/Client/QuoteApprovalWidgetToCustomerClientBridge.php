<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

class QuoteApprovalWidgetToCustomerClientBridge implements QuoteApprovalWidgetToCustomerClientInterface
{
    /**
     * @var \SprykerShop\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }
}
