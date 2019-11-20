<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductColorGroupWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductColorGroupWidget\ProductColorGroupWidgetFactory getFactory()
 */
class ProductColorGroupWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('product', $this->getExpandedProductViewTransfer($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductColorGroupWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductColorGroupWidget/views/product-color-group-widget/product-color-group-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getExpandedProductViewTransfer(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productViewExpanderPlugins = $this->getFactory()->getProductViewExpanderPlugins();

        if ($productViewExpanderPlugins === []) {
            return $productViewTransfer;
        }

        foreach ($productViewExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expand($productViewTransfer);
        }

        return $productViewTransfer;
    }
}
