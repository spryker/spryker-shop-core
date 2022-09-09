<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig getConfig()
 */
class ShoppingListPageProductConfiguratorResponseStrategyPlugin extends AbstractPlugin implements ProductConfiguratorResponseStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseTransfer.productConfigurationInstance.configuratorKey` to be set.
     * - Expects `ProductConfiguratorResponseTransfer.sourceType` to be provided.
     * - Applicable when configurator key is supported.
     * - Applicable when source type is equal to shopping list.
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
            ->createShoppingListPageApplicabilityChecker()
            ->isResponseApplicable($productConfiguratorResponseTransfer);
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductConfiguratorResponseTransfer.productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorResponseTransfer.productConfiguratorRequestData.shoppingListItemUuid` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Updates shopping list item product configuration.
     * - Resolves back URL.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer.messages` containing error messages, if any was added.
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
