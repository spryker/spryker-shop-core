<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * Provides an interface for expander plugins for the reorder item checkbox.
 */
interface CartReorderItemCheckboxAttributeExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the attributes for the reorder item checkbox.
     *
     * @api
     *
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $attributes, ItemTransfer $itemTransfer): array;
}
