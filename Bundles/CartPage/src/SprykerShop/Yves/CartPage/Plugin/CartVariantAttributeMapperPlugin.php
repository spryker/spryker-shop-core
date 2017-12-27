<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin;

use ArrayObject;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartVariantAttributeMapperPlugin extends AbstractPlugin implements CartVariantAttributeMapperPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     * @param string $localeName
     *
     * @return array
     */
    public function buildMap(ArrayObject $items, $localeName)
    {
        return $this->getFactory()->createCartItemsAttributeMapper()->buildMap($items, $localeName);
    }
}
