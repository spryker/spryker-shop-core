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
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToMessengerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToQuoteClientInterface;
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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
     * @param string|null $targetUrl
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createAgentAuthenticationSuccessHandler(?string $targetUrl = null): AuthenticationSuccessHandlerInterface
    {
        return new AgentAuthenticationSuccessHandler($targetUrl);
    }

    /**
     * @param string|null $targetUrl
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    public function createAgentAuthenticationFailureHandler(?string $targetUrl = null): AuthenticationFailureHandlerInterface
    {
        return new AgentAuthenticationFailureHandler($targetUrl);
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
     * @deprecated Will be removed without replacement. Use `new RedirectResponse()` where you need it.
     *
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRedirectResponse(string $url): RedirectResponse
    {
        return new RedirectResponse($url);
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToMessengerClientInterface
     */
    public function getMessengerClient(): AgentPageToMessengerClientInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::CLIENT_MESSENGER);
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
     * @return \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToQuoteClientInterface
     */
    public function getQuoteClient(): AgentPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @deprecated The application shouldn't be accessed and will be removed.
     *
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::APPLICATION);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    public function getTokenStorage()
    {
        $application = $this->getApplication();

        return $application['security.token_storage'];
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getSecurityAuthorizationChecker(): AuthorizationCheckerInterface
    {
        $application = $this->getApplication();

        return $application['security.authorization_checker'];
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
