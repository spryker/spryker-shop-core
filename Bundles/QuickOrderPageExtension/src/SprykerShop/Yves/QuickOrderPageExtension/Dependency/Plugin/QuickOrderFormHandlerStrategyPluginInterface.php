<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderFormHandlerStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this QuickOrderForm handler strategy is applicable for the provided form and request data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return bool
     */
    public function isApplicable(QuickOrderTransfer $quickOrderTransfer, array $params): bool;

    /**
     * Specification:
     * - Handles quick order form.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer
     */
    public function execute(QuickOrderTransfer $quickOrderTransfer, array $params): QuickOrderFormProcessResponseTransfer;
}
