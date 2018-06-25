<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\ProductPackagingUnit;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface SummaryProductPackagingUnitWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'SummaryProductPackagingUnitWidgetPlugin';

    /**
     * @param array $data
     *
     * @return void
     */
    public function initialize(array $data): void;
}
