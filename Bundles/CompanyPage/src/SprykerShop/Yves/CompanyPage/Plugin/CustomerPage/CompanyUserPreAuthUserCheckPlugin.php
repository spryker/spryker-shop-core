<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreAuthUserCheckPluginInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyUserPreAuthUserCheckPlugin extends AbstractPlugin implements PreAuthUserCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Throws disabled account exception if customer doesn't have enabled company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccountStatusException
     *
     * @return void
     */
    public function checkPreAuth(CustomerTransfer $customerTransfer, UserInterface $user): void
    {
        $this->getFactory()
            ->createPreAuthUserChecker()
            ->checkPreAuth($customerTransfer, $user);
    }
}
