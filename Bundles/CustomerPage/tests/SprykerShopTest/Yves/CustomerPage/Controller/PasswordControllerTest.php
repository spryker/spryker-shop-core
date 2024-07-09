<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Psr\Log\LoggerInterface;
use SprykerShop\Yves\CustomerPage\Controller\PasswordController;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientBridge;
use SprykerShop\Yves\CustomerPage\Form\FormFactory;
use SprykerShop\Yves\CustomerPage\Logger\AuditLogger;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Controller
 * @group PasswordControllerTest
 */
class PasswordControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testForgottenPasswordActionAddsPasswordResetRequestedAuditLog(): void
    {
        // Arrange
        $passwordResetController = $this->getPasswordControllerMock('Password Reset Requested');

        // Act
        $passwordResetController->forgottenPasswordAction(new Request());
    }

    /**
     * @return void
     */
    public function testRestorePasswordActionAddsPasswordUpdatedAfterResetAuditLog(): void
    {
        // Arrange
        $passwordResetController = $this->getPasswordControllerMock('Password Updated after Reset');

        // Act
        $passwordResetController->restorePasswordAction(new Request());
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\Controller\PasswordController
     */
    protected function getPasswordControllerMock(string $expectedAuditLogMessage): PasswordController
    {
        $passwordControllerMock = $this->getMockBuilder(PasswordController::class)
            ->onlyMethods([
                'getFactory',
                'isPasswordResetBlocked',
                'incrementPasswordResetBlocker',
                'sendPasswordRestoreMail',
                'addSuccessMessage',
                'redirectResponseInternal',
            ])
            ->getMock();
        $passwordControllerMock->method('getFactory')
            ->willReturn($this->getCustomerPageFactoryMock($expectedAuditLogMessage));
        $passwordControllerMock->method('isPasswordResetBlocked')->willReturn(false);
        $passwordControllerMock->method('sendPasswordRestoreMail')->willReturn(new CustomerResponseTransfer());
        $passwordControllerMock->method('redirectResponseInternal')->willReturn(new RedirectResponse('/'));

        return $passwordControllerMock;
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\CustomerPageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCustomerPageFactoryMock(string $expectedAuditLogMessage): CustomerPageFactory
    {
        $customerPageFactoryMock = $this->createMock(CustomerPageFactory::class);
        $customerPageFactoryMock->method('createAuditLogger')->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $customerPageFactoryMock->method('createCustomerFormFactory')->willReturn($this->getFormFactoryMock());
        $customerPageFactoryMock->method('getCustomerClient')->willReturn($this->getCustomerClientMock());

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

    /**
     * @return \Symfony\Component\Form\FormInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFormMock(): FormInterface
    {
        $form = $this->getMockBuilder(FormInterface::class)->getMock();
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('handleRequest')->willReturn($form);
        $form->method('setData')->willReturn($form);
        $form->method('getData')->willReturn([]);

        return $form;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\FormFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFormFactoryMock(): FormFactory
    {
        $formFactoryMock = $this->createMock(FormFactory::class);
        $formFactoryMock->method('getForgottenPasswordForm')->willReturn($this->getFormMock());
        $formFactoryMock->method('getFormRestorePassword')->willReturn($this->getFormMock());

        return $formFactoryMock;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCustomerClientMock(): CustomerPageToCustomerClientBridge
    {
        $customerClientMock = $this->createMock(CustomerPageToCustomerClientBridge::class);
        $customerClientMock->method('restorePassword')
            ->willReturn((new CustomerResponseTransfer())->setIsSuccess(true));

        return $customerClientMock;
    }
}
