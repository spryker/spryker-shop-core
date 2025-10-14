<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SecurityBlockerPage\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilder;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToStoreClientInterface;
use SprykerShop\Yves\SecurityBlockerPage\EventSubscriber\SecurityBlockerAgentEventSubscriber;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class SecurityBlockerAgentEventSubscriberTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SecurityBlockerPage\SecurityBlockerPageTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en';

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberWillCallSecurityBlockerClientOnKernelRequestLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(false);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(true),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberWillThrowExceptionOnKernelRequestLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(true);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(true),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        $this->expectException(HttpException::class);

        // Act
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberWillNotCallSecurityBlockerClientOnWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(true);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->never())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(true),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getInvalidRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberWillCallSecurityBlockerClientExceptionOnFailedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('incrementLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->getMock();
        $requestStackMock->method('getCurrentRequest')
            ->willReturn($this->tester->getRequest($securityCheckAuthContextTransfer));

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $requestStackMock,
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(true),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, LoginFailureEvent::class);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberWillCallSecurityBlockerClientExceptionWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->never())
            ->method('incrementLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->getMock();
        $requestStackMock->method('getCurrentRequest')
            ->willReturn($this->tester->getInvalidRequest($securityCheckAuthContextTransfer));

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $requestStackMock,
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(true),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, LoginFailureEvent::class);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentEventSubscriberShouldUseOnlyIPWhenUseEmailForLoginSecurityBlockerConfigDisabled(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setType(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE)
            ->setIp('66.66.66.6');

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(false);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);
        $securityBlockerMock->expects($this->once())
            ->method('incrementLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer);

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->getMock();
        $requestStackMock->method('getCurrentRequest')
            ->willReturn($this->tester->getRequest($securityCheckAuthContextTransfer));

        $securityBlockerAgentEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $requestStackMock,
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->getSecurityBlockerPageConfigMock(false),
            static::LOCALE_NAME_EN,
            $this->getMockBuilder(SecurityBlockerPageToStoreClientInterface::class)->getMock(),
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerAgentEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
        $eventDispatcher->dispatch($event, LoginFailureEvent::class);
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function getRequestEvent(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): RequestEvent
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        $request = $this->tester->getRequest($securityCheckAuthContextTransfer);

        return new RequestEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);
    }

    /**
     * @param bool $useEmailForLoginSecurityBlocker
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig
     */
    protected function getSecurityBlockerPageConfigMock(bool $useEmailForLoginSecurityBlocker): SecurityBlockerPageConfig
    {
        $securityBlockerPageConfig = $this->getMockBuilder(SecurityBlockerPageConfig::class)->getMock();
        $securityBlockerPageConfig->method('useEmailContextForLoginSecurityBlocker')
            ->willReturn($useEmailForLoginSecurityBlocker);

        return $securityBlockerPageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function getInvalidRequestEvent(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): RequestEvent
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        $request = $this->tester->getInvalidRequest($securityCheckAuthContextTransfer);

        return new RequestEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}
