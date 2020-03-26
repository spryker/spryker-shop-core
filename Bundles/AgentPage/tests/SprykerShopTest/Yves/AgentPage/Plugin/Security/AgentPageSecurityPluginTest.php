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

/**
 * @group SprykerShop
 * @group Yves
 * @group AgentPage
 * @group Plugin
 * @group AgentPageSecurityPluginTest
 */
class AgentPageSecurityPluginTest extends Unit
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @uses \Spryker\Yves\Session\Plugin\Application\SessionApplicationPlugin::SERVICE_SESSION
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @uses \Spryker\Yves\Messenger\Plugin\Application\FlashMessengerApplicationPlugin::SERVICE_FLASH_MESSENGER
     */
    protected const SERVICE_FLASH_MESSENGER = 'flash_messenger';

    /**
     * @var \SprykerShopTest\Yves\AgentPage\AgentPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = $this->tester->getContainer();
        $container->set(static::SERVICE_FLASH_MESSENGER, function () {
            return Stub::makeEmpty(FlashMessengerInterface::class);
        });
        $this->tester->setDependency(StorageDependencyProvider::PLUGIN_STORAGE, new StorageRedisPlugin());
        $this->tester->setDependency(CustomerPageDependencyProvider::PLUGIN_APPLICATION, $container);

        $this->tester->addRoute('home', '/', function () use ($container) {
            $user = $container->get('security.token_storage')->getToken()->getUser();

            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            return new Response($content);
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
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->tester->addSecurityPlugin(new CustomerPageSecurityPlugin());

        $container->get(static::SERVICE_SESSION)->start();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $httpKernelBrowser->request('post', '/agent/login_check', ['loginForm' => ['email' => $userTransfer->getUsername(), 'password' => 'foo']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame($userTransfer->getUsername() . 'AUTHENTICATED', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testAgentIsRedirectedOnAuthenticationFailure(): void
    {
        $container = $this->tester->getContainer();
        $userTransfer = $this->tester->haveRegisteredAgent(['password' => 'foo']);

        $securityPlugin = new AgentPageSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->tester->addSecurityPlugin(new CustomerPageSecurityPlugin());

        $container->get(self::SERVICE_SESSION)->start();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $httpKernelBrowser->request('post', '/agent/login_check', ['loginForm' => ['email' => $userTransfer->getUsername(), 'password' => 'bar']]);
        $httpKernelBrowser->followRedirect();

        $this->assertSame('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testAgentCanSwitchUser(): void
    {
        $container = $this->tester->getContainer();
        $customerTransfer = $this->tester->haveCustomer(['username' => 'user', 'password' => 'foo']);
        $userTransfer = $this->tester->haveRegisteredAgent(['password' => 'foo']);

        $securityPlugin = new AgentPageSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->tester->addSecurityPlugin(new CustomerPageSecurityPlugin());

        $container->get(self::SERVICE_SESSION)->start();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $httpKernelBrowser->request('post', '/agent/login_check', ['loginForm' => ['email' => $userTransfer->getUsername(), 'password' => 'foo']]);

        $httpKernelBrowser->request('get', '/?_switch_user=' . $customerTransfer->getEmail());
        $httpKernelBrowser->followRedirect();

        $this->assertSame($customerTransfer->getEmail() . 'AUTHENTICATED', $httpKernelBrowser->getResponse()->getContent());
    }
}
