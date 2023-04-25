<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\FirewallListener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListener;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group FirewallListener
 * @group ValidateAgentSessionListenerTest
 * Add your own group annotations below this line
 */
class ValidateAgentSessionListenerTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenAgentIsNotLoggedIn(): void
    {
        // Arrange
        $validateAgentSessionListener = new ValidateAgentSessionListener(
            $this->tester->createAgentClientMock(new UserTransfer()),
            $this->tester->createSessionAgentValidatorPluginMock(),
            $this->tester->createConfigMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateAgentSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenAgentDoesNotHaveId(): void
    {
        // Arrange
        $validateAgentSessionListener = new ValidateAgentSessionListener(
            $this->tester->createAgentClientMock(new UserTransfer()),
            $this->tester->createSessionAgentValidatorPluginMock(),
            $this->tester->createConfigMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateAgentSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldNotInvalidateSessionWhenSessionAgentIsValid(): void
    {
        // Arrange
        $validateAgentSessionListener = new ValidateAgentSessionListener(
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)),
            $this->tester->createSessionAgentValidatorPluginMock(true),
            $this->tester->createConfigMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->never())->method('invalidate');

        // Act
        $validateAgentSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldInvalidateSessionWhenSessionAgentIsInvalid(): void
    {
        // Arrange
        $validateAgentSessionListener = new ValidateAgentSessionListener(
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)),
            $this->tester->createSessionAgentValidatorPluginMock(),
            $this->tester->createConfigMock(),
        );

        $sessionMock = $this->createSessionMock();
        $eventMock = $this->createEventMock(
            $this->createRequestMock($sessionMock),
        );

        // Assert
        $sessionMock->expects($this->once())->method('invalidate');

        // Act
        $validateAgentSessionListener->authenticate($eventMock);
    }

    /**
     * @return void
     */
    public function testAuthenticateShouldInvalidateTokenSessionWhenSessionAgentIsInvalid(): void
    {
        // Arrange
        $tokenStorageMock = $this->createTokenStorageMock();
        $validateAgentSessionListener = new ValidateAgentSessionListener(
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)),
            $this->tester->createSessionAgentValidatorPluginMock(),
            $this->tester->createConfigMock(),
            $tokenStorageMock,
        );

        $eventMock = $this->createEventMock(
            $this->createRequestMock($this->createSessionMock()),
        );

        // Assert
        $tokenStorageMock->expects($this->once())->method('setToken');

        // Act
        $validateAgentSessionListener->authenticate($eventMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function createSessionMock(): SessionInterface
    {
        $sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->getMock();

        $sessionMock->method('getId')
            ->willReturn('1');

        return $sessionMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestMock(SessionInterface $session): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->method('getSession')
            ->willReturn($session);

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected function createTokenStorageMock(): TokenStorageInterface
    {
        return $this->getMockBuilder(TokenStorageInterface::class)
            ->getMock();
    }
}
