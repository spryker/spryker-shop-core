<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Security;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\StorageRedis\Plugin\StorageRedisPlugin;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin;
use Symfony\Component\HttpFoundation\Response;

class CustomerPageSecurityPluginTest extends Unit
{
    /**
     * @uses \Spryker\Yves\Locale\Plugin\Application\LocaleApplicationPlugin::SERVICE_LOCALE
     *
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var \SprykerShopTest\Yves\CustomerPage\CustomerPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->tester->isSymfonyVersion5() !== true) {
            $this->markTestSkipped('Compatible only with `symfony/security-core` package version ^5.0.0. To be removed once Symfony 5 support is discontinued.');
        }

        $container = $this->tester->getContainer();
        $container->set('flash_messenger', function () {
            return Stub::makeEmpty(FlashMessengerInterface::class);
        });
        $this->tester->setDependency(StorageDependencyProvider::PLUGIN_STORAGE, new StorageRedisPlugin());
        $this->tester->setDependency(CustomerPageDependencyProvider::PLUGIN_APPLICATION, $container);

        $this->tester->addRoute('home', '/', function () use ($container) {
            if ($container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                return new Response('authenticated');
            }

            return new Response('unauthorized');
        });
        $this->tester->addRoute('login', '/login', function () {
            return new Response('loginpage');
        });
        $this->tester->addRoute('login_check', '/login_check', function () use ($container) {
            return new Response('login_check');
        });
    }

    /**
     * @return void
     */
    public function testCustomerCanLogin(): void
    {
        $container = $this->tester->getContainer();
        $customerTransfer = $this->tester->haveCustomer(['password' => 'foo']);

        $securityPlugin = new CustomerPageSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

        $container->get('session')->start();
        $container->set(CustomerPageDependencyProvider::SERVICE_LOCALE, 'en_US');
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $httpKernelBrowser->request('post', '/login_check', ['loginForm' => ['email' => $customerTransfer->getEmail(), 'password' => 'foo']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame('authenticated', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testCustomerAccessDenied(): void
    {
        $container = $this->tester->getContainer();
        $customerTransfer = $this->tester->haveCustomer(['password' => 'foo']);

        $securityPlugin = new CustomerPageSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

        $container->get('session')->start();
        $container->set(CustomerPageDependencyProvider::SERVICE_LOCALE, 'en_US');

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('post', '/login_check', ['loginForm' => ['email' => $customerTransfer->getEmail(), 'password' => 'bar']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame('unauthorized', $httpKernelBrowser->getResponse()->getContent());
    }
}
