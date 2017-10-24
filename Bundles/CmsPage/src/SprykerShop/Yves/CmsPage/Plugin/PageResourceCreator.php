<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class PageResourceCreator extends AbstractPlugin
{

    /**
     * @return \SprykerShop\Yves\CmsPage\ResourceCreator\PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return $this->getFactory()->createPageResourceCreator();
    }

}
