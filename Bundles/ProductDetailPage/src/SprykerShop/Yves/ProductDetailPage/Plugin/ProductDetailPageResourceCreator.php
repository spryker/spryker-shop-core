<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductDetailPageResourceCreator extends AbstractPlugin
{

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\ResourceCreator\ProductDetailPageResourceCreator
     */
    public function createProductResourceCreator()
    {
        return $this->getFactory()->createProductDetailPageResourceCreator();
    }

}
