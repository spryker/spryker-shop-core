<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductRelationWidget\SimilarProductsWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class SimilarProductsWidgetPlugin extends AbstractWidgetPlugin implements SimilarProductsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter('productCollection', $this->findRelatedProducts($productViewTransfer))
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
        return '@ProductRelationWidget/views/pdp-similar-products-carousel/pdp-similar-products-carousel.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findRelatedProducts(ProductViewTransfer $productViewTransfer)
    {
        return $this->getFactory()
            ->getProductRelationStorageClient()
            ->findRelatedProducts($productViewTransfer->getIdProductAbstract(), $this->getLocale());
    }
}
