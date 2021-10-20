<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Checker;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig;

class ProductDetailPageApplicabilityChecker implements ProductDetailPageApplicabilityCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig
     */
    protected $productConfiguratorGatewayPageConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig
     */
    public function __construct(ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig)
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
            === $this->productConfiguratorGatewayPageConfig->getPdpSourceType();
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
            === $this->productConfiguratorGatewayPageConfig->getPdpSourceType();
    }
}
