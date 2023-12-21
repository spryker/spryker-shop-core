<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;

class SessionCustomerValidationSecurityExtender implements SessionCustomerValidationSecurityExtenderInterface
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO
     *
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.customer_session_validator._proto';

    /**
     * @uses \SprykerShop\Shared\CustomerPage\CustomerPageConfig::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * @var string
     */
    protected const SECURITY_CUSTOMER_SESSION_VALIDATOR = 'security.authentication_listener.customer_session_validator';

    /**
     * @var string
     */
    protected const SECURITY_FACTORY_CUSTOMER_SESSION_VALIDATOR = 'security.authentication_listener.factory.customer_session_validator';

    /**
     * @var string
     */
    protected const FIREWALL_KEY_CONTEXT = 'context';

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface
     */
    protected ValidateCustomerSessionListenerInterface $validateCustomerSessionListener;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig
     */
    protected SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig;

    /**
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface $validateCustomerSessionListener
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
     */
    public function __construct(
        ValidateCustomerSessionListenerInterface $validateCustomerSessionListener,
        SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
    ) {
        $this->validateCustomerSessionListener = $validateCustomerSessionListener;
        $this->sessionCustomerValidationPageConfig = $sessionCustomerValidationPageConfig;
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
        $customerFirewallConfiguration = $this->findFirewall($securityBuilder, static::SECURITY_FIREWALL_NAME);
        if ($customerFirewallConfiguration === null) {
            return $securityBuilder;
        }

        return $securityBuilder->mergeFirewall(static::SECURITY_FIREWALL_NAME, [
            $this->sessionCustomerValidationPageConfig->getAuthenticationListenerFactoryType() => static::SECURITY_CUSTOMER_SESSION_VALIDATOR,
            static::FIREWALL_KEY_CONTEXT => $customerFirewallConfiguration[static::FIREWALL_KEY_CONTEXT],
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
            static::SECURITY_FACTORY_CUSTOMER_SESSION_VALIDATOR,
            $container->protect(
                function ($firewallName) use ($container) {
                    $listenerName = sprintf('security.authentication_listener.%s.customer_session_validator', $firewallName);
                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO)($firewallName),
                        );
                    }

                    return $this->isSymfonyVersion5() === true ?
                        [
                            sprintf('security.authentication_provider.%s.anonymous', $firewallName),
                            $listenerName,
                            null,
                            $this->sessionCustomerValidationPageConfig->getAuthenticationListenerFactoryType(),
                        ] :
                        [
                            $listenerName,
                            null,
                            $this->sessionCustomerValidationPageConfig->getAuthenticationListenerFactoryType(),
                        ];
                },
            ),
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
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO, $container->protect(function () use ($container) {
            return function () use ($container) {
                $this->validateCustomerSessionListener->setTokenStorage($container->get(static::SERVICE_SECURITY_TOKEN_STORAGE));

                return $this->validateCustomerSessionListener;
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
