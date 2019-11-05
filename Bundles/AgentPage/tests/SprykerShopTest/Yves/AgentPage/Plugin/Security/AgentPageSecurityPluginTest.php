<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin\Security;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\StorageRedis\Plugin\StorageRedisPlugin;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\AgentPage\Plugin\Security\AgentPageSecurityPlugin;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin;
use Symfony\Component\HttpFoundation\Response;

class AgentPageSecurityPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\AgentPage\AgentPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

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

            return new Response('homepage');
        });
        $this->tester->addRoute('login', '/login', function () {
            return new Response('loginpage');
        });
    }

    /**
     * @return void
     */
    public function testAgentCanLogin(): void
    {
        $container = $this->tester->getContainer();
        $userTransfer = $this->tester->haveRegisteredAgent(['password' => 'foo']);

        $securityPlugin = new AgentPageSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin(new CustomerPageSecurityPlugin());
        $this->tester->addSecurityPlugin($securityPlugin);

        $container->get('session')->start();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
//        $httpKernelBrowser->request('get', '/');
        $httpKernelBrowser->request('post', '/agent/login_check', ['loginForm' => ['email' => $userTransfer->getUsername(), 'password' => 'foo']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame('authenticated', $httpKernelBrowser->getResponse()->getContent());
    }
}
