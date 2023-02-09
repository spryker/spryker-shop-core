<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerValidationPage\Plugin;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use SprykerShop\Yves\CustomerValidationPage\CustomerValidationPageDependencyProvider;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface;
use SprykerShopTest\Yves\CustomerValidationPage\CustomerValidationPageYvesTester;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerValidationPage
 * @group CustomerValidationPageYvesTest
 * Add your own group annotations below this line
 */
class LogoutInvalidatedCustomerFilterControllerEventHandlerPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ROUTE_LOGOUT = '/logout';

    /**
     * @var \SprykerShopTest\Yves\CustomerValidationPage\CustomerValidationPageYvesTester
     */
    protected CustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    public function testHandleWithoutInvalidatedCustomerData(): void
    {
        // Arrange
        $this->setCustomerClientMock($this->tester->getCustomerTransfer());
        $this->setCustomerStorageClientMock(new InvalidatedCustomerCollectionTransfer());
        $this->setRouterServiceMock();

        $mockController = $this->tester->createMockController();
        $controllerEvent = $this->tester->createControllerEvent($mockController);

        // Act
        $this->tester->createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin()->handle($controllerEvent);

        // Assert
        $this->assertSame($mockController, $controllerEvent->getController());
    }

    /**
     * @return void
     */
    public function testHandleWithoutError(): void
    {
        // Arrange
        $this->setCustomerClientMock($this->tester->getCustomerTransfer());
        $this->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, null),
        );
        $this->setRouterServiceMock();

        $mockController = $this->tester->createMockController();
        $controllerEvent = $this->tester->createControllerEvent($mockController);

        // Act
        $this->tester->createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin()->handle($controllerEvent);

        // Assert
        $this->assertSame($mockController, $controllerEvent->getController());
    }

    /**
     * @return void
     */
    public function testHandleWithAnonymizedAt(): void
    {
        // Arrange
        $this->setCustomerClientMock($this->tester->getCustomerTransfer());
        $this->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(new DateTime(), null),
        );
        $this->setRouterServiceMock();

        $mockController = $this->tester->createMockController();
        $controllerEvent = $this->tester->createControllerEvent($mockController);

        // Act
        $this->tester->createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin()->handle($controllerEvent);

        // Assert
        $this->assertNotSame($mockController, $controllerEvent->getController());
    }

    /**
     * @return void
     */
    public function testHandleWithPasswordUpdatedAtBeforeLogin(): void
    {
        // Arrange
        $this->setCustomerClientMock($this->tester->getCustomerTransfer());
        $this->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, new DateTime('-2 minutes')),
        );
        $this->setRouterServiceMock();
        $this->setSessionClientMock(
            $this->createMetadataBagMock(new DateTime('-1 minutes')),
        );

        $mockController = $this->tester->createMockController();
        $controllerEvent = $this->tester->createControllerEvent($mockController);

        // Act
        $this->tester->createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin()->handle($controllerEvent);

        // Assert
        $this->assertSame($mockController, $controllerEvent->getController());
    }

    /**
     * @return void
     */
    public function testHandleWithPasswordUpdatedAtAfterLogin(): void
    {
        // Arrange
        $this->setCustomerClientMock($this->tester->getCustomerTransfer());
        $this->setCustomerStorageClientMock(
            $this->tester->getInvalidatedCustomerCollectionTransfer(null, new DateTime()),
        );
        $this->setRouterServiceMock();
        $this->setSessionClientMock(
            $this->createMetadataBagMock(new DateTime('-1 minutes')),
        );

        $mockController = $this->tester->createMockController();
        $controllerEvent = $this->tester->createControllerEvent($mockController);

        // Act
        $this->tester->createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin()->handle($controllerEvent);

        // Assert
        $this->assertNotSame($mockController, $controllerEvent->getController());
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    protected function createMetadataBagMock(DateTime $dateTime): MetadataBag
    {
        $metadataBagMock = $this->getMockBuilder(MetadataBag::class)->getMock();
        $metadataBagMock->expects($this->once())
            ->method('getCreated')
            ->willReturn($dateTime->getTimestamp());

        return $metadataBagMock;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag $metadataBagMock
     *
     * @return void
     */
    protected function setSessionClientMock(MetadataBag $metadataBagMock): void
    {
        $sessionClientMock = $this->getMockBuilder(CustomerValidationPageToSessionClientInterface::class)->getMock();
        $sessionClientMock->expects($this->once())
            ->method('getMetadataBag')
            ->willReturn($metadataBagMock);

        $this->tester->setDependency(CustomerValidationPageDependencyProvider::CLIENT_SESSION, $sessionClientMock);
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
     *
     * @return void
     */
    protected function setCustomerStorageClientMock(
        InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
    ): void {
        $customerStorageClientMock = $this->getMockBuilder(CustomerValidationPageToCustomerStorageClientInterface::class)->getMock();
        $customerStorageClientMock->expects($this->once())
            ->method('getInvalidatedCustomerCollection')
            ->willReturn($invalidatedCustomerCollectionTransfer);

        $this->tester->setDependency(CustomerValidationPageDependencyProvider::CLIENT_CUSTOMER_STORAGE, $customerStorageClientMock);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function setCustomerClientMock(CustomerTransfer $customerTransfer): void
    {
        $customerClientMock = $this->getMockBuilder(CustomerValidationPageToCustomerClientInterface::class)->getMock();
        $customerClientMock->expects($this->once())
            ->method('getCustomer')
            ->willReturn($customerTransfer);

        $this->tester->setDependency(CustomerValidationPageDependencyProvider::CLIENT_CUSTOMER, $customerClientMock);
    }

    /**
     * @return void
     */
    protected function setRouterServiceMock(): void
    {
        $routerServiceMock = $this->getMockBuilder(ChainRouterInterface::class)->getMock();
        $routerServiceMock->expects($this->once())
            ->method('generate')
            ->willReturn(static::ROUTE_LOGOUT);

        $this->tester->setDependency(CustomerValidationPageDependencyProvider::SERVICE_ROUTER, $routerServiceMock);
    }
}
