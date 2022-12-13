<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Impersonator;

use SprykerShop\Yves\CustomerPage\Security\Customer;

interface SessionImpersonatorInterface
{
    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return void
     */
    public function impersonate(Customer $customer): void;
}
