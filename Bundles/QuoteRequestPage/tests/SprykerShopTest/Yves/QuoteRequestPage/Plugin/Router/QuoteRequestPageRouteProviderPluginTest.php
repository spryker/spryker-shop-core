<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\QuoteRequestPage\Plugin\Router;

use Codeception\Test\Unit;
use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group QuoteRequestPage
 * @group Router
 * @group QuoteRequestPageRouteProviderPlugin
 * Add your own group annotations below this line
 */
class QuoteRequestPageRouteProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

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
        $routeCollection = (new QuoteRequestPageRouteProviderPlugin())->addRoutes($routeCollection);
        $route = $routeCollection->get($routeName);
        $requirements = $route->getRequirements();

        // Act
        $regex = sprintf(
            '%s%s%s',
            static::START_PATTERN,
            $requirements[static::QUOTE_REQUEST_REFERENCE],
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
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS, 'quoteRequest123', true],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT, 'quoteRequest_456', true],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART, 'quoteRequest--789', true],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CANCEL, 'SMOKE_STORE_AT--4', true],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_REVISE, 'quoteRequestRevise123', true],

            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS, 'quote@Request!', false],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT, 'quoteRequest#456', false],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART, 'quote Request', false],
            [QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CANCEL, '', false],
        ];
    }
}
