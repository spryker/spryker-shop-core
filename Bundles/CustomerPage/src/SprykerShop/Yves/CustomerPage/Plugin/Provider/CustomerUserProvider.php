<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Customer) {
            return $user;
        }

        $customerTransfer = $this->getCustomerTransfer($user);

        return $this->getFactory()->createSecurityUser($customerTransfer);
    }

    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username) /** @phpstan-ignore-line */
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param string $identifier
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $customerTransfer = $this->loadCustomerByEmail($identifier);

        return $this->getFactory()->createSecurityUser($customerTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(UserInterface $user)
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            $customerTransfer = $this->loadCustomerByEmail(
                $this->getUserIdentifier($user),
            );

            return $customerTransfer;
        }
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer->getIsDirty() === true) {
            $customerTransfer = $this->updateUser($user);

            return $customerTransfer;
        }

        return $customerTransfer;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, Customer::class, true);
    }

    /**
     * @param string $email
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function loadCustomerByEmail($email)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail($email);

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomerByEmail($customerTransfer);

        if ($customerTransfer->getIdCustomer() === null) {
            throw new AuthenticationException(
                sprintf('Customer with email "%s" not found.', $email),
            );
        }

        return $customerTransfer;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function updateUser(UserInterface $user)
    {
        $customerTransfer = $this->loadCustomerByEmail(
            $this->getUserIdentifier($user),
        );

        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    protected function getUserIdentifier(UserInterface $user): string
    {
        if ($this->isSymfonyVersion5() === true) {
            return $user->getUsername();
        }

        return $user->getUserIdentifier();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
