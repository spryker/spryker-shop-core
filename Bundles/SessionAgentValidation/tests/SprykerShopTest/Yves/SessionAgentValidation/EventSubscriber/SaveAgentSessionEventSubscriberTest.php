<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\SessionAgentValidation\EventSubscriber\SaveAgentSessionEventSubscriber;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group EventSubscriber
 * @group SaveAgentSessionEventSubscriberTest
 * Add your own group annotations below this line
 */
class SaveAgentSessionEventSubscriberTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\EventSubscriber\SaveAgentSessionEventSubscriber::ROLE_AGENT
     *
     * @var string
     */
    protected const ROLE_AGENT = 'ROLE_AGENT';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED
     *
     * @var string
     */
    protected const COL_STATUS_DELETED = 'deleted';

    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnEvents(): void
    {
        // Act
        $subscribedEvents = SaveAgentSessionEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertNotEmpty($subscribedEvents);

        /** @deprecated Exists for Symfony 5 support only. */
        if (!class_exists(LoginSuccessEvent::class)) {
            $this->assertArrayHasKey(SecurityEvents::INTERACTIVE_LOGIN, $subscribedEvents);

            return;
        }

        $this->assertArrayHasKey(LoginSuccessEvent::class, $subscribedEvents);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenRequestDoesNotHaveSession(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock(),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(),
            $this->createAuthenticationTokenMock(),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenUserIsNotSet(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock(),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenUserDoesNotHaveAgentRole(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock(),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(
                $this->createUserMock(),
            ),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenAgentIsNotFound(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock(),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(
                $this->createUserMock([static::ROLE_AGENT]),
            ),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenAgentDoesNotHaveId(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock(new UserTransfer()),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(
                $this->createUserMock([static::ROLE_AGENT]),
            ),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldSaveSessionAgentDataWhenAgentHasId(): void
    {
        // Arrange
        $saverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(
                $this->createUserMock([static::ROLE_AGENT]),
            ),
        );

        // Assert
        $saverPluginMock->expects($this->once())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionAgentDataWhenAgentHasInactiveStatus(): void
    {
        // Arrange
        $saverPluginMock = $this->getMockBuilder(SessionAgentSaverPluginInterface::class)->getMock();
        $saveAgentSessionEventSubscriber = new SaveAgentSessionEventSubscriber(
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)->setStatus(static::COL_STATUS_DELETED)),
            $saverPluginMock,
            $this->tester->createConfigMock(),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(
                $this->createUserMock([static::ROLE_AGENT]),
            ),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $saveAgentSessionEventSubscriber->onInteractiveLogin($event);
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

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\User\UserInterface|null $user
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function createAuthenticationTokenMock(?UserInterface $user = null): TokenInterface
    {
        $authenticationTokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMock();

        $authenticationTokenMock->method('getUser')
            ->willReturn($user);

        return $authenticationTokenMock;
    }

    /**
     * @param list<string>|array $roles
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createUserMock(array $roles = []): UserInterface
    {
        $userMock = $this->getMockBuilder(UserInterface::class)
            ->getMock();

        $userMock->method('getRoles')
            ->willReturn($roles);

        return $userMock;
    }
}
