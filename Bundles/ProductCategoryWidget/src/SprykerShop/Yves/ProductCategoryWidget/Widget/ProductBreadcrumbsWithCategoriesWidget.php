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
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @var string
     */
    protected const PARAMETER_CATEGORIES = 'categories';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string|null $httpReferer
     */
    public function __construct(ProductViewTransfer $productViewTransfer, ?string $httpReferer = null)
    {
        $this->addProductParameter($productViewTransfer);
        $this->addCategoriesParameter($this->getCategories($productViewTransfer, $httpReferer));
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
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return void
     */
    protected function addCategoriesParameter(ArrayObject $productCategoryStorageTransfers): void
    {
        $this->addParameter(static::PARAMETER_CATEGORIES, $productCategoryStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string|null $httpReferer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function getCategories(ProductViewTransfer $productViewTransfer, ?string $httpReferer = null): ArrayObject
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
        if (!$productAbstractCategoryStorageTransfer || !$productAbstractCategoryStorageTransfer->getCategories()->count()) {
            return new ArrayObject();
        }

        $productCategoryStorageTransfers = $productAbstractCategoryStorageTransfer->getCategories()->getArrayCopy();

        $sortedProductCategoryStorageTransfers = $this->getFactory()
            ->getProductCategoryStorageClient()
            ->sortProductCategories($productCategoryStorageTransfers);

        if (!$httpReferer) {
            return $this->getMainProductCategoryStorageTransfers($sortedProductCategoryStorageTransfers);
        }

        $filteredProductCategoryStorageTransfers = $this->getFactory()
            ->getProductCategoryStorageClient()
            ->filterProductCategoriesByHttpReferer($sortedProductCategoryStorageTransfers, $httpReferer);

        if (!$filteredProductCategoryStorageTransfers) {
            return $this->getMainProductCategoryStorageTransfers($sortedProductCategoryStorageTransfers);
        }

        return new ArrayObject($filteredProductCategoryStorageTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function getMainProductCategoryStorageTransfers(array $productCategoryStorageTransfers): ArrayObject
    {
        return new ArrayObject(array_slice($productCategoryStorageTransfers, 0, 1));
    }
}
