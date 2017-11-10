<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin;

use Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 *
 */
class StorageProductMapperPlugin extends AbstractPlugin implements StorageProductMapperPluginInterface
{
    /**
     * @param array $productStorageData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productStorageData, array $selectedAttributes)
    {
        return $this->getFactory()
            ->getStorageProductMapperPlugin()
            ->mapStorageProduct($productStorageData, $selectedAttributes);
    }
}
