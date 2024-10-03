<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\QuoteRequestAgentPage\Plugin\Router;

use Codeception\Test\Unit;
use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group QuoteRequestAgentPage
 * @group Router
 * @group QuoteRequestAgentPageRouteProviderPlugin
 * Add your own group annotations below this line
 */
class QuoteRequestAgentPageRouteProviderPluginTest extends Unit
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
        $routeCollection = (new QuoteRequestAgentPageRouteProviderPlugin())->addRoutes($routeCollection);
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
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS, 'quoteRequest123', true],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT, 'quoteRequest_456', true],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, 'quoteRequest--789', true],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CANCEL, 'quoteRequest--123--abc', true],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_REVISE, 'quoteRequestRevise123', true],

            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS, 'quote@Request!', false],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT, 'quoteRequest#456', false],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, 'quote Request', false],
            [QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CANCEL, '', false],
        ];
    }
}
