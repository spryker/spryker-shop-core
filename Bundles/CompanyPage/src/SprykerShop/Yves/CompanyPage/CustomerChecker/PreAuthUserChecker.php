<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\CustomerChecker;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Exception\DisabledAccountException;
use Symfony\Component\Security\Core\User\UserInterface;

class PreAuthUserChecker implements PreAuthUserCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccountStatusException
     *
     * @return void
     */
    public function checkPreAuth(CustomerTransfer $customerTransfer, UserInterface $user): void
    {
        if (!$customerTransfer->getIsActiveCompanyUserExists()) {
            $exception = new DisabledAccountException();
            $exception->setUser($user);

            throw $exception;
        }
    }
}
