<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use Symfony\Component\Security\Http\Firewall\ExceptionListener;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;

/**
 * @deprecated Will be removed with the next major release.
 * @see \SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin
 *
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerSecurityServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

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
        $this->setAccessDeniedHandler($app);
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
    protected function setSecurityFirewalls(Application &$app)
    {
        $selectedLanguage = $this->findSelectedLanguage($app);

        $app['security.firewalls'] = [
            CustomerPageConfig::SECURITY_FIREWALL_NAME => [
                'anonymous' => true,
                'pattern' => '^/',
                'form' => [
                    'login_path' => $this->buildLoginPath($selectedLanguage),
                    'check_path' => '/login_check',
                    'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_EMAIL . ']',
                    'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
                    'listener_class' => UsernamePasswordFormAuthenticationListener::class,
                ],
                'logout' => [
                    'logout_path' => $this->buildLogoutPath($selectedLanguage),
                    'target_url' => $this->buildLogoutTargetUrl($selectedLanguage),
                ],
                'users' => $app->share(function () {
                    return $this->getFactory()->createCustomerUserProvider();
                }),
            ],
        ];
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setSecurityAccessRules(Application &$app)
    {
        $customerSecuredPattern = $this->getFactory()
            ->getCustomerClient()
            ->getCustomerSecuredPattern();

        $app['security.access_rules'] = [
            [
                $customerSecuredPattern,
                self::ROLE_USER,
            ],
            [
                Config::get(CustomerConstants::CUSTOMER_ANONYMOUS_PATTERN),
                self::IS_AUTHENTICATED_ANONYMOUSLY,
            ],
        ];
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setAuthenticationSuccessHandler(Application &$app)
    {
        $app['security.authentication.success_handler.' . CustomerPageConfig::SECURITY_FIREWALL_NAME] = $app->share(function () {
            return $this->getFactory()->createCustomerAuthenticationSuccessHandler();
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setAuthenticationFailureHandler(Application &$app)
    {
        $app['security.authentication.failure_handler.' . CustomerPageConfig::SECURITY_FIREWALL_NAME] = $app->share(function () {
            return $this->getFactory()->createCustomerAuthenticationFailureHandler();
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setAccessDeniedHandler(Application &$app)
    {
        $selectedLanguage = $this->findSelectedLanguage($app);

        $app['security.exception_listener._proto'] = $app->protect(function ($entryPoint, $name) use ($app, $selectedLanguage) {
            return $app->share(function () use ($app, $entryPoint, $name, $selectedLanguage) {
                return new ExceptionListener(
                    $app['security.token_storage'],
                    $app['security.trust_resolver'],
                    $app['security.http_utils'],
                    $name,
                    $app[$entryPoint],
                    null,
                    $this->getFactory()->createAccessDeniedHandler($this->buildLoginPath($selectedLanguage)),
                    $app['logger']
                );
            });
        });
    }

    /**
     * @param string $prefixLocale
     *
     * @return string
     */
    protected function buildLogoutPath($prefixLocale)
    {
        $logoutPath = '/logout';
        if ($prefixLocale) {
            $logoutPath = '/' . $prefixLocale . $logoutPath;
        }

        return $logoutPath;
    }

    /**
     * @param string $prefixLocale
     *
     * @return string
     */
    protected function buildLoginPath($prefixLocale)
    {
        $loginPath = '/login';
        if ($prefixLocale) {
            $loginPath = '/' . $prefixLocale . $loginPath;
        }

        return $loginPath;
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     *
     * @param \Silex\Application $app
     *
     * @return string|null
     */
    protected function findSelectedLanguage(Application &$app)
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
}
