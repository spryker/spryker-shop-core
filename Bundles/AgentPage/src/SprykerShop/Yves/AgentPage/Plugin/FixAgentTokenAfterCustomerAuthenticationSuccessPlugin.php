<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentPageSecurityServiceProvider;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\SwitchUserRole;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class FixAgentTokenAfterCustomerAuthenticationSuccessPlugin extends AbstractPlugin implements AfterCustomerAuthenticationSuccessPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function execute(): void
    {
        $this->fixAgentToken();
    }

    /**
     * @return void
     */
    protected function fixAgentToken(): void
    {
        if ($this->getFactory()->getSecurityAuthorizationChecker()->isGranted(AgentPageSecurityServiceProvider::ROLE_PREVIOUS_ADMIN)) {
            return;
        }

        if (!($this->getFactory()->getAgentClient()->isLoggedIn() && $this->getFactory()->getCustomerClient()->isLoggedIn())) {
            return;
        }

        $tokenStorage = $this->getFactory()->getTokenStorage();

        if (!$tokenStorage->getToken()->getUser() instanceof Customer) {
            return;
        }

        $this->changeToken($tokenStorage);
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return void
     */
    protected function changeToken(TokenStorageInterface $tokenStorage): void
    {
        /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
        $customer = $tokenStorage->getToken()->getUser();

        $token = $this->createUsernamePasswordToken($customer);

        $tokenStorage->setToken($token);
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createCustomerSecurityUser(Customer $customer): UserInterface
    {
        return new Customer(
            $customer->getCustomerTransfer(),
            $customer->getUsername(),
            $customer->getPassword(),
            $this->getCustomerRoles($customer->getRoles())
        );
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function createUsernamePasswordToken(Customer $customer): TokenInterface
    {
        $user = $this->createCustomerSecurityUser($customer);

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            $this->getCustomerRoles($customer->getRoles())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Symfony\Component\Security\Core\Role\Role
     */
    protected function createSwitchUserRole(UserTransfer $userTransfer): Role
    {
        $agent = $this->getFactory()->createSecurityUser($userTransfer);

        $token = new UsernamePasswordToken(
            $agent,
            $agent->getPassword(),
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            $agent->getRoles()
        );

        return new SwitchUserRole(AgentPageSecurityServiceProvider::ROLE_PREVIOUS_ADMIN, $token);
    }

    /**
     * @param array $roles
     *
     * @return array
     */
    protected function getCustomerRoles(array $roles): array
    {
        $roles[] = $this->createSwitchUserRole($this->getFactory()->getAgentClient()->getAgent());

        return $roles;
    }
}
