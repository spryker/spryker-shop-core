<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

/**
 * Use this plugin to process the response from the product configurator and get the return page URL.
 */
interface ProductConfiguratorResponseStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this strategy plugin is applicable to execute.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool;

    /**
     * Specification:
     * - Processes a product configurator response.
     * - Resolves back URL for configurator response.
     * - If validation success than `isSuccessful` flag equals to true.
     * - Returns error messages when validation failed and `isSuccessful` flag equals to false.
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
    ): ProductConfiguratorResponseProcessorResponseTransfer;
}
