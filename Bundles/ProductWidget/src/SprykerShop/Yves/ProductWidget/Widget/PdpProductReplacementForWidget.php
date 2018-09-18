<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class PdpProductReplacementForWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this
            ->addParameter('product', $productViewTransfer)
            ->addWidgets($this->getFactory()->getProductReplacementForWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'PdpProductReplacementForWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductWidget/views/pdp-product/pdp-product.twig';
    }
}
