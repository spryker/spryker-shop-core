<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Checker;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig;

class WishlistPageApplicabilityChecker implements WishlistPageApplicabilityCheckerInterface
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
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return bool
     */
    public function isRequestApplicable(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): bool
    {
        $isConfiguratorKeySupported = $this->isConfiguratorKeySupported(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getConfiguratorKeyOrFail(),
        );

        return $isConfiguratorKeySupported && $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getSourceType()
            === $this->productConfigurationWishlistWidgetConfig->getWishlistSourceType();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isResponseApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool
    {
        $isConfiguratorKeySupported = $this->isConfiguratorKeySupported(
            $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail(),
        );

        return $isConfiguratorKeySupported && $productConfiguratorResponseTransfer->getSourceType()
            === $this->productConfigurationWishlistWidgetConfig->getWishlistSourceType();
    }

    /**
     * @param string $configuratorKey
     *
     * @return bool
     */
    protected function isConfiguratorKeySupported(string $configuratorKey): bool
    {
        return in_array(
            $configuratorKey,
            $this->productConfigurationWishlistWidgetConfig->getSupportedConfiguratorKeys(),
            true,
        );
    }
}
