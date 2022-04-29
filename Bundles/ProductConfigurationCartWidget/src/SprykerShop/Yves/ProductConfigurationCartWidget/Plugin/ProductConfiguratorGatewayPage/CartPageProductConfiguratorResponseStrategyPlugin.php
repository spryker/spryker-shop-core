<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class CartPageProductConfiguratorResponseStrategyPlugin extends AbstractPlugin implements ProductConfiguratorResponseStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable when configurator key is supported.
     * - Applicable when source type is equal to cart.
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
            ->createCartPageApplicabilityChecker()
            ->isResponseApplicable($productConfiguratorResponseTransfer);
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse` to be set.
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse::sku` to be set.
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse::groupKey` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Gets current customer quote.
     * - Finds item in the quote.
     * - Handles quantity changes, adds corresponding messages to response.
     * - Replaces item in a quote.
     * - Resolves back URL.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::messages` containing error messages, if any was added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->getFactory()
            ->createProductConfiguratorResponseProcessor()
            ->processProductConfiguratorResponse(
                $productConfiguratorResponseTransfer,
                $configuratorResponseData,
            );
    }
}
