<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use SprykerShop\Yves\CompanyPage\CompanyPageFactory;
use SprykerShop\Yves\CompanyPage\Controller\CompanyRolePermissionController;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientBridge;
use SprykerShop\Yves\CompanyPage\Form\FormFactory;
use SprykerShopTest\Yves\CompanyPage\CompanyPageTester;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CompanyPage
 * @group Controller
 * @group CompanyRolePermissionControllerTest
 */
class CompanyRolePermissionControllerTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\CompanyPage\CompanyPageTester
     */
    protected CompanyPageTester $tester;

    /**
     * @return void
     */
    public function testConfigureActionThrowsExceptionOnOpenIfCustomerNotRelatedToCompany(): void
    {
        // Assert
        $this->expectException(NotFoundHttpException::class);

        // Arrange
        $companyRolePemissionController = $this->createCompanyRolePemissionControllerMockWithMockedForm();

        $companyRolePemissionController
            ->method('isCurrentCustomerRelatedToCompany')
            ->willReturn(false);

        // Act
        $companyRolePemissionController->configureAction(new Request());
    }

    /**
     * @return void
     */
    public function testConfigureActionDoesNotThrowExceptionOnOpenIfCustomerRelatedToCompany(): void
    {
        // Arrange
        $companyRolePemissionController = $this->createCompanyRolePemissionControllerMockWithMockedForm();

        $companyRolePemissionController
            ->method('isCurrentCustomerRelatedToCompany')
            ->willReturn(true);

        // Assert
        $companyRolePemissionController->expects($this->once())
            ->method('generateMessagesByCompanyRolePermissionResponse');

        // Act
        $companyRolePemissionController->configureAction(new Request());
    }

    /**
     * @return void
     */
    public function testConfigureActionThrowsExceptionOnFormSubmissionIfCustomerNotRelatedToCompany(): void
    {
        // Assert
        $this->expectException(NotFoundHttpException::class);

        // Arrange
        $companyRolePemissionController = $this->createCompanyRolePemissionControllerMockWithMockedForm();

        // Assert
        $companyRolePemissionController->expects($this->atLeast(2))
            ->method('isCurrentCustomerRelatedToCompany')
            ->willReturnOnConsecutiveCalls(true, false);

        // Act
        $companyRolePemissionController->configureAction(new Request());
    }

    /**
     * @return void
     */
    public function testConfigureActionDoesNotThrowExceptionOnFormSubmissionIfCustomerRelatedToCompany(): void
    {
        // Arrange
        $companyRolePemissionController = $this->createCompanyRolePemissionControllerMockWithMockedForm();

        // Assert
        $companyRolePemissionController->expects($this->atLeast(2))
            ->method('isCurrentCustomerRelatedToCompany')
            ->willReturnOnConsecutiveCalls(true, true);

        // Act
        $companyRolePemissionController->configureAction(new Request());
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Controller\CompanyRolePermissionController
     */
    protected function createCompanyRolePemissionControllerMockWithMockedForm(): CompanyRolePermissionController
    {
        $companyRolePemissionControllerMock = $this->createPartialMock(CompanyRolePermissionController::class, ['isCurrentCustomerRelatedToCompany', 'getFactory', 'generateMessagesByCompanyRolePermissionResponse']);

        $factoryMock = $this->createPartialMock(CompanyPageFactory::class, ['getCompanyRoleClient', 'createCompanyPageFormFactory']);

        $clientMock = $this->getMockBuilder(CompanyPageToCompanyRoleClientBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCompanyRoleById', 'updateCompanyRolePermission'])
            ->getMock();

        $companyRoleTransfer = new CompanyRoleTransfer();

        $companyRoleTransfer
            ->setFkCompany(0)
            ->setIdCompanyRole(0);

        $clientMock
            ->method('getCompanyRoleById')
            ->willReturn($companyRoleTransfer);

        $factoryMock
            ->method('getCompanyRoleClient')
            ->willReturn($clientMock);

        $formFactoryMock = $this->getMockBuilder(FormFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCompanyRolePermissionType'])
            ->getMock();

        $formMock = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isSubmitted', 'isValid', 'getData', 'handleRequest', 'createView'])
            ->getMock();

        $formMock->method('isSubmitted')
            ->willReturn(true);

        $formMock->method('isValid')
            ->willReturn(true);

        $formMock->method('getData')
            ->willReturn((new PermissionTransfer())->setIdCompanyRole(0));

        $formMock->method('handleRequest')
            ->willReturn($formMock);

        $formFactoryMock->method('getCompanyRolePermissionType')
            ->willReturn($formMock);

        $factoryMock->method('createCompanyPageFormFactory')
            ->willReturn($formFactoryMock);

        $companyRolePemissionControllerMock
            ->method('getFactory')
            ->willReturn($factoryMock);

        return $companyRolePemissionControllerMock;
    }
}
