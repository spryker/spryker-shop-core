<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientBridgeInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Form\FormFactory;

class CompanyUserInvitationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Form\FormFactory
     */
    public function createCompanyUserInvitationPageFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientBridgeInterface
     */
    public function getCompanyUserInvitationClient(): CompanyUserInvitationPageToCompanyUserInvitationClientBridgeInterface
    {
        return $this->getProvidedDependency(CompanyUserInvitationPageDependencyProvider::CLIENT_COMPANY_USER_INVITATION);
    }
}
