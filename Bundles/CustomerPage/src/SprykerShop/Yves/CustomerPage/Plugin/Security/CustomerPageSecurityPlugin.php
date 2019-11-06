<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Security\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Router\Router\ChainRouter;
use Spryker\Yves\Security\Configuration\SecurityBuilderInterface;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerPageSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const ROLE_USER = 'ROLE_USER';
    protected const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGIN
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGOUT
     */
    protected const ROUTE_LOGOUT = 'logout';

    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_HOME
     */
    protected const ROUTE_HOME = 'home';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
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

        $securityBuilder = $this->addAccessDeniedHandler($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(CustomerPageConfig::SECURITY_FIREWALL_NAME, [
            'anonymous' => true,
            'pattern' => '^/',
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => '/login_check',
                'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_EMAIL . ']',
                'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_HOME,
            ],
            'users' => function () {
                return $this->getFactory()->createCustomerUserProvider();
            },
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
        $customerSecuredPattern = $this->getFactory()
            ->getCustomerClient()
            ->getCustomerSecuredPattern();

        $securityBuilder->addAccessRules([
            [
                $customerSecuredPattern,
                static::ROLE_USER,
            ],
            [
                $this->getConfig()->getAnonymousPattern(),
                static::IS_AUTHENTICATED_ANONYMOUSLY,
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
        $securityBuilder->addAuthenticationSuccessHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function () {
            return $this->getFactory()->createCustomerAuthenticationSuccessHandler();
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
        $securityBuilder->addAuthenticationFailureHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createCustomerAuthenticationFailureHandler(
                $this->getRouter($container)->generate(static::ROUTE_HOME)
            );
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    protected function addAccessDeniedHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAccessDeniedHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createAccessDeniedHandler(
                $this->getRouter($container)->generate(static::ROUTE_LOGIN)
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
