<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Plugin\ProductRelationWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductRelationWidget\Dependency\Plugin\ProductWidget\ProductWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class ProductWidgetPlugin extends AbstractWidgetPlugin implements ProductWidgetPluginInterface
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
            ->addWidgets($this->getFactory()->getProductRelationWidgetSubWidgets());
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
        return '@ProductWidget/views/product-relation/product-relation.twig';
    }
}
