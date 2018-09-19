<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductPackagingUnitWidget\CartProductPackagingUnitWidgetPluginInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Widget\CartProductPackagingUnitWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductPackagingUnitWidget\Widget\CartProductPackagingUnitWidget instead.
 *
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class CartProductPackagingUnitWidgetPlugin extends AbstractWidgetPlugin implements CartProductPackagingUnitWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $widget = new CartProductPackagingUnitWidget($itemTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return CartProductPackagingUnitWidget::getTemplate();
    }
}
