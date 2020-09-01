<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Use this plugin to provide the template and data for product configuration display.
 */
interface ProductConfigurationRenderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for a product configuration.
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
     *  - Returns template path to be rendered.
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Specification:
     *  - Returns the data for template.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return mixed[]
     */
    public function getTemplateData(ProductConfigurationInstanceTransfer $productConfigurationInstance): array;
}
