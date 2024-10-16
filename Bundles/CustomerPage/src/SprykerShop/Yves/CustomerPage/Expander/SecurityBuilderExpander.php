<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig as SharedCustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\AccessDeniedHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_CUSTOMER_LOGIN_FORM_AUTHENTICATOR = 'security.secured.login_form.authenticator';

    /**
     * @var string
     */
    protected const ROLE_NAME_USER = 'ROLE_USER';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGIN
     *
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @var \SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface
     */
    protected CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    protected CustomerPageToCustomerClientInterface $customerClient;

    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected CustomerPageConfig $customerPageConfig;

    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected EventSubscriberInterface $eventSubscriber;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @var \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected EventSubscriberInterface $userCheckerListener;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $eventSubscriber
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $userCheckerListener
     */
    public function __construct(
        CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder,
        CustomerPageToCustomerClientInterface $customerClient,
        CustomerPageConfig $customerPageConfig,
        EventSubscriberInterface $eventSubscriber,
        AuthenticatorInterface $authenticator,
        EventSubscriberInterface $userCheckerListener
    ) {
        $this->customerSecurityOptionsBuilder = $customerSecurityOptionsBuilder;
        $this->customerClient = $customerClient;
        $this->customerPageConfig = $customerPageConfig;
        $this->eventSubscriber = $eventSubscriber;
        $this->authenticator = $authenticator;
        $this->userCheckerListener = $userCheckerListener;
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
        $securityBuilder = $this->addAccessDeniedHandler($securityBuilder);
        $securityBuilder = $this->addInteractiveLoginEventSubscriber($securityBuilder);
        $this->addAuthenticator($container);

        $this->addUserCheckerListener($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addUserCheckerListener(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addEventSubscriber(function () {
            return $this->userCheckerListener;
        });
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addFirewall(
            SharedCustomerPageConfig::SECURITY_FIREWALL_NAME,
            $this->customerSecurityOptionsBuilder->buildOptions(),
        );
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
                $this->customerClient->getCustomerSecuredPattern(),
                static::ROLE_NAME_USER,
            ],
            [
                $this->customerPageConfig->getAnonymousPattern(),
                static::ACCESS_MODE_PUBLIC,
            ],
        ]);
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addInteractiveLoginEventSubscriber(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
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
        $container->set(static::SECURITY_CUSTOMER_LOGIN_FORM_AUTHENTICATOR, function () {
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
        $securityBuilder->addAccessDeniedHandler(SharedCustomerPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
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
