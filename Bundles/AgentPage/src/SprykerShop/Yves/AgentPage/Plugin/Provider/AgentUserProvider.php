<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
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
        $userTransfer = $this->findUserByUsername($identifier);

        if ($userTransfer === null) {
            $this->throwUserNotFoundException();
        }

        return $this->getFactory()->createSecurityUser($userTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Agent) {
            return $user;
        }

        $agentUserTransfer = $this->getUserTransfer($user);

        if ($agentUserTransfer === null) {
            return $user;
        }

        return $this->getFactory()->createSecurityUser($agentUserTransfer);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, Agent::class, true) || is_a($class, Customer::class, true);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserByUsername(string $username): ?UserTransfer
    {
        $userTransfer = new UserTransfer();
        $userTransfer->setUsername($username);

        $userTransfer = $this->getFactory()
            ->getAgentClient()
            ->findAgentByUsername($userTransfer);

        if ($userTransfer && $userTransfer->getStatus() === static::COL_STATUS_ACTIVE) {
            return $userTransfer;
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function getUserTransfer(UserInterface $user): ?UserTransfer
    {
        $userTransfer = $this->findUserByUsername(
            $this->getUserIdentifier($user),
        );

        if ($userTransfer === null) {
             $this->getFactory()
                ->getAgentClient()
                ->invalidateAgentSession();
        }

        return $userTransfer;
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
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return void
     */
    protected function throwUserNotFoundException(): void
    {
        if ($this->isSymfonyVersion5() === true) {
            throw new UsernameNotFoundException();
        }

        throw new UserNotFoundException();
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
