<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CmsBlockWidget\Widget\ProductWithCmsBlockWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\CmsBlockWidget\ProductCmsBlockWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CmsBlockWidget\Widget\ProductWithCmsBlockWidget instead.
 */
class ProductCmsBlockWidgetPlugin extends AbstractWidgetPlugin implements ProductCmsBlockWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new ProductWithCmsBlockWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();
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
        return ProductWithCmsBlockWidget::getTemplate();
    }
}
