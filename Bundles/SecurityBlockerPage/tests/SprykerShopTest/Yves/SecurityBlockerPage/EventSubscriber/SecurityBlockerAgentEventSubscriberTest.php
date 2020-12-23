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
use SprykerShop\Yves\SecurityBlockerPage\EventSubscriber\SecurityBlockerAgentEventSubscriber;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;

class SecurityBlockerAgentEventSubscriberTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SecurityBlockerPage\SecurityBlockerPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSecurityBlockerCustomerEventSubscriberWillCallSecurityBlockerClientOnKernelRequestLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(false);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('getLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock()
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
    public function testSecurityBlockerCustomerEventSubscriberWillThrowExceptionOnKernelRequestLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(true);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->once())
            ->method('getLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock()
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
    public function testSecurityBlockerCustomerEventSubscriberWillNotCallSecurityBlockerClientOnWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->getSecurityCheckAuthContextTransfer(SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(true);

        $securityBlockerMock = $this->getMockBuilder(SecurityBlockerPageToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerMock->expects($this->never())
            ->method('getLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        $securityBlockerCustomerEventSubscriber = new SecurityBlockerAgentEventSubscriber(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $securityBlockerMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock()
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
    public function testSecurityBlockerCustomerEventSubscriberWillCallSecurityBlockerClientExceptionOnFailedLoginAttempt(): void
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
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock()
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, AuthenticationEvents::AUTHENTICATION_FAILURE);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerCustomerEventSubscriberWillCallSecurityBlockerClientExceptionWrongRequest(): void
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
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock()
        );

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($securityBlockerCustomerEventSubscriber);

        $event = $this->getRequestEvent($securityCheckAuthContextTransfer);

        // Act
        $eventDispatcher->dispatch($event, AuthenticationEvents::AUTHENTICATION_FAILURE);
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
