<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CartPage\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Router\Plugin\Router\YvesDevelopmentRouterPlugin;
use Spryker\Yves\Router\RouterDependencyProvider;
use SprykerShop\Yves\CartPage\Plugin\ProductConfiguratorGatewayPage\CartPageGatewayBackUrlResolverStrategyPlugin;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;

/**
 * @group SprykerShop
 * @group Yves
 * @group CartPage
 * @group Plugin
 * @group CartPageGatewayBackUrlResolverStrategyPluginTest
 */
class CartPageGatewayBackUrlResolverStrategyPluginTest extends Unit
{
    protected const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    protected const FAKE_SOURCE_TYPE = 'FAKE_SOURCE_TYPE';

    /**
     * @var \SprykerShop\Yves\CartPage\Plugin\ProductConfiguratorGatewayPage\CartPageGatewayBackUrlResolverStrategyPlugin
     */
    protected $cartPageGatewayBackUrlResolverStrategyPlugin;

    /**
     * @var \SprykerShopTest\Yves\CartPage\CartPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->cartPageGatewayBackUrlResolverStrategyPlugin = new CartPageGatewayBackUrlResolverStrategyPlugin();
    }

    /**
     * @return void
     */
    public function testCartPageGatewayBackUrlResolverStrategyPluginChecksIsApplicable(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(static::SOURCE_TYPE_CART);

        // Act
        $isApplicable = $this->cartPageGatewayBackUrlResolverStrategyPlugin->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertTrue(
            $isApplicable,
            'Expected that CartPageGatewayBackUrlResolverStrategyPlugin must be applicable when source type is SOURCE_TYPE_CART.'
        );
    }

    /**
     * @return void
     */
    public function testCartPageGatewayBackUrlResolverStrategyPluginChecksIsApplicableForFakeSourceType(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(static::FAKE_SOURCE_TYPE);

        // Act
        $isApplicable = $this->cartPageGatewayBackUrlResolverStrategyPlugin->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse(
            $isApplicable,
            'Expected that CartPageGatewayBackUrlResolverStrategyPlugin must be not applicable when source type is FAKE_SOURCE_TYPE.'
        );
    }

    /**
     * @return void
     */
    public function testCartPageGatewayBackUrlResolverStrategyPluginChecksIsApplicableForNullableSourceType(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(null);

        // Act
        $isApplicable = $this->cartPageGatewayBackUrlResolverStrategyPlugin->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse(
            $isApplicable,
            'Expected that CartPageGatewayBackUrlResolverStrategyPlugin must be not applicable when source type is null.'
        );
    }

    /**
     * @return void
     */
    public function testCartPageGatewayBackUrlResolverStrategyPluginResolvesBackUrl(): void
    {
        // Arrange
        $this->tester->setDependency(RouterDependencyProvider::ROUTER_PLUGINS, [new YvesDevelopmentRouterPlugin()]);
        $this->tester->setDependency(RouterDependencyProvider::ROUTER_ROUTE_PROVIDER, [new CartPageRouteProviderPlugin()]);

        // Act
        $backUrl = $this->cartPageGatewayBackUrlResolverStrategyPlugin->resolveBackUrl(new ProductConfiguratorResponseTransfer());

        // Assert
        $this->assertSame(
            '/cart',
            $backUrl,
            'Expected that CartPageGatewayBackUrlResolverStrategyPlugin must resolve back URL as cart URL.'
        );
    }
}
