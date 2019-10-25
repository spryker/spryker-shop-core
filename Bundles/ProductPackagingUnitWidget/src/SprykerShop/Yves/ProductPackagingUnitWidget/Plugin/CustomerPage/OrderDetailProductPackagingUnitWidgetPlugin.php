<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\ProductPackagingUnit\OrderDetailProductPackagingUnitWidgetPluginInterface;

/**
 * @deprecated Use molecule('order-detail-product-packaging-unit', 'ProductPackagingUnitWidget') instead.
 *
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class OrderDetailProductPackagingUnitWidgetPlugin extends AbstractWidgetPlugin implements OrderDetailProductPackagingUnitWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer, string $currencyIsoCode): void
    {
        $this
            ->addParameter('item', $itemTransfer)
            ->addParameter('currencyIsoCode', $currencyIsoCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@ProductPackagingUnitWidget/views/order-detail-product-packaging-unit/order-detail-product-packaging-unit.twig';
    }
}
