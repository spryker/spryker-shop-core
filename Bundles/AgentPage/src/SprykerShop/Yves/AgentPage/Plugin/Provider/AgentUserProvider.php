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
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
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
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
    {
        $userTransfer = $this->findUserByUsername($username);

        if ($userTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()
            ->createSecurityUser($userTransfer);
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

        $userTransfer = $this->getUserTransfer($user);

        return $this->getFactory()->createSecurityUser($userTransfer);
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
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === false) {
            return $this->findUserByUsername($user->getUsername());
        }

        return $this->getFactory()
            ->getAgentClient()
            ->getAgent();
    }
}
