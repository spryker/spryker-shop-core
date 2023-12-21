<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Updater;

use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AgentTokenAfterCustomerAuthenticationSuccessUpdater implements AgentTokenAfterCustomerAuthenticationSuccessUpdaterInterface
{
    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Security\AgentPageSecurityPlugin::ROLE_PREVIOUS_ADMIN
     *
     * @var string
     */
    protected const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface
     */
    protected AgentPageToAgentClientInterface $agentClient;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected TokenStorageInterface $tokenStorage;

    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface
     */
    protected AgentPageToCustomerClientInterface $customerClient;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface $agentClient
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        AgentPageToAgentClientInterface $agentClient,
        TokenStorageInterface $tokenStorage,
        AgentPageToCustomerClientInterface $customerClient
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->agentClient = $agentClient;
        $this->tokenStorage = $tokenStorage;
        $this->customerClient = $customerClient;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        if ($this->authorizationChecker->isGranted(static::ROLE_PREVIOUS_ADMIN)) {
            return;
        }

        if (!($this->agentClient->isLoggedIn() && $this->customerClient->isLoggedIn())) {
            return;
        }

        if (!$this->tokenStorage->getToken()->getUser() instanceof Customer) {
            return;
        }

        $this->changeToken();
    }

    /**
     * @return void
     */
    protected function changeToken(): void
    {
        /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
        $customer = $this->tokenStorage->getToken()->getUser();
        $token = $this->createUsernamePasswordToken($customer);

        $this->tokenStorage->setToken($token);
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
            $this->getCustomerRoles($customer->getRoles()),
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
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            $this->getCustomerRoles($customer->getRoles()),
        );
    }

    /**
     * @param array<string> $roles
     *
     * @return array<string>
     */
    protected function getCustomerRoles(array $roles): array
    {
        $roles[] = static::ROLE_PREVIOUS_ADMIN;

        return $roles;
    }
}
