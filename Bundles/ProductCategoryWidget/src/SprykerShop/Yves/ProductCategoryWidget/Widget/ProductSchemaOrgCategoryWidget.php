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
class ProductSchemaOrgCategoryWidget extends AbstractWidget
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
        return 'ProductSchemaOrgCategoryWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductCategoryWidget/views/product-detail-page-schema-org-category/product-detail-page-schema-org-category.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function getCategories(ProductViewTransfer $productViewTransfer): ArrayObject
    {
        $productAbstractCategoryStorageTransfers = $this->getFactory()
            ->getProductCategoryStorageClient()
            ->findBulkProductAbstractCategory(
                [$productViewTransfer->getIdProductAbstractOrFail()],
                $this->getLocale(),
                $this->getFactory()->getStoreClient()->getCurrentStore()->getNameOrFail(),
            );

        /** @var \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|false $productAbstractCategoryStorageTransfer */
        $productAbstractCategoryStorageTransfer = reset($productAbstractCategoryStorageTransfers);
        if (!$productAbstractCategoryStorageTransfer) {
            return new ArrayObject();
        }

        $productCategories = $productAbstractCategoryStorageTransfer->getCategories()->getArrayCopy();

        return new ArrayObject(array_reverse($productCategories));
    }
}
