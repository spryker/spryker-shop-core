<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use SprykerShop\Yves\CompanyPage\CompanyPageFactory;
use SprykerShop\Yves\CompanyPage\Controller\CompanyRoleUserController;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientBridge;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CompanyPage
 * @group Controller
 * @group CompanyRoleUserControllerTest
 */
class CompanyRoleUserControllerTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\CompanyPage\CompanyPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAssignThrowsExceptionOnInvalidCsrfToken(): void
    {
        // Assert
        $this->expectException(AccessDeniedHttpException::class);

        // Arrange
        $companyRoleUserController = $this->createPartialMock(CompanyRoleUserController::class, ['isCsrfTokenValid']);
        $companyRoleUserController
            ->method('isCsrfTokenValid')
            ->willReturn(false);

        // Act
        $companyRoleUserController->assignAction(new Request());
    }

    /**
     * @return void
     */
    public function testUnassignThrowsExceptionOnInvalidCsrfToken(): void
    {
        // Assert
        $this->expectException(AccessDeniedHttpException::class);

        // Arrange
        $companyRoleUserController = $this->createPartialMock(CompanyRoleUserController::class, ['isCsrfTokenValid']);
        $companyRoleUserController
            ->method('isCsrfTokenValid')
            ->willReturn(false);

        // Act
        $companyRoleUserController->unassignAction(new Request());
    }

    /**
     * @return void
     */
    public function testAssignDoesNotThrowExceptionOnValidCsrfToken(): void
    {
        // Arrange
        $companyRoleUserControllerMock = $this->createCompanyRoleUserControllerMockWithMockedCsrfTokenChecks();

        // Assert
        $companyRoleUserControllerMock->expects($this->once())
            ->method('redirectResponseInternal');

        // Act
        $companyRoleUserControllerMock->assignAction(new Request());
    }

    /**
     * @return void
     */
    public function testUnassignDoesNotThrowExceptionOnValidCsrfToken(): void
    {
        // Arrange
        $companyRoleUserControllerMock = $this->createCompanyRoleUserControllerMockWithMockedCsrfTokenChecks();

        // Assert
        $companyRoleUserControllerMock->expects($this->once())
            ->method('redirectResponseInternal');

        // Act
        $companyRoleUserControllerMock->unassignAction(new Request());
    }

    /**
     * @return void
     */
    public function testAssignThrowsExceptionIfUserIsNotRelated(): void
    {
        // Arrange
        $companyRoleUserControllerMock = $this->createCompanyRoleUserControllerMockWithMockedCsrfTokenChecks(false);

        // Assert
        $this->expectException(NotFoundHttpException::class);

        // Act
        $companyRoleUserControllerMock->assignAction(new Request());
    }

    /**
     * @param bool $userIsRelated
     *
     * @return \SprykerShop\Yves\CompanyPage\Controller\CompanyRoleUserController
     */
    public function createCompanyRoleUserControllerMockWithMockedCsrfTokenChecks(bool $userIsRelated = true): CompanyRoleUserController
    {
        $companyRoleUserControllerMock = $this->createPartialMock(
            CompanyRoleUserController::class,
            ['isCsrfTokenValid', 'getFactory', 'isCurrentCustomerRelatedToCompany', 'saveCompanyUser', 'redirectResponseInternal'],
        );

        $factoryMock = $this->createPartialMock(CompanyPageFactory::class, ['getCompanyRoleClient']);

        $clientMock = $this->getMockBuilder(CompanyPageToCompanyRoleClientBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCompanyRoleById', 'getCompanyRoleCollection'])
            ->getMock();

        $clientMock
            ->method('getCompanyRoleById')
            ->willReturn((new CompanyRoleTransfer())->setFkCompany(0));

        $clientMock
            ->method('getCompanyRoleCollection')
            ->willReturn(new CompanyRoleCollectionTransfer());

        $factoryMock
            ->method('getCompanyRoleClient')
            ->willReturn($clientMock);

        $companyRoleUserControllerMock
            ->method('isCurrentCustomerRelatedToCompany')
            ->willReturn($userIsRelated);

        $companyRoleUserControllerMock
            ->method('saveCompanyUser');

        $companyRoleUserControllerMock
            ->method('getFactory')
            ->willReturn($factoryMock);

        $companyRoleUserControllerMock
            ->method('redirectResponseInternal')
            ->willReturn(new RedirectResponse('/'));

        $companyRoleUserControllerMock
            ->method('isCsrfTokenValid')
            ->willReturn(true);

        return $companyRoleUserControllerMock;
    }
}
