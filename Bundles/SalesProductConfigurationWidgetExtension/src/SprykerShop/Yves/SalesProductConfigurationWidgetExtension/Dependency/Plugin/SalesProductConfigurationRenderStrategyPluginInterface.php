<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;

/**
 * Use this plugin to provide the template and data for the order item configuration display.
 */
interface SalesProductConfigurationRenderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for a product configuration.
     * - Hint: mostly the check should be done by the configuratorKey.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): bool;

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
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return mixed[]
     */
    public function getTemplateData(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): array;
}
