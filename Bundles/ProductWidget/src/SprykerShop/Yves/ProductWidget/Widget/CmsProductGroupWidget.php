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
class CmsProductGroupWidget extends AbstractWidget
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
            ->addWidgets($this->getFactory()->getCmsContentWidgetProductGroupWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CmsProductGroupWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductWidget/views/cms-product-group/cms-product-group.twig';
    }
}
