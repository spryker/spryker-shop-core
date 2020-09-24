<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CartPage\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Router\Plugin\Router\YvesDevelopmentRouterPlugin;
use Spryker\Yves\Router\RouterDependencyProvider;
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
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     */
    protected const ROUTE_NAME_CART = 'cart';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_CART
     */
    protected const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    protected const FAKE_SOURCE_TYPE = 'FAKE_SOURCE_TYPE';

    /**
     * @var \SprykerShopTest\Yves\CartPage\CartPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCartPageGatewayBackUrlResolverStrategyPluginChecksIsApplicable(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(static::SOURCE_TYPE_CART);

        // Act
        $isApplicable = $this->tester->getCartPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertTrue($isApplicable);
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
        $isApplicable = $this->tester->getCartPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse($isApplicable);
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
        $isApplicable = $this->tester->getCartPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse($isApplicable);
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
        $backUrl = $this->tester->getCartPageGatewayBackUrlResolverStrategyPlugin()->resolveBackUrl(new ProductConfiguratorResponseTransfer());

        // Assert
        $this->assertSame('/cart', $backUrl);
    }
}
