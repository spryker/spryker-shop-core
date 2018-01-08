<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductOptionWidget\ProductOptionWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ProductOptionWidgetPlugin extends AbstractWidgetPlugin implements ProductOptionWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('productOptionGroups', $this->getProductOptionGroups($productViewTransfer));
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
        return '@ProductOptionWidget/_product-detail-page/product-options.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StorageProductOptionGroupTransfer[]
     */
    protected function getProductOptionGroups(ProductViewTransfer $productViewTransfer)
    {
        $productAbstractOptionStorageTransfer = $this->getStorageProductOptionGroupCollectionTransfer($productViewTransfer);
        if (!$productAbstractOptionStorageTransfer) {
            return [];
        }

        return $this->getStorageProductOptionGroupCollectionTransfer($productViewTransfer)->getProductOptionGroups();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function getStorageProductOptionGroupCollectionTransfer(ProductViewTransfer $productViewTransfer)
    {
        return $this
            ->getFactory()
            ->getProductOptionStorageClient()
            ->getProductOptions($productViewTransfer->getIdProductAbstract(), $this->getLocale());
    }
}
