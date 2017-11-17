<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopTemplateUI;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopTemplateUI\Twig\ShopTemplateUITwigExtension;

class ShopTemplateUIFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createShopTemplateUITwigExtension()
    {
        return new ShopTemplateUITwigExtension();
    }
}
