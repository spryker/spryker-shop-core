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
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerShop\Yves\AgentPage\Authenticator\AgentLoginFormAuthenticator;
use SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilder;
use SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilderInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToMessengerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToQuoteClientInterface;
use SprykerShop\Yves\AgentPage\Expander\SecurityBuilderExpander;
use SprykerShop\Yves\AgentPage\Expander\SecurityBuilderExpanderInterface;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;
use SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatter;
use SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatterInterface;
use SprykerShop\Yves\AgentPage\Impersonator\SessionImpersonator;
use SprykerShop\Yves\AgentPage\Impersonator\SessionImpersonatorInterface;
use SprykerShop\Yves\AgentPage\Plugin\FixAgentTokenAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationFailureHandler;
use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationSuccessHandler;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AccessDeniedHandler;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentUserProvider;
use SprykerShop\Yves\AgentPage\Plugin\Security\AgentPageSecurityPlugin;
use SprykerShop\Yves\AgentPage\Plugin\Subscriber\SwitchUserEventSubscriber;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\AgentPage\Updater\AgentTokenAfterCustomerAuthenticationSuccessUpdater;
use SprykerShop\Yves\AgentPage\Updater\AgentTokenAfterCustomerAuthenticationSuccessUpdaterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageConfig getConfig()
 */
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
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::SERVICE_ROUTER);
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
            [AgentPageSecurityPlugin::ROLE_AGENT, AgentPageSecurityPlugin::ROLE_ALLOWED_TO_SWITCH],
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
        return $this->getProvidedDependency(AgentPageDependencyProvider::SERVICE_SECURITY_TOKEN_STORAGE);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getSecurityAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::SERVICE_LOCALE);
    }

    /**
     * @param string $targetUrl
     *
     * @return \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface
     */
    public function createAccessDeniedHandler(string $targetUrl): AccessDeniedHandlerInterface
    {
        return new AccessDeniedHandler($targetUrl);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAgentLoginForm()
    {
        return $this->getFormFactory()
            ->create(AgentLoginForm::class);
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatterInterface
     */
    public function createLoginCheckUrlFormatter(): LoginCheckUrlFormatterInterface
    {
        return new LoginCheckUrlFormatter($this->getConfig(), $this->getLocale());
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Impersonator\SessionImpersonatorInterface
     */
    public function createSessionImpersonator(): SessionImpersonatorInterface
    {
        return new SessionImpersonator(
            $this->getCustomerClient(),
            $this->getSessionPostImpersonationPlugins(),
        );
    }

    /**
     * @return list<\SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface>
     */
    public function getSessionPostImpersonationPlugins(): array
    {
        return $this->getProvidedDependency(AgentPageDependencyProvider::PLUGINS_SESSION_POST_IMPERSONATION);
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilderInterface
     */
    public function createAgentSecurityOptionsBuilder(): AgentSecurityOptionsBuilderInterface
    {
        return new AgentSecurityOptionsBuilder(
            $this->getConfig(),
            $this->createAgentUserProvider(),
            $this->createLoginCheckUrlFormatter(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    public function createAgentLoginAuthenticator(): AuthenticatorInterface
    {
        return new AgentLoginFormAuthenticator(
            $this->createAgentUserProvider(),
            $this->createAgentAuthenticationSuccessHandler(),
            $this->createAgentAuthenticationFailureHandler(),
            $this->getRouter(),
        );
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Expander\SecurityBuilderExpanderInterface
     */
    public function createSecurityBuilderExpander(): SecurityBuilderExpanderInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            return new AgentPageSecurityPlugin();
        }

        return new SecurityBuilderExpander(
            $this->createAgentSecurityOptionsBuilder(),
            $this->getConfig(),
            $this->createSwitchUserEventSubscriber(),
            $this->createAgentLoginAuthenticator(),
        );
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Updater\AgentTokenAfterCustomerAuthenticationSuccessUpdaterInterface
     */
    public function createAgentTokenAfterCustomerAuthenticationSuccessUpdater(): AgentTokenAfterCustomerAuthenticationSuccessUpdaterInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            return new FixAgentTokenAfterCustomerAuthenticationSuccessPlugin();
        }

        return new AgentTokenAfterCustomerAuthenticationSuccessUpdater(
            $this->getSecurityAuthorizationChecker(),
            $this->getAgentClient(),
            $this->getTokenStorage(),
            $this->getCustomerClient(),
        );
    }
}
