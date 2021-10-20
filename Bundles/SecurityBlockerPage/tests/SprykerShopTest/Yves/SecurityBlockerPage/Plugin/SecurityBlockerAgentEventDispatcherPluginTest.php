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
use SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerAgentEventDispatcherPlugin;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageDependencyProvider;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageFactory;

class SecurityBlockerAgentEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SecurityBlockerPage\SecurityBlockerPageTester
     */
    protected $tester;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerAgentEventDispatcherPlugin
     */
    protected $securityBlockerAgentEventDispatcherPlugin;

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

        $this->securityBlockerAgentEventDispatcherPlugin = new SecurityBlockerAgentEventDispatcherPlugin();
        $this->securityBlockerAgentEventDispatcherPlugin->setFactory($securityBlockerPageFactoryMock);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventDispatcherPluginWillAddSubscriber(): void
    {
        // Arrange
        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $eventDispatcherMock->expects($this->once())
            ->method('addSubscriber');

        // Act
        $this->securityBlockerAgentEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }
}
