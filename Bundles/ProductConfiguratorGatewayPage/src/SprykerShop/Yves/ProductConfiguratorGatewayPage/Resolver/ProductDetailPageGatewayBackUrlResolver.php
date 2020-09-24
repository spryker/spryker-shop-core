<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ProductDetailPageGatewayBackUrlResolver implements ProductDetailPageGatewayBackUrlResolverInterface
{
    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_NAME_HOME
     */
    protected const ROUTE_NAME_HOME = 'home';

    protected const MAPPING_TYPE_SKU = 'sku';

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface $productStorageClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     */
    public function __construct(
        ProductConfiguratorGatewayPageToProductStorageClientInterface $productStorageClient,
        ChainRouterInterface $router
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->router = $router;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return string
     */
    public function resolveBackUrl(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): string
    {
        $productConfiguratorResponseTransfer->requireSku();

        $productConcreteStorageData = $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(
            static::MAPPING_TYPE_SKU,
            $productConfiguratorResponseTransfer->getSku()
        );

        if (!$productConcreteStorageData) {
            return $this->router->generate(static::ROUTE_NAME_HOME);
        }

        $productConcreteStorageTransfer = $this->mapProductConcreteStorageDataToProductConcreteStorageTransfer(
            $productConcreteStorageData,
            new ProductConcreteStorageTransfer()
        );

        return $this->productStorageClient->buildProductConcreteUrl($productConcreteStorageTransfer);
    }

    /**
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer
     */
    protected function mapProductConcreteStorageDataToProductConcreteStorageTransfer(
        array $productConcreteStorageData,
        ProductConcreteStorageTransfer $productConcreteStorageTransfer
    ): ProductConcreteStorageTransfer {
        return $productConcreteStorageTransfer->fromArray($productConcreteStorageData, true);
    }
}
