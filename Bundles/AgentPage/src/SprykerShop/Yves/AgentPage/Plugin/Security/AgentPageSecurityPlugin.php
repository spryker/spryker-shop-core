<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Shared\AgentPage\AgentPageConfig;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentPage\AgentPageConfig getConfig()
 */
class AgentPageSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @var string
     */
    public const ROLE_AGENT = 'ROLE_AGENT';

    /**
     * @var string
     */
    public const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var string
     */
    public const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_LOGIN
     *
     * @var string
     */
    protected const ROUTE_LOGIN = 'agent/login';

    /**
     * This is used as route name and is internally converted to `/agent/logout`.
     * `path('agent_logout')` can be used in templates to get the URL.
     *
     * @var string
     */
    protected const ROUTE_LOGOUT = 'agent_logout';

    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_HOME
     *
     * @var string
     */
    protected const ROUTE_HOME = 'home';

    /**
     * {@inheritDoc}
     * - Adds a firewall for the AgentPages.
     * - Adds a context and switch_user to the existing CustomerPage firewall configuration.
     * - Executes {@link \SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface} plugin stack.
     *
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewalls($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder);
        $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder);
        $securityBuilder = $this->addAccessDeniedHandler($securityBuilder);

        $this->addSwitchUserEventSubscriber($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(AgentPageConfig::SECURITY_FIREWALL_NAME, [
            'context' => AgentPageConfig::SECURITY_FIREWALL_NAME,
            'anonymous' => false,
            'pattern' => $this->getConfig()->getAgentFirewallRegex(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => $this->getFactory()->createLoginCheckUrlFormatter()->getLoginCheckPath(),
                'username_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_EMAIL . ']',
                'password_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_PASSWORD . ']',
                'with_csrf' => true,
                'csrf_parameter' => AgentLoginForm::FORM_NAME . '[_token]',
                'csrf_token_id' => AgentLoginForm::FORM_NAME,
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_HOME,
            ],
            'users' => function () {
                return $this->getFactory()->createAgentUserProvider();
            },
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => static::ROLE_PREVIOUS_ADMIN,
            ],
        ]);

        $securityBuilder->mergeFirewall(CustomerPageConfig::SECURITY_FIREWALL_NAME, [
            'context' => AgentPageConfig::SECURITY_FIREWALL_NAME,
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => static::ROLE_ALLOWED_TO_SWITCH,
            ],
        ]);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessRules(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAccessRules([
            [
                $this->getConfig()->getAgentFirewallRegex(),
                [
                    static::ROLE_AGENT,
                    static::ROLE_PREVIOUS_ADMIN,
                ],
            ],
        ]);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationSuccessHandler(AgentPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createAgentAuthenticationSuccessHandler(
                $this->getRouter($container)->generate(static::ROUTE_HOME),
            );
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationFailureHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationFailureHandler(AgentPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createAgentAuthenticationFailureHandler(
                $this->getRouter($container)->generate(static::ROUTE_HOME),
            );
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return void
     */
    protected function addSwitchUserEventSubscriber(SecurityBuilderInterface $securityBuilder): void
    {
        $securityBuilder->addEventSubscriber(function () {
            return $this->getFactory()->createSwitchUserEventSubscriber();
        });
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessDeniedHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAccessDeniedHandler(AgentPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createAccessDeniedHandler(
                $this->getRouter($container)->generate(static::ROUTE_LOGIN),
            );
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Yves\Router\Router\ChainRouter
     */
    protected function getRouter(ContainerInterface $container): ChainRouter
    {
        return $container->get(static::SERVICE_ROUTER);
    }
}
