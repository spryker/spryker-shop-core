<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Psr\Log\LoggerInterface;
use SprykerShop\Yves\AgentPage\AgentPageFactory;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientBridge;
use SprykerShop\Yves\AgentPage\Logger\AuditLogger;
use SprykerShop\Yves\AgentPage\Security\Agent;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AbstractPluginTest extends Unit
{
    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken
     */
    protected function getSwitchUserTokenMock(): SwitchUserToken
    {
        $agentPageFactoryMock = $this->createMock(SwitchUserToken::class);
        $agentPageFactoryMock->method('getOriginalToken')->willReturn($this->getTokenInterfaceMock());

        return $agentPageFactoryMock;
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface|(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface&\PHPUnit\Framework\MockObject\MockObject)
     */
    protected function getTokenInterfaceMock()
    {
        $tokenMock = $this->createMock(TokenInterface::class);
        $tokenMock->method('getUser')->willReturn($this->getAgentMock());

        return $tokenMock;
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Security\Agent
     */
    protected function getAgentMock(): Agent
    {
        $agentMock = $this->createMock(Agent::class);
        $agentMock->method('getUsername')->willReturn('username');

        return $agentMock;
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\AgentPage\AgentPageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAgentPageFactoryMock(string $expectedAuditLogMessage): AgentPageFactory
    {
        $agentPageFactoryMock = $this->createMock(AgentPageFactory::class);
        $agentPageFactoryMock->method('createAuditLogger')->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $agentPageFactoryMock->method('getAgentClient')->willReturn($this->getAgentClientMock());

        return $agentPageFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \SprykerShop\Yves\AgentPage\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuditLoggerMock(string $expectedMessage): AuditLogger
    {
        $auditLoggerMock = $this->getMockBuilder(AuditLogger::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAuditLogger', 'addOriginalCustomerContext'])
            ->getMock();
        $auditLoggerMock->expects($this->once())
            ->method('getAuditLogger')
            ->willReturn($this->getLoggerMock($expectedMessage));

        return $auditLoggerMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getLoggerMock(string $expectedMessage): LoggerInterface
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('info')->with($expectedMessage);

        return $loggerMock;
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAgentClientMock(): AgentPageToAgentClientBridge
    {
        $agentClientMock = $this->createMock(AgentPageToAgentClientBridge::class);

        $agentClientMock->method('findAgentByUsername')->willReturn(
            (new UserTransfer())->setStatus('active'),
        );

        return $agentClientMock;
    }
}
