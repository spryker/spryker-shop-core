<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @uses \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS
     *
     * @var string
     */
    protected const ROUTE_NAME_WISHLIST_DETAILS = 'wishlist/details';

    /**
     * @see \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::addWishlistDetailsRoute()
     *
     * @var string
     */
    protected const PARAMETER_WISHLIST_NAME = 'wishlistName';

    /**
     * @var \SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface
     */
    protected $productConfigurationWishlistClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface $productConfigurationWishlistClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     */
    public function __construct(
        ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface $productConfigurationWishlistClient,
        ChainRouterInterface $router
    ) {
        $this->productConfigurationWishlistClient = $productConfigurationWishlistClient;
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
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationWishlistClient
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, $configuratorResponseData);

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $backUrl = $this->router->generate(
            static::ROUTE_NAME_WISHLIST_DETAILS,
            [static::PARAMETER_WISHLIST_NAME => $productConfiguratorResponseProcessorResponseTransfer->getWishlistName()],
        );

        return $productConfiguratorResponseProcessorResponseTransfer->setBackUrl($backUrl);
    }
}
