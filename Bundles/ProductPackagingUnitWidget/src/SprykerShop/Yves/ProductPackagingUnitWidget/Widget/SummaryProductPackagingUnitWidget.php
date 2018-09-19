<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SummaryProductPackagingUnitWidget extends AbstractWidget
{
    /**
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this
            ->addParameter('item', $item);
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
        return 'SummaryProductPackagingUnitWidget';
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
