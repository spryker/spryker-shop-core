<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\CheckoutPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\ProductPackagingUnit\SummaryProductPackagingUnitWidgetPluginInterface;

/**
 * @deprecated Use NEW_MOLECULE instead.
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
        $this->addParameter('item', $item);
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
        return '@ProductPackagingUnitWidget/views/summary-product-packaging-unit/summary-product-packaging-unit.twig';
    }
}
