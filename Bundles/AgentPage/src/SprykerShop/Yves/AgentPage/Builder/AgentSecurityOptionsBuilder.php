<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Builder;

use SprykerShop\Shared\AgentPage\AgentPageConfig as SharedAgentPageConfig;
use SprykerShop\Yves\AgentPage\AgentPageConfig;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;
use SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatterInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AgentSecurityOptionsBuilder implements AgentSecurityOptionsBuilderInterface
{
    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_LOGIN
     *
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

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
     * @uses \SprykerShop\Yves\AgentPage\Expander\SecurityBuilderExpander::SECURITY_AGENT_LOGIN_FORM_AUTHENTICATOR
     *
     * @var string
     */
    protected const SECURITY_AGENT_LOGIN_FORM_AUTHENTICATOR = 'security.agent.login_form.authenticator';

    /**
     * @var string
     */
    public const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * @var \SprykerShop\Yves\AgentPage\AgentPageConfig
     */
    protected AgentPageConfig $config;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @var \SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatterInterface
     */
    protected LoginCheckUrlFormatterInterface $loginCheckUrlFormatter;

    /**
     * @param \SprykerShop\Yves\AgentPage\AgentPageConfig $config
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \SprykerShop\Yves\AgentPage\Formatter\LoginCheckUrlFormatterInterface $loginCheckUrlFormatter
     */
    public function __construct(
        AgentPageConfig $config,
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
            'context' => SharedAgentPageConfig::SECURITY_FIREWALL_NAME,
            'pattern' => $this->config->getAgentFirewallRegex(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => $this->loginCheckUrlFormatter->getLoginCheckPath(),
                'username_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_EMAIL . ']',
                'password_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_PASSWORD . ']',
                'with_csrf' => true,
                'csrf_parameter' => AgentLoginForm::FORM_NAME . '[_token]',
                'csrf_token_id' => AgentLoginForm::FORM_NAME,
                'authenticators' => [
                    static::SECURITY_AGENT_LOGIN_FORM_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_HOME,
            ],
            'users' => function () {
                return $this->userProvider;
            },
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => static::ROLE_PREVIOUS_ADMIN,
            ],
        ];
    }
}
