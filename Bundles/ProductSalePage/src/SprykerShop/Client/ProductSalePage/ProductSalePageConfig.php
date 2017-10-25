<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\ProductSalePage;

use Pyz\Shared\ProductSale\ProductSaleConfig as SharedProductSaleConfig;
use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductSalePageConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getLabelSaleName()
    {
        return SharedProductSaleConfig::DEFAULT_LABEL_NAME;
    }
}
