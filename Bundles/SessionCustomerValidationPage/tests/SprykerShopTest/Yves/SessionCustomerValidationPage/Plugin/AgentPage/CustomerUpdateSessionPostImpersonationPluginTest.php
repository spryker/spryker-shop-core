<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage\Plugin\AgentPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\Plugin\AgentPage\CustomerUpdateSessionPostImpersonationPlugin;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageDependencyProvider;
use SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionCustomerValidationPage
 * @group Plugin
 * @group AgentPage
 * @group CustomerUpdateSessionPostImpersonationPluginTest
 * Add your own group annotations below this line
 */
class CustomerUpdateSessionPostImpersonationPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester
     */
    protected SessionCustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    public function testExecuteShouldNotUpdateSessionWhenCustomerIsNotValid(): void
    {
        // Arrange
        $customerUpdateSessionPostImpersonationPlugin = new CustomerUpdateSessionPostImpersonationPlugin();
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();

        $this->tester->setDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_SAVER, $customerSessionSaverPluginMock);
        $this->tester->setDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_SESSION, $this->createSessionClientMock());

        // Assert
        $customerSessionSaverPluginMock->expects($this->never())->method('saveSession');

        // Act
        $customerUpdateSessionPostImpersonationPlugin->execute(new CustomerTransfer());
    }

    /**
     * @return void
     */
    public function testExecuteShouldUpdateSessionCustomerWhenCustomerIsValid(): void
    {
        // Arrange
        $customerUpdateSessionPostImpersonationPlugin = new CustomerUpdateSessionPostImpersonationPlugin();
        $customerSessionSaverPluginMock = $this->tester->createCustomerSessionSaverPluginMock();
        $customerTransfer = (new CustomerTransfer())->setIdCustomer(1);

        $this->tester->setDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_SAVER, $customerSessionSaverPluginMock);
        $this->tester->setDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_SESSION, $this->createSessionClientMock());

        // Assert
        $customerSessionSaverPluginMock->expects($this->once())->method('saveSession');

        // Act
        $customerUpdateSessionPostImpersonationPlugin->execute($customerTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface
     */
    protected function createSessionClientMock(): SessionCustomerValidationPageToSessionClientInterface
    {
        $sessionClientMock = $this->getMockBuilder(SessionCustomerValidationPageToSessionClientInterface::class)
            ->getMock();

        $sessionClientMock->method('getId')
            ->willReturn('1');

        return $sessionClientMock;
    }
}
