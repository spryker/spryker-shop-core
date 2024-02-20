<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerRememberMeSecurityPlugin;
use SprykerShopTest\Yves\CustomerPage\CustomerPageTester;
use SprykerShopTest\Yves\CustomerPage\Fixtures\DefaultAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Security
 * @group Plugin
 * @group Security
 * @group CustomerRememberMeSecurityPluginTest
 * Add your own group annotations below this line
 */
class CustomerRememberMeSecurityPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @var string
     */
    protected const AUTHENTICATED_FULLY = 'AUTHENTICATED_FULLY';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @var string
     */
    protected const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';

    /**
     * @var string
     */
    protected const REMEMBERME = 'REMEMBERME';

    /**
     * @var string
     */
    protected const SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR = 'security.default.login_form.authenticator';

    /**
     * @var \SprykerShopTest\Yves\CustomerPage\CustomerPageTester
     */
    protected CustomerPageTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->tester->isSymfonyVersion5() === true) {
            $this->markTestSkipped('Compatible only with `symfony/security-core` package version >= 6. Will be enabled by default once Symfony 5 support is discontinued.');
        }

        $this->tester->addSecurityPlugin(new CustomerRememberMeSecurityPlugin());
        $this->tester->setDependency(CustomerPageDependencyProvider::SERVICE_LOCALE, 'en_US');
    }

    /**
     * @return void
     */
    public function testRememberMeAuthentication(): void
    {
        //Arrange
        $this->addAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        //Act
        $httpKernelBrowser->request('get', '/');
        //Assert
        $this->assertSame(static::ACCESS_MODE_PUBLIC, $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', '/login_check', ['loginForm' => ['username' => 'user', 'password' => 'foo', 'remember_me' => 'true']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame(static::AUTHENTICATED_FULLY, $httpKernelBrowser->getResponse()->getContent());
        $this->assertNotNull($httpKernelBrowser->getCookiejar()->get(static::REMEMBERME), 'The REMEMBERME cookie is not set');

        $httpKernelBrowser->getCookiejar()->expire($this->tester::MOCKSESSID);
        $httpKernelBrowser->request('get', '/');
        $this->assertSame(static::ACCESS_MODE_PUBLIC, $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/logout');
        $httpKernelBrowser->followRedirect();
        $this->assertNull($httpKernelBrowser->getCookiejar()->get(static::REMEMBERME), 'The REMEMBERME cookie has not been removed yet');
    }

    /**
     * @return void
     */
    protected function addAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('default', [
                'pattern' => '^.*$',
                'form' => [
                    'require_previous_session' => false,
                    'authenticators' => [
                        static::SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR,
                    ],
                ],
                'remember_me' => [],
                'logout' => true,
                'users' => [
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
            ]);

        $container = $this->tester->getContainer();

        $this->tester->mockYvesSecurityPlugin($securityConfiguration);
        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();

        $container->set(static::SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR, function () {
            return new DefaultAuthenticator();
        });

        $this->tester->addRoute('homepage', '/', function () {
            $authorizationChecker = $this->getAuthorizationChecker();

            if ($authorizationChecker->isGranted(static::IS_AUTHENTICATED_FULLY)) {
                return new Response(static::AUTHENTICATED_FULLY);
            }

            return new Response(static::ACCESS_MODE_PUBLIC);
        });
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        $container = $this->tester->getContainer();

        return $container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }
}
