<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;
use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationFailureHandler;
use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationSuccessHandler;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentPageSecurityServiceProvider;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentUserProvider;
use SprykerShop\Yves\AgentPage\Plugin\Subscriber\SwitchUserEventSubscriber;
use SprykerShop\Yves\AgentPage\Security\Agent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AgentPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSwitchUserEventSubscriber(): EventSubscriberInterface
    {
        return new SwitchUserEventSubscriber();
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createAgentUserProvider(): UserProviderInterface
    {
        return new AgentUserProvider();
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createAgentAuthenticationSuccessHandler(): AuthenticationSuccessHandlerInterface
    {
        return new AgentAuthenticationSuccessHandler();
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    public function createAgentAuthenticationFailureHandler(): AuthenticationFailureHandlerInterface
    {
        return new AgentAuthenticationFailureHandler();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function createSecurityUser(UserTransfer $userTransfer): UserInterface
    {
        return new Agent(
            $userTransfer,
            [AgentPageSecurityServiceProvider::ROLE_AGENT, AgentPageSecurityServiceProvider::ROLE_ALLOWED_TO_SWITCH]
        );
    }

    /**
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRedirectResponse(string $url): RedirectResponse
    {
        return new RedirectResponse($url);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getFlashMessenger(): FlashMessengerInterface
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface
     */
    public function getAgentClient(): AgentPageToAgentClientInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::CLIENT_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface
     */
    public function getCustomerClient(): AgentPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::APPLICATION);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAgentLoginForm()
    {
        return $this->getFormFactory()
            ->create(AgentLoginForm::class);
    }
}
