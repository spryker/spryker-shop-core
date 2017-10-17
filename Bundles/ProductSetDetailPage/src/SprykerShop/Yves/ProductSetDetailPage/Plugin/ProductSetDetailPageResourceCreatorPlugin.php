<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class ProductSetDetailPageResourceCreatorPlugin extends AbstractPlugin
{

    /**
     * @return \Pyz\Yves\Collector\Creator\ResourceCreatorInterface
     */
    public function createProductSetDetailPageResourceCreator()
    {
        return $this->getFactory()->createProductSetDetailPageResourceCreator();
    }

}
