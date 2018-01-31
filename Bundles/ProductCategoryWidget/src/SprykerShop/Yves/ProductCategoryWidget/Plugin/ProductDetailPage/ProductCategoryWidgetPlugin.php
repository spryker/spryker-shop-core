<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductCategoryWidget\ProductCategoryWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductCategoryWidget\ProductCategoryWidgetFactory getFactory()
 */
class ProductCategoryWidgetPlugin extends AbstractWidgetPlugin implements ProductCategoryWidgetPluginInterface
{
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
        return '@ProductCategoryWidget/templates/product-detail-page-breadcrumb/product-detail-page-breadcrumb.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $productAbstractCategoryStorageTransfer = $this->getFactory()
            ->getProductCategoryStorageClient()
            ->findProductAbstractCategory($productViewTransfer->getIdProductAbstract(), $this->getLocale());

        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter('categories', $productAbstractCategoryStorageTransfer->getCategories());
    }
}
