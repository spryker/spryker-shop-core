<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class CartProductPackagingUnitWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $this
            ->addParameter('item', $itemTransfer);
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
        return 'CartProductPackagingUnitWidget';
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
        return '@ProductPackagingUnitWidget/views/cart-product-packaging-unit/cart-product-packaging-unit.twig';
    }
}
