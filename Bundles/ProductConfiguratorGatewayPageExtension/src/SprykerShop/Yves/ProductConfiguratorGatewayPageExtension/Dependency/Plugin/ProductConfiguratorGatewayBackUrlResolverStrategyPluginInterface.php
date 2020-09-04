<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

/**
 * Use this plugin to resolve back page url from the given configurator response.
 */
interface ProductConfiguratorGatewayBackUrlResolverStrategyPluginInterface
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
     * - Returns back URL for the provided configuration response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return string
     */
    public function resolveBackUrl(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): string;
}
