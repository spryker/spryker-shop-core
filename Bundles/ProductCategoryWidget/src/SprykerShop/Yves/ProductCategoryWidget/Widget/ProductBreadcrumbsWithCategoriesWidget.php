<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductCategoryWidget\ProductCategoryWidgetFactory getFactory()
 */
class ProductBreadcrumbsWithCategoriesWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('product', $productViewTransfer)
            ->addParameter('categories', $this->getCategories($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductBreadcrumbsWithCategoriesWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductCategoryWidget/views/product-detail-page-breadcrumb/product-detail-page-breadcrumb.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function getCategories(ProductViewTransfer $productViewTransfer): ArrayObject
    {
        $productAbstractCategoryStorageTransfer = $this->getFactory()
            ->getProductCategoryStorageClient()
            ->findProductAbstractCategory($productViewTransfer->getIdProductAbstract(), $this->getLocale());

        if ($productAbstractCategoryStorageTransfer === null) {
            return new ArrayObject();
        }

        return $productAbstractCategoryStorageTransfer->getCategories();
    }
}
