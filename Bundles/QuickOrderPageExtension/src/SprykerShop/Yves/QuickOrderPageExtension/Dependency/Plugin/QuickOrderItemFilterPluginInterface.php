<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderItemTransfer;

interface QuickOrderItemFilterPluginInterface
{
    /**
     * Specification:
     * - Adjusts provided quick order item transfer data using the provided product concrete transfer data without generating warnings.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function filterItem(
        QuickOrderItemTransfer $quickOrderItemTransfer
    ): QuickOrderItemTransfer;
}
