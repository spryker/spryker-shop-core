<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\EventSubscriber\SaveCustomerSessionEventSubscriber;
use SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionCustomerValidationPage
 * @group EventSubscriber
 * @group SaveCustomerSessionEventSubscriberTest
 * Add your own group annotations below this line
 */
class SaveCustomerSessionEventSubscriberTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester
     */
    protected SessionCustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnEvents(): void
    {
        // Act
        $subscribedEvents = SaveCustomerSessionEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertNotEmpty($subscribedEvents);
        $this->assertArrayHasKey(SecurityEvents::INTERACTIVE_LOGIN, $subscribedEvents);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenRequestDoesNotHaveSession(): void
    {
        // Arrange
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $saveCustomerSessionEventSubscriber = new SaveCustomerSessionEventSubscriber(
            $this->tester->createCustomerClientMock(),
            $customerSessionSaverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(),
            $this->tester->createAuthenticationTokenMock(),
        );

        // Assert
        $customerSessionSaverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveCustomerSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenUserIsNotSet(): void
    {
        // Arrange
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $saveCustomerSessionEventSubscriber = new SaveCustomerSessionEventSubscriber(
            $this->tester->createCustomerClientMock(),
            $customerSessionSaverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->tester->createAuthenticationTokenMock(),
        );

        // Assert
        $customerSessionSaverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveCustomerSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenCustomerIsNotFound(): void
    {
        // Arrange
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $saveCustomerSessionEventSubscriber = new SaveCustomerSessionEventSubscriber(
            $this->tester->createCustomerClientMock(),
            $customerSessionSaverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->tester->createAuthenticationTokenMock(true),
        );

        // Assert
        $customerSessionSaverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveCustomerSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenCustomerDoesNotHaveId(): void
    {
        // Arrange
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $saveCustomerSessionEventSubscriber = new SaveCustomerSessionEventSubscriber(
            $this->tester->createCustomerClientMock(new CustomerTransfer()),
            $customerSessionSaverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->tester->createAuthenticationTokenMock(true),
        );

        // Assert
        $customerSessionSaverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveCustomerSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldSaveSessionCustomerDataWhenCustomerHasId(): void
    {
        // Arrange
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $saveCustomerSessionEventSubscriber = new SaveCustomerSessionEventSubscriber(
            $this->tester->createCustomerClientMock((new CustomerTransfer())->setIdCustomer(1)),
            $customerSessionSaverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->tester->createAuthenticationTokenMock(true),
        );

        // Assert
        $customerSessionSaverPluginMock->expects($this->once())->method('saveSession');

        // Act
        $saveCustomerSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @param bool $withSession
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestMock(bool $withSession = false): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->method('hasSession')
            ->willReturn($withSession);

        if (!$withSession) {
            return $requestMock;
        }

        $sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->getMock();

        $sessionMock->method('getId')
            ->willReturn('1');

        $requestMock->method('getSession')
            ->willReturn($sessionMock);

        return $requestMock;
    }
}
