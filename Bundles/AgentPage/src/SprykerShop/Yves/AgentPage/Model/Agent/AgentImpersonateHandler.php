<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Model\Agent;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentPageSecurityServiceProvider;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\Role\SwitchUserRole;
use Symfony\Component\Security\Core\User\UserInterface;

class AgentImpersonateHandler implements AgentImpersonateHandlerInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface
     */
    protected $agentClient;

    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface $agentClient
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface $customerClient
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AgentPageToAgentClientInterface $agentClient,
        AgentPageToCustomerClientInterface $customerClient,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->agentClient = $agentClient;
        $this->customerClient = $customerClient;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return void
     */
    public function fixAgentToken(): void
    {
        if ($this->authorizationChecker->isGranted(AgentPageSecurityServiceProvider::ROLE_PREVIOUS_ADMIN)) {
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
        $customerTransfer = $this->tokenStorage->getToken()->getUser()->getCustomerTransfer();

        $token = $this->createUsernamePasswordToken($customerTransfer);

        $this->tokenStorage->setToken($token);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createSecurityUser(CustomerTransfer $customerTransfer): UserInterface
    {
        return new Customer(
            $customerTransfer,
            $customerTransfer->getEmail(),
            $customerTransfer->getPassword(),
            $this->getRoles()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function createUsernamePasswordToken(CustomerTransfer $customerTransfer): TokenInterface
    {
        $user = $this->createSecurityUser($customerTransfer);

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            $this->getRoles()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createSecurityAgent(UserTransfer $userTransfer): UserInterface
    {
        return new Agent(
            $userTransfer,
            [AgentPageSecurityServiceProvider::ROLE_AGENT, AgentPageSecurityServiceProvider::ROLE_ALLOWED_TO_SWITCH]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Symfony\Component\Security\Core\Role\RoleInterface
     */
    protected function createSwitchUserRole(UserTransfer $userTransfer): RoleInterface
    {
        $agent = $this->createSecurityAgent($userTransfer);

        $token = new UsernamePasswordToken(
            $agent,
            $agent->getPassword(),
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            $agent->getRoles()
        );

        return new SwitchUserRole(AgentPageSecurityServiceProvider::ROLE_PREVIOUS_ADMIN, $token);
    }

    /**
     * @return array
     */
    protected function getRoles(): array
    {
        return [
            AgentPageSecurityServiceProvider::ROLE_USER,
            AgentPageSecurityServiceProvider::ROLE_PREVIOUS_ADMIN,
            $this->createSwitchUserRole($this->agentClient->getAgent()),
        ];
    }
}
