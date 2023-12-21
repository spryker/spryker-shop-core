<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Builder;

use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use SprykerShop\Yves\CustomerPage\Formatter\LoginCheckUrlFormatterInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomerSecurityOptionsBuilder implements CustomerSecurityOptionsBuilderInterface
{
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
     * @uses \SprykerShop\Yves\CustomerPage\Expander\SecurityBuilderExpander::SECURITY_CUSTOMER_LOGIN_FORM_AUTHENTICATOR
     *
     * @var string
     */
    protected const SECURITY_CUSTOMER_LOGIN_FORM_AUTHENTICATOR = 'security.secured.login_form.authenticator';

    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected CustomerPageConfig $config;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Formatter\LoginCheckUrlFormatterInterface
     */
    protected LoginCheckUrlFormatterInterface $loginCheckUrlFormatter;

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $config
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \SprykerShop\Yves\CustomerPage\Formatter\LoginCheckUrlFormatterInterface $loginCheckUrlFormatter
     */
    public function __construct(
        CustomerPageConfig $config,
        UserProviderInterface $userProvider,
        LoginCheckUrlFormatterInterface $loginCheckUrlFormatter
    ) {
        $this->config = $config;
        $this->userProvider = $userProvider;
        $this->loginCheckUrlFormatter = $loginCheckUrlFormatter;
    }

    /**
     * @return array<mixed>
     */
    public function buildOptions(): array
    {
        return [
            'pattern' => '^/',
            'remember_me' => [
                'secret' => $this->config->getRememberMeSecret(),
                'lifetime' => $this->config->getRememberMeLifetime(),
                'remember_me_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_REMEMBER_ME . ']',
            ],
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => $this->loginCheckUrlFormatter->getLoginCheckPath(),
                'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_EMAIL . ']',
                'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
                'with_csrf' => true,
                'csrf_parameter' => LoginForm::FORM_NAME . '[_token]',
                'csrf_token_id' => LoginForm::FORM_NAME,
                'authenticators' => [
                    static::SECURITY_CUSTOMER_LOGIN_FORM_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_HOME,
            ],
            'users' => function () {
                return $this->userProvider;
            },
        ];
    }
}
