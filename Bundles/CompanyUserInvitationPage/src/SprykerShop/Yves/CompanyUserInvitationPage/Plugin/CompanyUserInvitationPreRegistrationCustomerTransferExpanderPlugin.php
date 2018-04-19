<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class CompanyUserInvitationPreRegistrationCustomerTransferExpanderPlugin extends AbstractPlugin implements PreRegistrationCustomerTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expand(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer->setCompanyUserInvitationHash(
            $this->getFactory()->getSessionClient()->get(CompanyUserInvitationPageConstants::INVITATION_SESSION_ID)
        );

        return $customerTransfer;
    }
}
