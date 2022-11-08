<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\Plugin\CustomerPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToSessionClientInterface;
use SprykerShop\Yves\SessionAgentValidation\Plugin\CustomerPage\UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationDependencyProvider;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group Plugin
 * @group CustomerPage
 * @group UpdateAgentSessionAfterCustomerAuthenticationSuccessPluginTest
 * Add your own group annotations below this line
 */
class UpdateAgentSessionAfterCustomerAuthenticationSuccessPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testExecuteShouldNotUpdateSessionAgentDataWhenAuthorizationIsNotGranted(): void
    {
        // Arrange
        $sessionAgentSaverPluginMock = $this->tester->createSessionAgentSaverPluginMock();
        $this->addDependencies(
            $this->createAuthorizationCheckerMock(),
            $this->tester->createAgentClientMock(),
            $sessionAgentSaverPluginMock,
        );

        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin = new UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin();

        // Assert
        $sessionAgentSaverPluginMock->expects($this->never())
            ->method('saveSession');

        // Act
        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin->execute();
    }

    /**
     * @return void
     */
    public function testExecuteShouldNotUpdateSessionAgentDataWhenAgentIsNotLoggedIn(): void
    {
        // Arrange
        $sessionAgentSaverPluginMock = $this->tester->createSessionAgentSaverPluginMock();

        $this->addDependencies(
            $this->createAuthorizationCheckerMock(true),
            $this->tester->createAgentClientMock(),
            $sessionAgentSaverPluginMock,
        );

        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin = new UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin();

        // Assert
        $sessionAgentSaverPluginMock->expects($this->never())
            ->method('saveSession');

        // Act
        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin->execute();
    }

    /**
     * @return void
     */
    public function testExecuteShouldUpdateSessionAgentDataWhenAuthorizationIsGrantedAndAgentIsLoggedIn(): void
    {
        // Arrange
        $sessionAgentSaverPluginMock = $this->tester->createSessionAgentSaverPluginMock();

        $this->addDependencies(
            $this->createAuthorizationCheckerMock(true),
            $this->tester->createAgentClientMock((new UserTransfer())->setIdUser(1)),
            $sessionAgentSaverPluginMock,
        );

        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin = new UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin();

        // Assert
        $sessionAgentSaverPluginMock->expects($this->once())
            ->method('saveSession');

        // Act
        $updateAgentSessionAfterCustomerAuthenticationSuccessPlugin->execute();
    }

    /**
     * @param bool $isGranted
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected function createAuthorizationCheckerMock(bool $isGranted = false): AuthorizationCheckerInterface
    {
        $authorizationCheckerMock = $this->getMockBuilder(AuthorizationCheckerInterface::class)
            ->getMock();

        $authorizationCheckerMock->method('isGranted')
            ->willReturn($isGranted);

        return $authorizationCheckerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToSessionClientInterface
     */
    protected function createSessionClientMock(): SessionAgentValidationToSessionClientInterface
    {
        $sessionClientMock = $this->getMockBuilder(SessionAgentValidationToSessionClientInterface::class)
            ->getMock();

        $sessionClientMock->method('getId')
            ->willReturn('1');

        return $sessionClientMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface $agentClient
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface $sessionAgentSaverPluginMock
     *
     * @return void
     */
    protected function addDependencies(
        AuthorizationCheckerInterface $authorizationChecker,
        SessionAgentValidationToAgentClientInterface $agentClient,
        SessionAgentSaverPluginInterface $sessionAgentSaverPluginMock
    ): void {
        $this->tester->setDependency(SessionAgentValidationDependencyProvider::CLIENT_SESSION, $this->createSessionClientMock());
        $this->tester->setDependency(SessionAgentValidationDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER, $authorizationChecker);
        $this->tester->setDependency(SessionAgentValidationDependencyProvider::CLIENT_AGENT, $agentClient);
        $this->tester->setDependency(SessionAgentValidationDependencyProvider::PLUGIN_SESSION_AGENT_SAVER, $sessionAgentSaverPluginMock);
    }
}
