<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\WishlistWidget\WishlistWidgetPluginInterface;
use SprykerShop\Yves\WishlistWidget\Widget\PdpWishlistSelectorWidget;

/**
 * @deprecated Use \SprykerShop\Yves\WishlistWidget\Widget\PdpWishlistSelectorWidget instead.
 */
class WishlistWidgetPlugin extends AbstractWidgetPlugin implements WishlistWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new PdpWishlistSelectorWidget($productViewTransfer);

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
        return PdpWishlistSelectorWidget::getTemplate();
    }
}
