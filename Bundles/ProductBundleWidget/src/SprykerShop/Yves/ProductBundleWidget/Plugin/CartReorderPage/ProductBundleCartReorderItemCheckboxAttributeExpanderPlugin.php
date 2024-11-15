<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\CartReorderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderItemCheckboxAttributeExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleCartReorderItemCheckboxAttributeExpanderPlugin extends AbstractPlugin implements CartReorderItemCheckboxAttributeExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Overwrites attribute name and value for bundle items.
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
        return $this->getFactory()->createBundleItemMapper()->mapProductBundleAttributes($attributes, $itemTransfer);
    }
}
