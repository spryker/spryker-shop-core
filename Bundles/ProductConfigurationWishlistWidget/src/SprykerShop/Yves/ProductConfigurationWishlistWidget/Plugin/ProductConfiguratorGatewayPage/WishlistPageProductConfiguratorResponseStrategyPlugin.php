<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class WishlistPageProductConfiguratorResponseStrategyPlugin extends AbstractPlugin implements ProductConfiguratorResponseStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseTransfer::productConfigurationInstance::configuratorKey` to be set.
     * - Expects `ProductConfiguratorResponseTransfer::sourceType` to be provided.
     * - Applicable when configurator key is supported.
     * - Applicable when source type is equal to wishlist.
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
            ->createWishlistPageApplicabilityChecker()
            ->isResponseApplicable($productConfiguratorResponseTransfer);
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseTransfer::productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorResponseTransfer::productConfiguratorRequestData::idWishlistItem` to be set.
     * - Requires `ProductConfiguratorResponseTransfer::productConfiguratorRequestData::sku` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Updates wishlist item product configuration.
     * - Resolves back URL.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::messages` containing error messages, if any was added.
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
            ->createProductConfiguratorResponseProcessor()
            ->processProductConfiguratorResponse(
                $productConfiguratorResponseTransfer,
                $configuratorResponseData,
            );
    }
}
