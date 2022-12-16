<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage\FirewallListener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListener;
use SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionCustomerValidationPage
 * @group FirewallListener
 * @group ValidateCustomerSessionListenerTest
 * Add your own group annotations below this line
 */
class ValidateCustomerSessionListenerTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester
     */
    protected SessionCustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenTokenStorageIsNotSet(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock(),
            $this->tester->createCustomerSessionValidatorPluginMock(),
            $this->tester->createConfigMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenTokenIsNotSet(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock(),
            $this->tester->createCustomerSessionValidatorPluginMock(),
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenUserIsNotSet(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock(),
            $this->tester->createCustomerSessionValidatorPluginMock(),
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(
                $this->tester->createAuthenticationTokenMock(),
            ),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenEventDoesNotHaveSession(): void
    {
        // Arrange
        $customerSessionValidatorPluginMock = $this->tester->createCustomerSessionValidatorPluginMock();
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock(),
            $customerSessionValidatorPluginMock,
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(
                $this->tester->createAuthenticationTokenMock(true),
            ),
        );

        $eventMock = $this->createEventMock(
            $this->createRequestMock(),
        );

        // Assert
        $customerSessionValidatorPluginMock->expects($this->never())->method('validate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenCustomerIsNotFound(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock(),
            $this->tester->createCustomerSessionValidatorPluginMock(),
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(
                $this->tester->createAuthenticationTokenMock(true),
            ),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenSessionCustomerIsValid(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock((new CustomerTransfer())->setIdCustomer(1)),
            $this->tester->createCustomerSessionValidatorPluginMock(true),
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(
                $this->tester->createAuthenticationTokenMock(true),
            ),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldInvalidateSessionWhenSessionCustomerIsInvalid(): void
    {
        // Arrange
        $validateCustomerSessionListener = new ValidateCustomerSessionListener(
            $this->tester->createCustomerClientMock((new CustomerTransfer())->setIdCustomer(1)),
            $this->tester->createCustomerSessionValidatorPluginMock(),
            $this->tester->createConfigMock(),
            $this->createTokenStorageMock(
                $this->tester->createAuthenticationTokenMock(true),
            ),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->once())->method('invalidate');

        // Act
        $validateCustomerSessionListener->authenticate($eventMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function createSessionMock(): SessionInterface
    {
        $sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->getMock();

        $sessionMock->method('getId')
            ->willReturn(1);

        return $sessionMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Session\SessionInterface|null $session
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestMock(?SessionInterface $session = null): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->method('getSession')
            ->willReturn($session);

        $requestMock->method('hasSession')
            ->willReturn($session !== null);

        return $requestMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request $request
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function createEventMock(Request $request): RequestEvent
    {
        $requestEventMock = $this->getMockBuilder(RequestEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestEventMock->method('getRequest')
            ->willReturn($request);

        return $requestEventMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface|null $token
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected function createTokenStorageMock(?TokenInterface $token = null): TokenStorageInterface
    {
        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMock();

        $tokenStorageMock->method('getToken')
            ->willReturn($token);

        return $tokenStorageMock;
    }
}
