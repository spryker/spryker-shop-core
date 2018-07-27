<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Security\Agent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
    {
        $userTransfer = $this->getUserByUsername($username);

        if ($userTransfer->getIdUser() === null) {
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
        return $class === Agent::class;
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserByUsername(string $username): UserTransfer
    {
        $userTransfer = new UserTransfer();
        $userTransfer->setUsername($username);

        return $this->getFactory()
            ->getAgentClient()
            ->getAgentByUsername($userTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserTransfer(UserInterface $user): UserTransfer
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === false) {
            return $this->getUserByUsername($user->getUsername());
        }

        return $this->getFactory()
            ->getAgentClient()
            ->getAgent();
    }
}
