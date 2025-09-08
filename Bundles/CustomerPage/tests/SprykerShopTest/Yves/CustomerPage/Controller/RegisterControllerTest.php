<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Controller;

use Codeception\Test\Unit;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Controller\RegisterController;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Controller
 * @group RegisterControllerTest
 */
class RegisterControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteConfirmActionWithLocaleRedirectsWithCorrectRoute(): void
    {
        // Arrange
        $token = 'test-registration-token';
        $locale = 'de_DE';

        $request = new Request([
            'token' => $token,
            CustomerPageConfig::URL_PARAM_LOCALE => $locale,
        ]);

        $registerControllerMock = $this->getMockBuilder(RegisterController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getFactory', 'redirectResponseInternal', 'redirectWithLocale'])
            ->getMock();

        // Assert
        $registerControllerMock->expects($this->once())
            ->method('redirectWithLocale')
            ->with(
                CustomerPageRouteProviderPlugin::ROUTE_NAME_CONFIRM_REGISTRATION,
                $locale,
                ['token' => $token],
            )
            ->willReturn(new RedirectResponse('/'));

        // Act
        $registerControllerMock->confirmAction($request);
    }
}
