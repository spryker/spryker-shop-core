<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\DataProvider;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig;

class ProductConfiguratorButtonFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig
     */
    protected $productConfigurationWishlistWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig $productConfigurationWishlistWidgetConfig
     */
    public function __construct(ProductConfigurationWishlistWidgetConfig $productConfigurationWishlistWidgetConfig)
    {
        $this->productConfigurationWishlistWidgetConfig = $productConfigurationWishlistWidgetConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function getData(WishlistItemTransfer $wishlistItemTransfer): ProductConfiguratorRequestDataTransfer
    {
        return (new ProductConfiguratorRequestDataTransfer())
            ->setIdWishlistItem($wishlistItemTransfer->getIdWishlistItem())
            ->setSku($wishlistItemTransfer->getSkuOrFail())
            ->setConfiguratorKey($wishlistItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail())
            ->setSourceType($this->productConfigurationWishlistWidgetConfig->getWishlistSourceType());
    }
}
