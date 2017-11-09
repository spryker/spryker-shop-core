<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\RedirectPage;

use SprykerShop\Yves\RedirectPage\ResourceCreator\RedirectResourceCreatorPlugin;
use Spryker\Yves\Kernel\AbstractFactory;

class RedirectPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\RedirectPage\ResourceCreator\RedirectResourceCreatorPlugin
     */
    public function createRedirectResourceCreator()
    {
        return new RedirectResourceCreatorPlugin();
    }
}
