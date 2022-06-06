<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin;

/**
 * Sets the properties of `ItemTransfer` to default values.
 *
 * Use this plugin if some of `ItemTransfer` properties need to be sanitized before executing items reordering.
 */
interface ReorderItemSanitizerPluginInterface
{
    /**
     * Specification:
     * - Sanitizes items data before reorder.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function execute(array $itemTransfers): array;
}
