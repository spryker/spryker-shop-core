<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Security\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Security\Configuration\SecurityBuilderInterface;
use SprykerShop\Shared\AgentPage\AgentPageConfig;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentPage\AgentPageConfig getConfig()
 */
class AgentPageSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const ROLE_AGENT = 'ROLE_AGENT';
    protected const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';
    protected const ROLE_USER = 'ROLE_USER';
    protected const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_LOGIN
     */
    protected const ROUTE_LOGIN = 'agent/login';

    protected const ROUTE_CHECK_PATH = '/agent/login_check';

    protected const ROUTE_LOGOUT = 'agent_logout';

    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_HOME
     */
    protected const ROUTE_HOME = 'home';

    /**
     * {@inheritDoc}
     * - Adds a firewall for the AgentPages.
     * - Adds a context and switch_user to the existing CustomerPage firewall configuration.
     *
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewalls($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder);
        $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder);

        $this->addSwitchUserEventSubscriber($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(AgentPageConfig::SECURITY_FIREWALL_NAME, [
            'context' => AgentPageConfig::SECURITY_FIREWALL_NAME,
            'anonymous' => false,
            'pattern' => $this->getConfig()->getAgentFirewallRegex(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => static::ROUTE_CHECK_PATH,
                'username_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_EMAIL . ']',
                'password_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_PASSWORD . ']',
                'listener_class' => UsernamePasswordFormAuthenticationListener::class,
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

        $securityBuilder->addFirewall(CustomerPageConfig::SECURITY_FIREWALL_NAME, [
            'context' => AgentPageConfig::SECURITY_FIREWALL_NAME,
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => static::ROLE_ALLOWED_TO_SWITCH,
            ],
        ]);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
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
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationSuccessHandler(AgentPageConfig::SECURITY_FIREWALL_NAME, function () {
            return $this->getFactory()->createAgentAuthenticationSuccessHandler();
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationFailureHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationFailureHandler(AgentPageConfig::SECURITY_FIREWALL_NAME, function () {
            return $this->getFactory()->createAgentAuthenticationFailureHandler();
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return void
     */
    protected function addSwitchUserEventSubscriber(SecurityBuilderInterface $securityBuilder): void
    {
        $securityBuilder->addEventSubscriber(function () {
            return $this->getFactory()->createSwitchUserEventSubscriber();
        });
    }
}
