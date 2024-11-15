<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Plugin\CartReorderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderItemCheckboxAttributeExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetFactory getFactory()
 */
class ProductAvailabilityCartReorderItemCheckboxAttributeExpanderPlugin extends AbstractPlugin implements CartReorderItemCheckboxAttributeExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Overwrites attribute disabled for unavailable items.
     *
     * @api
     *
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $attributes, ItemTransfer $itemTransfer): array
    {
        return $this->getFactory()->createProductAvailabilityMapper()->mapProductAvailabilityAttributes($attributes, $itemTransfer);
    }
}
