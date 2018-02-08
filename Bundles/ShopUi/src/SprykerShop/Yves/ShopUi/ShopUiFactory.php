<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopUi;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopUi\Twig\ShopUiTwigExtension;

class ShopUiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createShopUiTwigExtension()
    {
        return new ShopUiTwigExtension();
    }
}
