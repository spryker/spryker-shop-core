<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ProductConfiguratorGatewayPage\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Router\Plugin\Router\YvesDevelopmentRouterPlugin;
use Spryker\Yves\Router\RouterDependencyProvider;
use SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientBridge;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory;

/**
 * @group SprykerShop
 * @group Yves
 * @group ProductConfiguratorGatewayPage
 * @group Plugin
 * @group ProductDetailPageGatewayBackUrlResolverStrategyPluginTest
 */
class ProductDetailPageGatewayBackUrlResolverStrategyPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    protected const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    protected const FAKE_SOURCE_TYPE = 'FAKE_SOURCE_TYPE';
    protected const FAKE_REDIRECT_URL = 'fake/redirect-url';

    /**
     * @var \SprykerShopTest\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductDetailPageGatewayBackUrlResolverStrategyPluginChecksIsApplicable(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(static::SOURCE_TYPE_PDP);

        // Act
        $isApplicable = $this->tester->getProductDetailPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertTrue(
            $isApplicable,
            'Expects that ProductDetailPageGatewayBackUrlResolverStrategyPlugin will be applicable.'
        );
    }

    /**
     * @return void
     */
    public function testProductDetailPageGatewayBackUrlResolverStrategyPluginChecksIsApplicableForFakeSourceType(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(static::FAKE_SOURCE_TYPE);

        // Act
        $isApplicable = $this->tester->getProductDetailPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse(
            $isApplicable,
            'Expects that ProductDetailPageGatewayBackUrlResolverStrategyPlugin wont be applicable for fake source type.'
        );
    }

    /**
     * @return void
     */
    public function testProductDetailPageGatewayBackUrlResolverStrategyPluginChecksIsApplicableForNullableSourceType(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSourceType(null);

        // Act
        $isApplicable = $this->tester->getProductDetailPageGatewayBackUrlResolverStrategyPlugin()->isApplicable($productConfiguratorResponseTransfer);

        // Assert
        $this->assertFalse(
            $isApplicable,
            'Expects that ProductDetailPageGatewayBackUrlResolverStrategyPlugin wont be applicable for nullable source type.'
        );
    }

    /**
     * @return void
     */
    public function testProductDetailPageGatewayBackUrlResolverStrategyPluginResolvesBackUrl(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSku($productConcreteTransfer->getSku());

        /** @var \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory $productConfiguratorGatewayPageFactoryMock */
        $productConfiguratorGatewayPageFactoryMock = $this->getMockBuilder(ProductConfiguratorGatewayPageFactory::class)
            ->onlyMethods(['getProductStorageClient'])
            ->getMock();

        $productConfiguratorGatewayPageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductConfiguratorGatewayPageToProductStorageClientBridgeMock($productConcreteTransfer));

        // Act
        $backUrl = $this->tester
            ->getProductDetailPageGatewayBackUrlResolverStrategyPluginMock($productConfiguratorGatewayPageFactoryMock)
            ->resolveBackUrl($productConfiguratorResponseTransfer);

        // Assert
        $this->assertSame(
            static::FAKE_REDIRECT_URL,
            $backUrl,
            'Expects that back URL will be equal to the fake/redirect-url.'
        );
    }

    /**
     * @return void
     */
    public function testProductDetailPageGatewayBackUrlResolverStrategyPluginResolvesBackUrlToFallbackUrl(): void
    {
        // Arrange
        $this->tester->setDependency(RouterDependencyProvider::ROUTER_PLUGINS, [new YvesDevelopmentRouterPlugin()]);
        $this->tester->setDependency(RouterDependencyProvider::ROUTER_ROUTE_PROVIDER, [new HomePageRouteProviderPlugin()]);

        $this->tester->setupStorageRedisConfig();

        $productConcreteTransfer = $this->tester->haveProduct();

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $backUrl = $this->tester->getProductDetailPageGatewayBackUrlResolverStrategyPlugin()->resolveBackUrl($productConfiguratorResponseTransfer);

        // Assert
        $this->assertSame(
            '/',
            $backUrl,
            'Expects that back URL will be equal to the fallback URL.'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface
     */
    protected function getProductConfiguratorGatewayPageToProductStorageClientBridgeMock(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConfiguratorGatewayPageToProductStorageClientInterface {
        $productConfiguratorGatewayPageToProductStorageClientBridgeMock = $this->getMockBuilder(ProductConfiguratorGatewayPageToProductStorageClientBridge::class)
            ->onlyMethods(['findProductConcreteStorageDataByMappingForCurrentLocale', 'buildProductConcreteUrl'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfiguratorGatewayPageToProductStorageClientBridgeMock
            ->method('findProductConcreteStorageDataByMappingForCurrentLocale')
            ->willReturn($productConcreteTransfer->toArray());

        $productConfiguratorGatewayPageToProductStorageClientBridgeMock
            ->method('buildProductConcreteUrl')
            ->willReturn(static::FAKE_REDIRECT_URL);

        return $productConfiguratorGatewayPageToProductStorageClientBridgeMock;
    }
}
