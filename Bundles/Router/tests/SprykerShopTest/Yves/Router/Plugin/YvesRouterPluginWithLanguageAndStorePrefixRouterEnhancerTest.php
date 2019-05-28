<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use SprykerShop\Shared\Router\RouterConstants;
use SprykerShop\Yves\Router\Plugin\RouteManipulator\LanguageDefaultRouteManipulatorPlugin;
use SprykerShop\Yves\Router\Plugin\RouteManipulator\StoreDefaultRouteManipulatorPlugin;
use SprykerShop\Yves\Router\Plugin\Router\YvesRouterPlugin;
use SprykerShop\Yves\Router\Plugin\RouterEnhancer\LanguagePrefixRouterEnhancerPlugin;
use SprykerShop\Yves\Router\Plugin\RouterEnhancer\StorePrefixRouterEnhancerPlugin;
use SprykerShopTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;
use Symfony\Component\Routing\RequestContext;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router * @group Plugin
 * @group YvesRouterPluginWithLanguageAndStorePrefixRouterEnhancerTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginWithLanguageAndStorePrefixRouterEnhancerTest extends Unit
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

        $this->tester->mockFactoryMethod('getRouteManipulatorPlugins', [
            new LanguageDefaultRouteManipulatorPlugin(),
            new StoreDefaultRouteManipulatorPlugin(),
        ]);

        $this->tester->mockFactoryMethod('getRouterEnhancerPlugins', [
            new LanguagePrefixRouterEnhancerPlugin(),
            new StorePrefixRouterEnhancerPlugin(),
        ]);
    }

    /**
     * @dataProvider matcherDataProvider
     *
     * @param string $url
     * @param string $routeName
     * @param string $language
     * @param string $store
     *
     * @return void
     */
    public function testMatchReturnsParameterWithLanguageAndStore(string $url, string $routeName, string $language, string $store): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $parameters = $router->match($url);

        $this->assertSame($routeName, $parameters['_route']);
        $this->assertSame($language, $parameters['language']);
        $this->assertSame($store, $parameters['store']);
    }

    /**
     * @dataProvider generatorDataProvider
     *
     * @param string $url
     * @param string $routeName
     * @param string $language
     * @param string $store
     *
     * @return void
     */
    public function testGenerateReturnsUrlWithLanguageAndStoreWhenLanguageAndStoreAreInContext(string $url, string $routeName, string $language, string $store): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $requestContext = new RequestContext();
        $requestContext->setParameter('language', $language);
        $requestContext->setParameter('store', $store);

        $router = $routerPlugin->getRouter();
        $router->setContext($requestContext);

        $generatedUrl = $router->generate($routeName);

        $this->assertSame($url, $generatedUrl);
    }

    /**
     * @dataProvider generatorWithoutLanguageAndStoreDataProvider
     *
     * @param string $url
     * @param string $routeName
     *
     * @return void
     */
    public function testGenerateReturnsUrlWithoutLanguageAndStoreWhenLanguageAndStoreAreNotInContext(string $url, string $routeName): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $generatedUrl = $router->generate($routeName);

        $this->assertSame($url, $generatedUrl);
    }

    /**
     * @return string[][]
     */
    public function matcherDataProvider(): array
    {
        return [
            ['/', 'home', 'en', 'US'],
            ['/de/DE', 'home', 'de', 'DE'],
            ['/de/DE/foo', 'foo', 'de', 'DE'],
            ['/foo', 'foo', 'en', 'US'],
        ];
    }

    /**
     * @return string[][]
     */
    public function generatorDataProvider(): array
    {
        return [
            ['/en/US', 'home', 'en', 'US'],
            ['/de/DE', 'home', 'de', 'DE'],
            ['/de/DE/foo', 'foo', 'de', 'DE'],
            ['/de/DE/foo', 'foo', 'de', 'DE'],
        ];
    }

    /**
     * @return string[][]
     */
    public function generatorWithoutLanguageAndStoreDataProvider(): array
    {
        return [
            ['/', 'home'],
            ['/foo', 'foo'],
        ];
    }
}
