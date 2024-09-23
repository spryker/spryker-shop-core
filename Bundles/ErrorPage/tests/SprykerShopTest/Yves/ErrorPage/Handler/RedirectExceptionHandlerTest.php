<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ErrorPage\ExceptionHandler;

use Codeception\Test\Unit;
use Exception;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\ErrorPage\Handler\RedirectExceptionHandler;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class RedirectExceptionHandlerTest extends Unit
{
    /**
     * @return void
     */
    public function testHandleExceptionReturnsTemporaryRedirectStatusCode(): void
    {
        // Arrange
        $chainRouterMock = $this->createMock(ChainRouter::class);
        $chainRouterMock->method('generate')->willReturn('http://error-page/404?errorMessage=test-page');

        // Act
        $response = (new RedirectExceptionHandler($chainRouterMock))->handle(
            FlattenException::createFromThrowable(new Exception('test-page'))->setStatusCode(404),
        );

        // Assert
        $this->assertSame(307, $response->getStatusCode());
    }
}
