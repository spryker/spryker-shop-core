<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SecurityBlockerPage\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerCustomerEventDispatcherPlugin;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageDependencyProvider;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageFactory;

class SecurityBlockerCustomerEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SecurityBlockerPage\SecurityBlockerPageTester
     */
    protected $tester;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerCustomerEventDispatcherPlugin
     */
    protected $securityBlockerCustomerEventDispatcherPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $securityBlockerPageDependencyProvider = new SecurityBlockerPageDependencyProvider();
        $securityBlockerPageDependencyProvider->provideDependencies($container);

        $securityBlockerPageFactoryMock = $this->getMockBuilder(SecurityBlockerPageFactory::class)->getMock();
        $securityBlockerPageFactoryMock->setContainer($container);

        $this->securityBlockerCustomerEventDispatcherPlugin = new SecurityBlockerCustomerEventDispatcherPlugin();
        $this->securityBlockerCustomerEventDispatcherPlugin->setFactory($securityBlockerPageFactoryMock);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerCustomerEventDispatcherPluginWillAddSubscriber(): void
    {
        // Arrange
        $container = new Container();
        $securityBlockerPageDependencyProvider = new SecurityBlockerPageDependencyProvider();
        $securityBlockerPageDependencyProvider->provideDependencies($container);

        $securityBlockerPageFactoryMock = $this->getMockBuilder(SecurityBlockerPageFactory::class)->getMock();
        $securityBlockerPageFactoryMock->setContainer($container);

        $securityBlockerCustomerEventDispatcherPlugin = new SecurityBlockerCustomerEventDispatcherPlugin();
        $securityBlockerCustomerEventDispatcherPlugin->setFactory($securityBlockerPageFactoryMock);

        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $eventDispatcherMock->expects($this->once())
            ->method('addSubscriber');

        // Act
        $securityBlockerCustomerEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }
}
