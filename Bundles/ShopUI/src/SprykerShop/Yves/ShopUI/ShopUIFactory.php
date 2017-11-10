<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopUI;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopUI\Twig\ShopUITwigExtension;

class ShopUIFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createShopUITwigExtension()
    {
        return new ShopUITwigExtension();
    }
}
