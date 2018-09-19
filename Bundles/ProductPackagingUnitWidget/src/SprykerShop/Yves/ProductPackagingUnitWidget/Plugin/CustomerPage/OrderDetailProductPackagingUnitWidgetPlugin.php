<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\ProductPackagingUnit\OrderDetailProductPackagingUnitWidgetPluginInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Widget\OrderDetailProductPackagingUnitWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductPackagingUnitWidget\Widget\OrderDetailProductPackagingUnitWidget instead.
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
        $widget = new OrderDetailProductPackagingUnitWidget($itemTransfer, $currencyIsoCode);

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
        return OrderDetailProductPackagingUnitWidget::getTemplate();
    }
}
