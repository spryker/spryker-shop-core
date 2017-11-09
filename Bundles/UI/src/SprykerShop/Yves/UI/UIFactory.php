<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\UI;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\UI\Twig\UIComponentsTwigExtension;

class UIFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createUIComponentsTwigExtension()
    {
        return new UIComponentsTwigExtension();
    }
}
