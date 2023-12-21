<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Expander\SecurityBuilderExpanderInterface;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Security\YvesCustomerPageSecurityPlugin} instead.
 *
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerPageSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface, SecurityBuilderExpanderInterface
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin::ROLE_NAME_USER} instead.
     *
     * @var string
     */
    protected const ROLE_USER = 'ROLE_USER';

    /**
     * @var string
     */
    public const ROLE_NAME_USER = 'ROLE_USER';

    /**
     * @var string
     */
    protected const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGIN
     *
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGOUT
     *
     * @var string
     */
    protected const ROUTE_LOGOUT = 'logout';

    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_HOME
     *
     * @var string
     */
    protected const ROUTE_HOME = 'home';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTER = 'routers';

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

        $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder);
        $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder);

        $securityBuilder = $this->addAccessDeniedHandler($securityBuilder);

        $this->addInteractiveLoginEventSubscriber($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(CustomerPageConfig::SECURITY_FIREWALL_NAME, [
            'anonymous' => true,
            'pattern' => '^/',
            'remember_me' => [
                'secret' => $this->getConfig()->getRememberMeSecret(),
                'lifetime' => $this->getConfig()->getRememberMeLifetime(),
                'remember_me_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_REMEMBER_ME . ']',
            ],
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => $this->getFactory()->createLoginCheckUrlFormatter()->getLoginCheckPath(),
                'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_EMAIL . ']',
                'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
                'with_csrf' => true,
                'csrf_parameter' => LoginForm::FORM_NAME . '[_token]',
                'csrf_token_id' => LoginForm::FORM_NAME,
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
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
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
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationSuccessHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function () {
            return $this->getFactory()->createCustomerAuthenticationSuccessHandler();
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
        $securityBuilder->addAuthenticationFailureHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createCustomerAuthenticationFailureHandler(
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
    protected function addAccessDeniedHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAccessDeniedHandler(CustomerPageConfig::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
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

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addInteractiveLoginEventSubscriber(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addEventSubscriber(function () {
            return $this->getFactory()->createInteractiveLoginEventSubscriber();
        });
    }
}
