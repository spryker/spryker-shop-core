<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CartPage;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerShop\Client\CartPage\Model\CartItemReader;

class CartPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Client\CartPage\Model\CartItemReaderInterface
     */
    public function createCartItemReader()
    {
        return new CartItemReader($this->getCartItemTransformerPlugins());
    }

    /**
     * @return \SprykerShop\Client\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected function getCartItemTransformerPlugins()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_ITEM_TRANSFORMERS);
    }
}
