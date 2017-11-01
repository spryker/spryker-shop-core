<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\RedirectPage;

use SprykerShop\Yves\RedirectPage\ResourceCreator\RedirectResourceCreator;
use Spryker\Yves\Kernel\AbstractFactory;

class RedirectPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\RedirectPage\ResourceCreator\RedirectResourceCreator
     */
    public function createRedirectResourceCreator()
    {
        return new RedirectResourceCreator();
    }
}
