<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     */
    protected const ROUTE_NAME_CART = 'cart';

    /**
     * @var \SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientInterface
     */
    protected $productConfigurationCartClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientInterface $productConfigurationCartClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     */
    public function __construct(
        ProductConfigurationCartWidgetToProductConfigurationCartClientInterface $productConfigurationCartClient,
        ChainRouterInterface $router
    ) {
        $this->productConfigurationCartClient = $productConfigurationCartClient;
        $this->router = $router;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationCartClient->processProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $productConfiguratorResponseProcessorResponseTransfer->setBackUrl(
            $this->router->generate(static::ROUTE_NAME_CART)
        );
    }
}
