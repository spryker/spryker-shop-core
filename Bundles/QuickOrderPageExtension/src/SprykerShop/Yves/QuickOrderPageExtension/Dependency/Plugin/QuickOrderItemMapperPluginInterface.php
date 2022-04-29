<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderItemTransfer;

interface QuickOrderItemMapperPluginInterface
{
    /**
     * Specification:
     * - Maps array of data to QuickOrderItem transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function map(QuickOrderItemTransfer $quickOrderItemTransfer, array $data): QuickOrderItemTransfer;
}
