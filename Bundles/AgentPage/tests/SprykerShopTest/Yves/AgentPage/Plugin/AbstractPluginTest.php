<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use SprykerShop\Yves\AgentPage\AgentPageFactory;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientBridge;
use SprykerShop\Yves\AgentPage\Logger\AuditLogger;

class AbstractPluginTest extends Unit
{
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
        return $this->createMock(AgentPageToAgentClientBridge::class);
    }
}
