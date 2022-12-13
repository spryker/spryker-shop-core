<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\Plugin\AgentPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface;

/**
 * @method \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageFactory getFactory()
 */
class CustomerUpdateSessionPostImpersonationPlugin extends AbstractPlugin implements SessionPostImpersonationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates customer's session data in storage if a given customer is valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()
            ->createCustomerSessionUpdater()
            ->update($customerTransfer);
    }
}
