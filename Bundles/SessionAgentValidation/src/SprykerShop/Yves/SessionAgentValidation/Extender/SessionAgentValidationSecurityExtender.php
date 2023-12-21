<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListenerInterface;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;

class SessionAgentValidationSecurityExtender implements SessionAgentValidationSecurityExtenderInterface
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO
     *
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.agent_session_validator._proto';

    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * @uses \SprykerShop\Shared\AgentPage\AgentPageConfig::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_AGENT_FIREWALL_NAME = 'agent';

    /**
     * @var string
     */
    protected const SECURITY_AGENT_SESSION_VALIDATOR = 'security.authentication_listener.agent_session_validator';

    /**
     * @var string
     */
    protected const SECURITY_FACTORY_AGENT_SESSION_VALIDATOR = 'security.authentication_listener.factory.agent_session_validator';

    /**
     * @var string
     */
    protected const FIREWALL_KEY_CONTEXT = 'context';

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListenerInterface
     */
    protected ValidateAgentSessionListenerInterface $validateAgentSessionListener;

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig
     */
    protected SessionAgentValidationConfig $config;

    /**
     * @param \SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListenerInterface $validateAgentSessionListener
     * @param \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig $config
     */
    public function __construct(
        ValidateAgentSessionListenerInterface $validateAgentSessionListener,
        SessionAgentValidationConfig $config
    ) {
        $this->validateAgentSessionListener = $validateAgentSessionListener;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $this->extendFirewall($securityBuilder);
        $this->extendAgentFirewall($securityBuilder);
        $this->addAuthenticationListenerFactory($container);
        $this->addAuthenticationListenerPrototype($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function extendFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityFirewall = $this->findFirewall($securityBuilder, static::SECURITY_FIREWALL_NAME);
        if ($securityFirewall === null) {
            return $securityBuilder;
        }

        $configuration = [
            $this->config->getAuthenticationListenerFactoryType() => static::SECURITY_AGENT_SESSION_VALIDATOR,
        ];

        if (array_key_exists(static::FIREWALL_KEY_CONTEXT, $securityFirewall)) {
            $configuration[static::FIREWALL_KEY_CONTEXT] = $securityFirewall[static::FIREWALL_KEY_CONTEXT];
        }

        return $securityBuilder->mergeFirewall(static::SECURITY_FIREWALL_NAME, $configuration);
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function extendAgentFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityFirewall = $this->findFirewall($securityBuilder, static::SECURITY_AGENT_FIREWALL_NAME);
        if ($securityFirewall === null) {
            return $securityBuilder;
        }

        return $securityBuilder->mergeFirewall(static::SECURITY_AGENT_FIREWALL_NAME, [
            $this->config->getAuthenticationListenerFactoryType() => static::SECURITY_AGENT_SESSION_VALIDATOR,
        ]);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerFactory(ContainerInterface $container): ContainerInterface
    {
        $container->set(
            static::SECURITY_FACTORY_AGENT_SESSION_VALIDATOR,
            $container->protect(function ($firewallName) use ($container) {
                $listenerName = sprintf('security.authentication_listener.%s.agent_session_validator', $firewallName);
                if (!$container->has($listenerName)) {
                    $container->set(
                        $listenerName,
                        $container->get(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO)($firewallName),
                    );
                }

                return $this->isSymfonyVersion5() === true ?
                     [
                         sprintf('security.authentication_provider.%s.dao', $firewallName),
                         $listenerName,
                         null,
                         $this->config->getAuthenticationListenerFactoryType(),
                     ] :
                     [
                         $listenerName,
                         null,
                         $this->config->getAuthenticationListenerFactoryType(),
                     ];
            }),
        );

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO, $container->protect(function () use ($container) {
            return function () use ($container) {
                $this->validateAgentSessionListener->setTokenStorage($container->get(static::SERVICE_SECURITY_TOKEN_STORAGE));

                return $this->validateAgentSessionListener;
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param string $firewallName
     *
     * @return array<string, mixed>|null
     */
    protected function findFirewall(SecurityBuilderInterface $securityBuilder, string $firewallName): ?array
    {
        $firewalls = (clone $securityBuilder)->getConfiguration()->getFirewalls();

        if (array_key_exists($firewallName, $firewalls)) {
            return $firewalls[$firewallName];
        }

        return null;
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
