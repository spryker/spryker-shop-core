<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SalesReturnPage\Plugin\Router;

use Codeception\Test\Unit;
use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\SalesReturnPage\Plugin\Router\SalesReturnPageRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SalesReturnPage
 * @group Router
 * @group SalesReturnPageRouteProviderPluginTest
 * Add your own group annotations below this line
 */
class SalesReturnPageRouteProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const RETURN_REFERENCE = 'returnReference';

    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'orderReference';

    /**
     * @var string
     */
    protected const START_PATTERN = '/^';

    /**
     * @var string
     */
    protected const END_PATTERN = '$/';

    /**
     * @dataProvider referenceProvider
     *
     * @param string $routeName
     * @param string $reference
     * @param bool $isValid
     *
     * @return void
     */
    public function testReferenceMatchesRegex(string $routeName, string $reference, bool $isValid): void
    {
        // Arrange
        $routeCollection = new RouteCollection();
        $routeCollection = (new SalesReturnPageRouteProviderPlugin())->addRoutes($routeCollection);
        $route = $routeCollection->get($routeName);
        $requirements = $route->getRequirements();

        // Act
        $pattern = $requirements[static::RETURN_REFERENCE] ?? $requirements[static::ORDER_REFERENCE];
        $regex = sprintf(
            '%s%s%s',
            static::START_PATTERN,
            $pattern,
            static::END_PATTERN,
        );

        // Assert
        if ($isValid) {
            $this->assertRegExp($regex, $reference);
        }
        if (!$isValid) {
            $this->assertNotRegExp($regex, $reference);
        }
    }

    /**
     * @return array
     */
    public function referenceProvider(): array
    {
        return [
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_CREATE, 'orderReference123', true],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_VIEW, 'returnReference-456', true],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_SLIP_PRINT, 'return_789', true],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_CREATE, 'reference_123--abc', true],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_VIEW, 'return--view123', true],

            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_CREATE, 'order@Reference!', false],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_VIEW, 'returnReference#456', false],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_SLIP_PRINT, 'return 789', false],
            [SalesReturnPageRouteProviderPlugin::ROUTE_NAME_RETURN_VIEW, '', false],
        ];
    }
}
