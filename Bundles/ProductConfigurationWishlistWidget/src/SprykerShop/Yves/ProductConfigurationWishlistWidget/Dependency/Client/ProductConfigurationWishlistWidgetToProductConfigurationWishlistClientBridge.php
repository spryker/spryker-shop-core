<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientBridge implements ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface
     */
    protected $productConfigurationWishlistClient;

    /**
     * @param \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface $productConfigurationWishlistClient
     */
    public function __construct($productConfigurationWishlistClient)
    {
        $this->productConfigurationWishlistClient = $productConfigurationWishlistClient;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer
    {
        return $this->productConfigurationWishlistClient->expandWithProductConfiguration($wishlistItemTransfer, $params);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->productConfigurationWishlistClient->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->productConfigurationWishlistClient->processProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData,
        );
    }
}
