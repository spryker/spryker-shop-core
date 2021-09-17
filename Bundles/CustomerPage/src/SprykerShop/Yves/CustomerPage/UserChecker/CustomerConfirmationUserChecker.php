<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\UserChecker;

use SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException;
use SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerConfirmationUserChecker extends UserChecker
{
    /**
     * @var array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreAuthUserCheckPluginInterface>
     */
    protected $preAuthUserCheckPlugins;

    /**
     * @param array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreAuthUserCheckPluginInterface> $preAuthUserCheckPlugins
     */
    public function __construct(array $preAuthUserCheckPlugins)
    {
        $this->preAuthUserCheckPlugins = $preAuthUserCheckPlugins;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException
     *
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof CustomerUserInterface) {
            return;
        }

        parent::checkPreAuth($user);

        $customerTransfer = $user->getCustomerTransfer();
        if ($customerTransfer->getRegistered() === null) {
            $ex = new NotConfirmedAccountException();
            $ex->setUser($user);

            throw $ex;
        }

        $this->executePreAuthUserCheckPlugins($user);
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface $user
     *
     * @return void
     */
    protected function executePreAuthUserCheckPlugins(CustomerUserInterface $user): void
    {
        foreach ($this->preAuthUserCheckPlugins as $preAuthCheckerPlugin) {
            $preAuthCheckerPlugin->checkPreAuth($user->getCustomerTransfer(), $user);
        }
    }
}
