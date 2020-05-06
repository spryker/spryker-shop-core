<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class SimilarProductsWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('product', $productViewTransfer)
            ->addParameter('productCollection', $this->findRelatedProducts($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SimilarProductsWidget';
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
    protected function findRelatedProducts(ProductViewTransfer $productViewTransfer): array
    {
        $storeName = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore()
            ->getName();

        return $this->getFactory()
            ->getProductRelationStorageClient()
            ->findRelatedProducts($productViewTransfer->getIdProductAbstract(), $this->getLocale(), $storeName);
    }
}
