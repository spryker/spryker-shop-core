<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\CustomerChecker;

use SprykerShop\Yves\CustomerPage\Exception\WrongUserInterfaceException;
use SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerChecker extends UserChecker
{
    public const ERROR_NOT_CONFIRMED_ACCOUNT = 'User account is not confirmed.';
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \SprykerShop\Yves\CustomerPage\Exception\WrongUserInterfaceException
     *
     * @return void
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof CustomerUserInterface) {
            throw new WrongUserInterfaceException(sprintf('User should be an instance of %s', CustomerUserInterface::class));
        }

        parent::checkPreAuth($user);

        $customerTransfer = $user->getCustomerTransfer();
        if ($customerTransfer->getRegistered() === null) {
            $ex = new DisabledException(static::ERROR_NOT_CONFIRMED_ACCOUNT);
            $ex->setUser($user);

            throw $ex;
        }
    }
}
