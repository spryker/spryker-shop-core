<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\RedirectPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\RedirectPage\RedirectPageFactory getFactory()
 */
class RedirectResourceCreator extends AbstractPlugin
{
    /**
     * @return \SprykerShop\Yves\RedirectPage\ResourceCreator\RedirectResourceCreator
     */
    public function createRedirectResourceCreator()
    {
        return $this->getFactory()->createRedirectResourceCreator();
    }
}
