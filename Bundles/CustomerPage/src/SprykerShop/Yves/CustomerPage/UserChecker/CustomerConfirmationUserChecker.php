<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\UserChecker;

use SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException;
use SprykerShop\Yves\CustomerPage\Exception\WrongUserInterfaceException;
use SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerConfirmationUserChecker extends UserChecker
{
    /**
     * @see \SprykerShop\Yves\AgentPage\Plugin\Security\AgentPageSecurityPlugin::ROLE_AGENT
     */
    protected const ROLE_AGENT = 'ROLE_AGENT';

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \SprykerShop\Yves\CustomerPage\Exception\WrongUserInterfaceException
     *
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (in_array(static::ROLE_AGENT, $user->getRoles(), true)) {
            return;
        }

        if (!$user instanceof CustomerUserInterface) {
            throw new WrongUserInterfaceException(sprintf('User should be an instance of %s', CustomerUserInterface::class));
        }

        parent::checkPreAuth($user);

        $customerTransfer = $user->getCustomerTransfer();
        if ($customerTransfer->getRegistered() === null) {
            $ex = new NotConfirmedAccountException();
            $ex->setUser($user);

            throw $ex;
        }
    }
}
