<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Handler;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Plugin
 * @group Handler
 * @group CustomerAuthenticationFailureHandlerTest
 */
class CustomerAuthenticationFailureHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationFailureAddsFailedLoginAuditLog(): void
    {
        // Arrange
        $customerAuthenticationFailureHandler = $this->getCustomerAuthenticationFailureHandler('Failed Login');

        // Act
        $customerAuthenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }

    /**
     * @param string $expectedMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler
     */
    protected function getCustomerAuthenticationFailureHandler(
        string $expectedMessage
    ): CustomerAuthenticationFailureHandler {
        $customerPageFactoryMock = $this->getCustomerPageFactoryMock($expectedMessage);
        $customerAuthenticationFailureHandler = new CustomerAuthenticationFailureHandler($this->getFlashMessengerMock());
        $customerAuthenticationFailureHandler->setFactory($customerPageFactoryMock);

        return $customerAuthenticationFailureHandler;
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFlashMessengerMock(): FlashMessengerInterface
    {
        return $this->createMock(FlashMessengerInterface::class);
    }
}
