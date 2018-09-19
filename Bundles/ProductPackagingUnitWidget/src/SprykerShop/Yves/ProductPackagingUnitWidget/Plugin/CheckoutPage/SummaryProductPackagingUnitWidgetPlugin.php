<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\CheckoutPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\ProductPackagingUnit\SummaryProductPackagingUnitWidgetPluginInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Widget\SummaryProductPackagingUnitWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductPackagingUnitWidget\Widget\SummaryProductPackagingUnitWidget instead.
 */
class SummaryProductPackagingUnitWidgetPlugin extends AbstractWidgetPlugin implements SummaryProductPackagingUnitWidgetPluginInterface
{
    /**
     * @param array $item
     *
     * @return void
     */
    public function initialize(array $item): void
    {
        $widget = new SummaryProductPackagingUnitWidget($item);

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
        return SummaryProductPackagingUnitWidget::getTemplate();
    }
}
