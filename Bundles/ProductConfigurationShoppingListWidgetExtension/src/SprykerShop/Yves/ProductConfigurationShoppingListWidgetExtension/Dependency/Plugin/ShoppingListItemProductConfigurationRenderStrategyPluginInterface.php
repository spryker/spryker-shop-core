<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;

/**
 * Use this plugin to provide the template and data for the shopping list item configuration display.
 */
interface ShoppingListItemProductConfigurationRenderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for a shopping list item product configuration.
     * - Hint: mostly the check should be done by the configuratorKey.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return bool
     */
    public function isApplicable(ProductConfigurationInstanceTransfer $productConfigurationInstance): bool;

    /**
     * Specification:
     * - Returns template to be rendered.
     * - It includes data to be used for the rendering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function getTemplate(ProductConfigurationInstanceTransfer $productConfigurationInstance): ProductConfigurationTemplateTransfer;
}
