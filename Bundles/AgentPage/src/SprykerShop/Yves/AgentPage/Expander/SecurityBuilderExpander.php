<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Shared\AgentPage\AgentPageConfig as SharedAgentPageConfig;
use SprykerShop\Yves\AgentPage\AgentPageConfig;
use SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilderInterface;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AccessDeniedHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_AGENT_LOGIN_FORM_AUTHENTICATOR = 'security.agent.login_form.authenticator';

    /**
     * @var string
     */
    protected const ROLE_AGENT = 'ROLE_AGENT';

    /**
     * @var string
     */
    protected const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * @var string
     */
    protected const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @uses \SprykerShop\Shared\CustomerPage\CustomerPageConfig::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

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
     * @var \SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilderInterface
     */
    protected AgentSecurityOptionsBuilderInterface $agentSecurityOptionsBuilder;

    /**
     * @var \SprykerShop\Yves\AgentPage\AgentPageConfig
     */
    protected AgentPageConfig $config;

    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected EventSubscriberInterface $eventSubscriber;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @param \SprykerShop\Yves\AgentPage\Builder\AgentSecurityOptionsBuilderInterface $agentSecurityOptionsBuilder
     * @param \SprykerShop\Yves\AgentPage\AgentPageConfig $config
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $eventSubscriber
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     */
    public function __construct(
        AgentSecurityOptionsBuilderInterface $agentSecurityOptionsBuilder,
        AgentPageConfig $config,
        EventSubscriberInterface $eventSubscriber,
        AuthenticatorInterface $authenticator
    ) {
        $this->agentSecurityOptionsBuilder = $agentSecurityOptionsBuilder;
        $this->config = $config;
        $this->eventSubscriber = $eventSubscriber;
        $this->authenticator = $authenticator;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewalls($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $securityBuilder = $this->addSwitchUserEventSubscriber($securityBuilder);
        $securityBuilder = $this->addAccessDeniedHandler($securityBuilder);
        $this->addAuthenticator($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(
            SharedAgentPageConfig::SECURITY_FIREWALL_NAME,
            $this->agentSecurityOptionsBuilder->buildOptions(),
        );

        $securityBuilder->mergeFirewall(static::SECURITY_FIREWALL_NAME, [
            'context' => SharedAgentPageConfig::SECURITY_FIREWALL_NAME,
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
        return $securityBuilder->addAccessRules([
            [
                $this->config->getAgentFirewallRegex(),
                [
                    static::ROLE_AGENT,
                    static::ROLE_PREVIOUS_ADMIN,
                ],
            ],
        ]);
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addSwitchUserEventSubscriber(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addEventSubscriber(function () {
            return $this->eventSubscriber;
        });
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function addAuthenticator(ContainerInterface $container): void
    {
        $container->set(static::SECURITY_AGENT_LOGIN_FORM_AUTHENTICATOR, function () {
            return $this->authenticator;
        });
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessDeniedHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAccessDeniedHandler(SharedAgentPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return new AccessDeniedHandler(
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
