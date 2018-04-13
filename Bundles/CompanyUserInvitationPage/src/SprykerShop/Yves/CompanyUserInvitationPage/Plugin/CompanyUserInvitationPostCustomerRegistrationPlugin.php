<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\PostCustomerRegistrationPluginInterface;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class CompanyUserInvitationPostCustomerRegistrationPlugin extends AbstractPlugin implements PostCustomerRegistrationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function execute(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createCompanyUserInvitationPostCustomerRegistrationHandler()
            ->handle($customerTransfer);
    }
}
