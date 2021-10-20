<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Checker;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig;

class CartPageApplicabilityChecker implements CartPageApplicabilityCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig
     */
    protected $productConfiguratorGatewayPageConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig $productConfiguratorGatewayPageConfig
     */
    public function __construct(ProductConfigurationCartWidgetConfig $productConfiguratorGatewayPageConfig)
    {
        $this->productConfiguratorGatewayPageConfig = $productConfiguratorGatewayPageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return bool
     */
    public function isRequestApplicable(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): bool
    {
        $isConfiguratorKeySupported = in_array(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getConfiguratorKeyOrFail(),
            $this->productConfiguratorGatewayPageConfig->getSupportedConfiguratorKeys(),
            true,
        );

        return $isConfiguratorKeySupported && $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getSourceType()
            === $this->productConfiguratorGatewayPageConfig->getCartSourceType();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isResponseApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool
    {
        $isConfiguratorKeySupported = in_array(
            $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail(),
            $this->productConfiguratorGatewayPageConfig->getSupportedConfiguratorKeys(),
            true,
        );

        return $isConfiguratorKeySupported && $productConfiguratorResponseTransfer->getSourceType()
            === $this->productConfiguratorGatewayPageConfig->getCartSourceType();
    }
}
