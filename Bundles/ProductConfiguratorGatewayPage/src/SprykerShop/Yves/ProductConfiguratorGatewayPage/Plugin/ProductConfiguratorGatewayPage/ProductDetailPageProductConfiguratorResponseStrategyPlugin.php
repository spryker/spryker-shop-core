<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductDetailPageProductConfiguratorResponseStrategyPlugin extends AbstractPlugin implements ProductConfiguratorResponseStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable when configurator key is supported.
     * - Applicable when source type is equal to PDP.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool
    {
        return $this->getFactory()
            ->createProductDetailPageApplicabilityChecker()
            ->isResponseApplicable($productConfiguratorResponseTransfer);
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseTransfer::productConfigurationInstance` to be set.
     * - Requires `ProductConfiguratorResponseTransfer::sku` to be set.
     * - Maps product configurator check sum response.
     * - Validates product configurator check sum response.
     * - Replaces configuration for a given product in the session.
     * - Resolves back URL.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `true` when processing went well.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `false` otherwise.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::messages` with errors if any exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->getFactory()
            ->createProductDetailPageProductConfiguratorResponseProcessor()
            ->processProductDetailPageProductConfiguratorResponse(
                $productConfiguratorResponseTransfer,
                $configuratorResponseData
            );
    }
}
