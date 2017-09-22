<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class CategoryResourceCreator extends AbstractPlugin
{

    /**
     * @return \Pyz\Yves\Collector\Creator\ResourceCreatorInterface
     */
    public function createCategoryResourceCreator()
    {
        return $this->getFactory()->createCategoryResourceCreator();
    }

}
