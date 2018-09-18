<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReplacementForWidgetPlugin\ProductReplacementForWidgetPluginInterface;
use SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetConfig;
use SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForWidget;

/**
 * @depricated Use \SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForWidget instead.
 *
 * @method \SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetFactory getFactory()
 */
class ProductReplacementForWidgetPlugin extends AbstractWidgetPlugin implements ProductReplacementForWidgetPluginInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function initialize(string $sku): void
    {
        $this->addParameter('products', $this->findReplacementForProducts($sku))
            ->addWidgets($this->getFactory()->getProductDetailPageProductReplacementsForWidgetPlugins());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductReplacementForWidget::getTemplate();
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findReplacementForProducts(string $sku): array
    {
        $productViewTransferList = [];
        $productReplacementForStorage = $this->getFactory()->getProductAlternativeStorageClient()
            ->findProductReplacementForStorage($sku);
        if (!$productReplacementForStorage) {
            return $productViewTransferList;
        }
        foreach ($productReplacementForStorage->getProductConcreteIds() as $idProduct) {
            $productViewTransferList[] = $this->getProductViewTransfer($idProduct);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function getProductViewTransfer(int $idProduct): ?ProductViewTransfer
    {
        $productConcreteStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageData($idProduct, $this->getLocale());
        if (empty($productConcreteStorageData)) {
            return null;
        }
        $productConcreteStorageData[ProductReplacementForWidgetConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = new AttributeMapStorageTransfer();

        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productConcreteStorageData, $this->getLocale());
    }
}
