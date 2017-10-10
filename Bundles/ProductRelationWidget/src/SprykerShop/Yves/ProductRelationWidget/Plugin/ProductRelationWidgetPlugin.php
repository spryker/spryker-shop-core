<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductRelationWidget\ProductRelationWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class ProductRelationWidgetPlugin extends AbstractWidgetPlugin implements ProductRelationWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void
    {
        $this
            ->addParameter('product', $storageProductTransfer)
            ->addWidgets($this->getFactory()->getProductDetailPageSimilarProductsWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductRelationWidget/_product-detail-page/product-relations.twig';
    }

}
