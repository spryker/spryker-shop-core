<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Handler;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory;
use SprykerShop\Yves\CustomerPage\Logger\AuditLogger;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractHandlerTest extends Unit
{
    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\CustomerPageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCustomerPageFactoryMock(string $expectedAuditLogMessage): CustomerPageFactory
    {
        $customerPageFactoryMock = $this->createMock(CustomerPageFactory::class);
        $customerPageFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $customerPageFactoryMock->method('createRedirectResponse')->willReturn(new RedirectResponse('/'));

        return $customerPageFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuditLoggerMock(string $expectedMessage): AuditLogger
    {
        $auditLoggerMock = $this->getMockBuilder(AuditLogger::class)
            ->onlyMethods(['getAuditLogger'])
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
}
