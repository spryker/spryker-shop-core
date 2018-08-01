<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Form\AgentLoginForm;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentPageSecurityServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const FIREWALL_SECURED = 'secured';
    public const FIREWALL_AGENT = 'agent';

    public const ROLE_AGENT = 'ROLE_AGENT';
    public const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->setSecurityFirewalls($app);
        $this->setSecurityAccessRules($app);
        $this->setAuthenticationSuccessHandler($app);
        $this->setAuthenticationFailureHandler($app);
        $this->setSwitchUserEventSubscriber($app);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setSecurityFirewalls(Application $app)
    {
        $selectedLanguage = $this->findSelectedLanguage($app);

        $app['security.firewalls'] = array_merge_recursive([
            static::FIREWALL_AGENT => [
                'context' => static::FIREWALL_AGENT,
                'anonymous' => false,
                'pattern' => '\/agent(.+)?\/(?!login$).+',
                'form' => [
                    'login_path' => '/agent/login',
                    'check_path' => '/agent/login_check',
                    'username_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_EMAIL . ']',
                    'password_parameter' => AgentLoginForm::FORM_NAME . '[' . AgentLoginForm::FIELD_PASSWORD . ']',
                    'listener_class' => UsernamePasswordFormAuthenticationListener::class,
                ],
                'logout' => [
                    'logout_path' => '/agent/logout',
                    'target_url' => $this->buildLogoutTargetUrl($selectedLanguage),
                ],
                'users' => $app->share(function () {
                    return $this->getFactory()->createAgentUserProvider();
                }),
            ],
            self::FIREWALL_SECURED => [
                'context' => static::FIREWALL_AGENT,
                'switch_user' => [
                    'parameter' => '_switch_user',
                    'role' => static::ROLE_ALLOWED_TO_SWITCH,
                ],
            ],
        ], $app['security.firewalls']);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setSecurityAccessRules(Application $app)
    {
        $app['security.access_rules'] = array_merge([
            [
                '\/agent\/(?!login$).+',
                static::ROLE_AGENT,
            ],
        ], $app['security.access_rules']);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setAuthenticationSuccessHandler(Application $app): void
    {
        $app['security.authentication.success_handler.' . static::FIREWALL_AGENT] = $app->share(function () {
            return $this->getFactory()->createAgentAuthenticationSuccessHandler();
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setAuthenticationFailureHandler(Application $app): void
    {
        $app['security.authentication.failure_handler.' . static::FIREWALL_AGENT] = $app->share(function () {
            return $this->getFactory()->createAgentAuthenticationFailureHandler();
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setSwitchUserEventSubscriber(Application $app): void
    {
        $this->getDispatcher($app)->addSubscriber(
            $this->getFactory()->createSwitchUserEventSubscriber()
        );
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     *
     * @param \Silex\Application $app
     *
     * @return string|null
     */
    protected function findSelectedLanguage(Application $app)
    {
        $currentLocale = $app['locale'];
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        $prefixLocale = mb_substr($currentLocale, 0, 2);
        $localePath = mb_substr($requestUri, 1, 3);

        if ($prefixLocale . '/' !== $localePath) {
            return null;
        }
        return $prefixLocale;
    }

    /**
     * @param string $selectedLanguage
     *
     * @return string
     */
    protected function buildLogoutTargetUrl($selectedLanguage)
    {
        $logoutTarget = '/';
        if ($selectedLanguage) {
            $logoutTarget .= $selectedLanguage;
        }
        return $logoutTarget;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getDispatcher(Application $app): EventDispatcherInterface
    {
        return $app['dispatcher'];
    }
}
