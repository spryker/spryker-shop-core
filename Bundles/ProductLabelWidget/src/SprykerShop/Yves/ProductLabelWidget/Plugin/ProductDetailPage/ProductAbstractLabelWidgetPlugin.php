<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductLabelWidget\ProductLabelWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetFactory getFactory()
 */
class ProductAbstractLabelWidgetPlugin extends AbstractWidgetPlugin implements ProductLabelWidgetPluginInterface
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
            ->addParameter('productLabelDictionaryItemTransfers', $this->getProductLabelDictionaryItems($productViewTransfer));
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
        return '@ProductLabelWidget/views/product-label-group/product-label-group.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function getProductLabelDictionaryItems(ProductViewTransfer $productViewTransfer)
    {
        return $this->getFactory()
            ->getProductLabelStorageClient()
            ->findLabelsByIdProductAbstract($productViewTransfer->getIdProductAbstract(), $this->getLocale());
    }
}
