<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Plugin
 * @group Handler
 * @group CustomerAuthenticationSuccessHandlerTest
 */
class CustomerAuthenticationSuccessHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationSuccessAddsSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $customerAuthenticationSuccessHandler = $this->getCustomerAuthenticationSuccessHandler('Successful Login');
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        // Act
        $customerAuthenticationSuccessHandler->onAuthenticationSuccess($request, $this->getPostAuthenticationTokenMock());
    }

    /**
     * @param string $expectedMessage
     *
     * @return \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler
     */
    protected function getCustomerAuthenticationSuccessHandler(
        string $expectedMessage
    ): CustomerAuthenticationSuccessHandler {
        $customerPageFactoryMock = $this->getCustomerPageFactoryMock($expectedMessage);
        $customerAuthenticationSuccessHandler = new CustomerAuthenticationSuccessHandler();
        $customerAuthenticationSuccessHandler->setFactory($customerPageFactoryMock);

        return $customerAuthenticationSuccessHandler;
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPostAuthenticationTokenMock(): PostAuthenticationToken
    {
        $postAuthenticationTokenMock = $this->getMockBuilder(PostAuthenticationToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postAuthenticationTokenMock->method('getUser')->willReturn(new Customer(
            new CustomerTransfer(),
            'test',
            'test',
        ));

        return $postAuthenticationTokenMock;
    }
}
