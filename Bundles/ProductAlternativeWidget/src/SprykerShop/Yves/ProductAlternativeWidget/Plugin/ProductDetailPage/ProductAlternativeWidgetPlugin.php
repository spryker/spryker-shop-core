<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductAlternativeWidget\ProductAlternativeWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ProductAlternativeWidgetPlugin extends AbstractWidgetPlugin implements ProductAlternativeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('products', $this->findAlternativesProducts($productViewTransfer))
            ->addWidgets($this->getFactory()->getProductDetailPageProductAlternativeWidgetPlugins());
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@ProductAlternativeWidget/views/product-alternative-list/product-alternative-list.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findAlternativesProducts(ProductViewTransfer $productViewTransfer): array
    {
        if (!$this->getFactory()->getProductAlternativeStorageClient()->isAlternativeProductApplicable($productViewTransfer)) {
            return [];
        }
        $productReplacementForStorage = $this->getFactory()->getProductAlternativeStorageClient()
            ->findProductAlternativeStorage($productViewTransfer->getSku());
        if (!$productReplacementForStorage) {
            return [];
        }

        return $this->mapProductViewTransferList($productReplacementForStorage);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer $productReplacementForStorage
     *
     * @return array
     */
    protected function mapProductViewTransferList(ProductAlternativeStorageTransfer $productReplacementForStorage): array
    {
        $productViewTransferList = [];
        foreach ($productReplacementForStorage->getProductAbstractIds() as $idProduct) {
            $productViewTransferList[] = $this->getAbstractProductView($idProduct);
        }

        foreach ($productReplacementForStorage->getProductConcreteIds() as $idProduct) {
            $productViewTransferList[] = $this->getConcreteProductView($idProduct);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function getAbstractProductView(int $idProduct): ?ProductViewTransfer
    {
        $productAbstractStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->getProductAbstractStorageData($idProduct, $this->getLocale());
        if (empty($productAbstractStorageData)) {
            return null;
        }

        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productAbstractStorageData, $this->getLocale());
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function getConcreteProductView(int $idProduct): ?ProductViewTransfer
    {
        $productConcreteStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->getProductConcreteStorageData($idProduct, $this->getLocale());
        if (empty($productConcreteStorageData)) {
            return null;
        }
        $productConcreteStorageData['attribute_map'] = new AttributeMapStorageTransfer();

        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productConcreteStorageData, $this->getLocale());
    }
}
