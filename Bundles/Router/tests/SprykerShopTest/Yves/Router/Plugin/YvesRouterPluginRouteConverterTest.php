<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use SprykerShop\Shared\Router\RouterConstants;
use SprykerShop\Yves\Router\Plugin\Router\YvesRouterPlugin;
use SprykerShopTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router
 * @group Plugin
 * @group YvesRouterPluginRouteConverterTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginRouteConverterTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\Router\RouterYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->tester->mockEnvironmentConfig(RouterConstants::IS_CACHE_ENABLED, false);

        $this->tester->mockFactoryMethod('getRouteProviderPlugins', [
            new RouteProviderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testGenerateWillConvertRouteParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $generatedUrl = $router->generate('converter', ['parameter' => 'foo']);

        $this->assertSame('/route/foo', $generatedUrl);
    }
}
