<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductOptionWidget\CartItemProductOptionWidgetPluginInterface;
use SprykerShop\Yves\ProductOptionWidget\Widget\CartItemProductOptionWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductOptionWidget\Widget\CartItemProductOptionWidget instead.
 */
class CartItemProductOptionWidgetPlugin extends AbstractWidgetPlugin implements CartItemProductOptionWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $widget = new CartItemProductOptionWidget($itemTransfer);

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
        return CartItemProductOptionWidget::getTemplate();
    }
}
